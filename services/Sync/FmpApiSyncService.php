<?php
/**
 * Класс для синхронизации данных с сервиса https://fmpcloud.io
 */

namespace app\services\sync;

use app\helpers\CsvParseHelper;
use app\models\Sync;
use app\models\west\WestExchange;
use app\models\west\WestTicker;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class FmpApiSyncService extends CsvParseHelper
{
    public Sync $sync;
    public int $result;

    public function __construct()
    {
        $this->sync = new Sync();
        $this->result = 0;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function getTicker(): void
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('get')
            ->setUrl("https://fmpcloud.io/api/v3/stock/list?apikey=2f3678cbd392df4ecccd3da6c3215dd6")
            ->send();
        if ($response->isOk) {
            foreach ($response->data as $ticker) {
                $exchange = WestExchange::getExchangeIdIfExist($ticker['exchange'], $ticker['exchangeShortName']);
                WestTicker::createOrUpdate($ticker['symbol'], $ticker['name'], $exchange, $ticker['type']);
            }
        }
    }
}
