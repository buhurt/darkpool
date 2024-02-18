<?php

namespace app\models\upserts;

use app\components\UpsertColumn;
use app\components\UpsertTable;
use app\models\TradeExpit;

class ExpitLoadUpsertTable extends UpsertTable
{

    public function __construct()
    {
        $this->tableName = TradeExpit::tableName();

        $this->columns = [
            UpsertColumn::create('trade_date')->noUpdate(),
            UpsertColumn::create('trade_day')->noUpdate(),
            UpsertColumn::create('ticker_id')->noUpdate(),
            UpsertColumn::create('ticker')->noUpdate(),
            UpsertColumn::create('emitent_name'),
            UpsertColumn::create('reg_num'),
            UpsertColumn::create('period_execution')->noUpdate(),
            UpsertColumn::create('deals_count'),
            UpsertColumn::create('deals_volume'),
            UpsertColumn::create('count_paper'),
            UpsertColumn::create('max_price'),
            UpsertColumn::create('min_price'),
            UpsertColumn::create('est_price'),
            UpsertColumn::create('big_deals'),
            UpsertColumn::create('created_by'),
            UpsertColumn::create('updated_by'),
            UpsertColumn::create('created_at')->noUpdate(),
            UpsertColumn::create('updated_at'),
        ];
        $this->onConflictConstraint = 'trade_expit_date_ticker_unique';
    }

}
