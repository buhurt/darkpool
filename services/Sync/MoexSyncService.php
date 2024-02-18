<?php

namespace app\services\sync;

use app\components\BatchUpserter;
use app\helpers\CsvParseHelper;
use app\models\loaders\ExpitLoader;
use app\models\MoexIndex;
use app\models\MoexTicker;
use app\models\Sync;
use app\models\TradeExpit;
use app\models\upserts\ExpitLoadUpsertTable;
use JsonException;
use models\dto\IndexDto;
use models\dto\TickerDto;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\CurlTransport;

class MoexSyncService extends CsvParseHelper
{
    public Sync $sync;
    public int $result;
    private MoexTicker $ticker;
    private MoexIndex $index;

    public function __construct()
    {
        $this->ticker = new MoexTicker();
        $this->index = new MoexIndex();
        $this->sync = new Sync();
        $this->result = 0;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getIndexes(): void
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_XML)
            ->setMethod('get')
            ->setUrl('https://iss.moex.com/iss/statistics/engines/stock/markets/index/analytics.xml')
            ->send();
        if ($response->isOk) {
            $indexes = $response->data['data']['rows']['row'];
            foreach ($indexes as $index) {
                (new MoexIndex())->create(new IndexDto($index));
            }
        }
    }

    /**
     * @throws JsonException
     */
    public function getTicker(): void
    {
        $indexes = $this->index->getAllData();
        $client = new Client();
        foreach ($indexes as $index) {
            try {
                $response = $client->createRequest()
                    ->setFormat(Client::FORMAT_XML)
                    ->setMethod('get')
                    ->setUrl("https://iss.moex.com/iss/statistics/engines/stock/markets/index/analytics/$index->moex_id/tickers.xml")
                    ->send();
            } catch (\Exception $exception) {
                continue;
            }
            if ($response->isOk) {
                $tickers = $response->data['data']['rows']['row'];
                foreach ($tickers as $ticker) {
                    $tickerDto = new TickerDto($ticker);
                    $modelTicker = MoexTicker::getTickerModel($tickerDto->getName());
                    if ($modelTicker === null) {
                        (new MoexTicker())->create($index, $tickerDto);
                    } else {
                        $indexes = json_decode($modelTicker->getIndexes(), false, 512, JSON_THROW_ON_ERROR);
                        $indexes[] = $index->id;
                        $indexes2 = json_encode($indexes, JSON_THROW_ON_ERROR);
                        $modelTicker->setIndexes($indexes2);
                        $modelTicker->save();
                    }
                }
            }
        }
    }

    /**
     * Получение сделок
     */
    public function getExpit(): bool
    {
        $sync = $this->sync->create('syncExpit');
        try {
            $dateFrom = date('Ymd', strtotime('-5day'));
            $dateTo = date('Ymd', strtotime('-1day'));
            $tickers = $this->ticker->getTickerList(null, true);
            $client = new Client([
                'transport' => CurlTransport::class,
            ]);
            foreach ($tickers as $ticker) {
                $filename = tempnam(sys_get_temp_dir(), 'guzzle-download-');
                $fh = fopen($filename, 'wb');
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl("https://www.moex.com/ru/expit/securitytrades-csv.aspx?day1=$dateFrom&day2=$dateTo&issue=$ticker->ticker&type=2")
                    ->setOutputFile($fh)
                    ->send();
                if ($response->isOk) {
                    $handle = fopen($filename, 'rb');
                    if ($handle === false) {
                        Yii::error('Unable to find file ' . $filename);
                        return false;
                    }
                    $row = 1;
                    $processing = true;
                    $batchUpserter = new BatchUpserter(new ExpitLoadUpsertTable());
                    while ($processing) {
                        for ($i = 0; $i < 10000; $i++) {
                            $line = stream_get_line($handle, 4096, "\n");
                            if ($line === false) {
                                Yii::info("Processing stopped at $row line from file $filename");
                                $processing = false;
                                break;
                            }
                            $data = str_getcsv(mb_convert_encoding($line, 'utf-8', 'windows-1251'), ';');
                            if ($row > 1 && ($data[1] === "N" || $data[1] === "Y")) {
                                $model = new TradeExpit();
                                $model->setLoader(new ExpitLoader());
                                $model->setMaxPrice(str_replace(',', '.', $data['9']));
                                $model->setMinPrice(str_replace(',', '.', $data['10']));
                                $model->setEstPrice(str_replace(',', '.', $data['11']));
                                $model->setTickerId($ticker->id);
                                $model->setTradeDate(Yii::$app->formatter->asDate($data[0], 'php:Y-m-d'));
                                $model->load($this->processData($data));
                                $batchUpserter->addModel($model);
                            } elseif ($row === 1) {
                                array_pop($data);
                                $this->setHeader($data);
                            }
                            $row++;
                        }
                    }
                    $batchUpserter->flush();
                    unset($batchUpserter);
                    $this->result++;
                    fclose($handle);
                    unlink($filename);
                }
            }
            $sync->endSync($this->result);
            return true;
        } catch (\Exception $e) {
            $sync->endSync($e->getMessage(), Sync::SYNC_STATUS_ERROR);
            echo $e->getMessage() . ' in ' . $e->getFile() . ' on ' . $e->getLine();
            return false;
        }
    }
}
