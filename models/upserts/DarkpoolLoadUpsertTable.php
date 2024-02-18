<?php

namespace app\models\upserts;

use app\components\UpsertColumn;
use app\components\UpsertTable;
use app\models\west\WestDarkpool;

class DarkpoolLoadUpsertTable extends UpsertTable
{

    public function __construct()
    {
        $this->tableName = WestDarkpool::tableName();

        $this->columns = [
            UpsertColumn::create('trade_date')->noUpdate(),
            UpsertColumn::create('trade_time')->noUpdate(),
            UpsertColumn::create('ticker_id')->noUpdate(),
            UpsertColumn::create('ticker')->noUpdate(),
            UpsertColumn::create('deals_volume'),
            UpsertColumn::create('price'),
            UpsertColumn::create('notional'),
            UpsertColumn::create('percent_avg_30_day'),
            UpsertColumn::create('message'),
            UpsertColumn::create('type'),
            UpsertColumn::create('security_type_id'),
            UpsertColumn::create('deals_avg_30_day'),
            UpsertColumn::create('float'),
            UpsertColumn::create('earnings_date'),
            UpsertColumn::create('is_cancelled'),
            UpsertColumn::create('created_by')->noUpdate(),
            UpsertColumn::create('updated_by'),
            UpsertColumn::create('created_at')->noUpdate(),
            UpsertColumn::create('updated_at'),
        ];
        $this->onConflictConstraint = 'west_darkpool_date_ticker_unique';
    }

}
