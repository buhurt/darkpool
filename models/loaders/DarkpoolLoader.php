<?php

namespace app\models\loaders;

use app\components\ModelLoader;


/**
 * Description of EmailCsvLoader
 *
 */
class DarkpoolLoader extends ModelLoader
{
    protected $mappedAttributes = [
        'date' => 'trade_date',
        'timestamp' => 'trade_time',
        'ticker' => 'ticker',
        'volume' => 'deals_volume',
        'price' => 'price',
        'percent_avg_30_day' => 'percent_avg_30_day',
        'notional' => 'notional',
        'message' => 'message',
        'type' => 'type',
        'deals_avg_30_day' => 'deals_avg_30_day',
        'float' => 'float',
        'is_cancelled' => 'is_cancelled',
    ];

}












