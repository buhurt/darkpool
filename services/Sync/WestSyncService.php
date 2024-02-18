<?php

namespace app\services\sync;

use app\components\BatchUpserter;
use app\helpers\CsvParseHelper;
use app\models\loaders\DarkpoolLoader;
use app\models\loaders\TempDarkpoolLoader;
use app\models\Sync;
use app\models\upserts\DarkpoolLoadUpsertTable;
use app\models\upserts\TempDarkpoolLoadUpsertTable;
use app\models\west\TempWestDarkpool;
use app\models\west\WestDarkpool;
use app\models\west\WestTicker;
use League\Csv\Exception;
use League\Csv\Reader;
use Yii;
use yii\base\InvalidConfigException;

class WestSyncService extends CsvParseHelper
{
    /**
     * Лимит строк для одного пакета (вставка данных в бд)
     */
    const LINE_LIMIT = 100;
    public Sync $sync;
    public int $result;

    /**
     * @var string[]
     */
    private array $csvFile = [];

    public function __construct()
    {
        $this->sync = new Sync();
        $this->result = 0;
    }

    public static function runFromFile($fileName): bool
    {
        $t = new static();
        $t->csvFile[] = $fileName;
        $t->processCsv();
        return true;
    }

    /**
     * Обработка csv-файлов
     */
    private function processCsv(): void
    {
        foreach ($this->csvFile as $item) {
            Yii::info("Start upload $item to database");
            try {
                $this->saveCsvToDatabase($item);
            } catch (Exception|InvalidConfigException $e) {
                continue;
            }
        }
    }

    /**
     * Сохранение csv-файла в бд
     * @throws Exception|InvalidConfigException
     */
    private function saveCsvToDatabase(string $fileName): void
    {
        $syncRes = $this->sync->create('uploadFileWest');
        $handle = Reader::createFromPath($fileName, 'r');
        $handle->setHeaderOffset(0);
        TempWestDarkpool::deleteAll();
        $batchUpserter = new BatchUpserter(new TempDarkpoolLoadUpsertTable());
        foreach ($handle as $data) {
            $model = new TempWestDarkpool();
            $model->setLoader(new TempDarkpoolLoader());
            $model->load($data);
            $model->setNotional((int)$data['Notional']);
            $model->setDate(!empty($data['Date']) ? Yii::$app->formatter->asDate(strtotime($data['Date']), 'php:Y-m-d') : null);
            $model->setEarningsDate(
                !empty($data['EarningsDate']) ? Yii::$app->formatter->asDate(
                    strtotime($data['EarningsDate']),
                    'php:Y-m-d'
                ) : null
            );
            $batchUpserter->addModel($model, true);
            $this->result++;
        }
        $batchUpserter->flushWithoutConstraint();
        unset($batchUpserter);
        unlink($fileName);
        $this->updateDataToDarkpool();
        $syncRes->endSync(count($handle) . ' | ' . $this->result);
        Yii::$app->cache->flush();
    }

    /**
     * Обновление данных в таблице из временной после синки
     */
    public function updateDataToDarkpool(): int
    {
        $limit = 100;
        for ($i = 0; $i >= 0; $i += $limit) {
            $data = TempWestDarkpool::getDataForInsert($i, $limit);
            if (empty($data)) {
                return 1;
            }
            $batchUpserter = new BatchUpserter(new DarkpoolLoadUpsertTable(), ['validate' => false]);
            /** @var TempWestDarkpool[] $item */
            foreach ($data as $item) {
                $model = new WestDarkpool();
                $model->setLoader(new DarkpoolLoader());
                $model->load($item);
                $model->setTickerId(WestTicker::getTickerIdIfExist($item['ticker']));
                $model->setSecurityTypeId(null);
                $batchUpserter->addModel($model);
            }
            $batchUpserter->flush();
        }
    }
}
