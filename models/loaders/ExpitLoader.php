<?php

namespace app\models\loaders;

use app\components\ModelLoader;


/**
 * Description of EmailCsvLoader
 *
 */
class ExpitLoader extends ModelLoader
{

    protected $mappedAttributes = [
        'Торговый день' => 'trade_day',
        'Код ц/б' => 'ticker',
        'Наименование эмитента, вид ц/б' => 'emitent_name',
        'Номер гос. регистрации' => 'reg_num',
        'Срок исполнения, дней' => 'period_execution',
        'Кол-во сделок' => 'deals_count',
        'Объем сделок, руб.' => 'deals_volume',
        'Кол-во ц/б' => 'count_paper',
        'Крупные сделки' => 'big_deals',
    ];

}












