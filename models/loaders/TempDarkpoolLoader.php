<?php

namespace app\models\loaders;

use app\components\ModelLoader;


/**
 * Description of EmailCsvLoader
 *
 */
class TempDarkpoolLoader extends ModelLoader
{
    protected $mappedAttributes = [
        'Timestamp' => 'timestamp',
        'Ticker' => 'ticker',
        'Volume' => 'volume',
        'Price' => 'price',
        'Pct_of_Avg30Day' => 'percent_avg_30_day',
        'Message' => 'message',
        'Type' => 'type',
        'SecurityType' => 'security_type',
        'Industry' => 'industry',
        'Sector' => 'sector',
        'Avg30Day' => 'deals_avg_30_day',
        'Float' => 'float',
        'EarningsDate' => 'earnings_date',
    ];

}












