<?php

namespace app\services\synchronization;

use app\helpers\CsvParseHelper;
use app\models\morningstar\MorningstarData;
use app\models\morningstar\MorningstarFund;
use app\models\morningstar\MorningstarSource;
use app\models\Sync;
use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class MorningstarSyncService extends CsvParseHelper
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
    public function getData(): void
    {
        $sources = MorningstarSource::find()->all();
        $client = new Client();
        foreach ($sources as $source) {
            $response = $client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setMethod('get')
                ->setHeaders(['apikey' => $source->apikey])
                ->setUrl($source->url)
                ->send();
            if ($response->isOk) {
                $datas = $response->data['rows'];
                foreach ($datas as $data) {
                    $fund = MorningstarFund::findOne(['sec_id' => $data['secId']]);
                    if ($fund === null) {
                        $fund = new MorningstarFund();
                        $fund->sec_id = $data['secId'];
                        $fund->name = $data['name'];
                        $fund->save();
                    }
                    if (!empty($data['name']) && !empty($data['date'])) {
                        $owner = new MorningstarData();
                        $owner->source_id = $source->id;
                        $owner->fund_id = $fund->id;
                        $owner->name = $data['name'];
                        $owner->current_shares = $data['currentShares'];
                        $owner->total_shares_held = $data['totalSharesHeld'];
                        $owner->total_assets = $data['totalAssets'];
                        $owner->change_amount = $data['changeAmount'];
                        $owner->change_percentage = $data['changePercentage'];
                        $owner->date = Yii::$app->formatter->asDate($data['date'], 'php:Y-m-d');
                        $owner->save();
                    }
                }
            }
        }
    }
}
