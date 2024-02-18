<?php

namespace app\models\upserts;

use app\components\UpsertColumn;
use app\components\UpsertTable;
use app\models\west\TempWestDarkpool;

class TempDarkpoolLoadUpsertTable extends UpsertTable
{

    public function __construct()
    {
        $this->tableName = TempWestDarkpool::tableName();

        $this->columns = [
            UpsertColumn::create('date'),
            UpsertColumn::create('timestamp'),
            UpsertColumn::create('ticker'),
            UpsertColumn::create('volume'),
            UpsertColumn::create('price'),
            UpsertColumn::create('percent_avg_30_day'),
            UpsertColumn::create('notional'),
            UpsertColumn::create('message'),
            UpsertColumn::create('type'),
            UpsertColumn::create('security_type'),
            UpsertColumn::create('industry'),
            UpsertColumn::create('sector'),
            UpsertColumn::create('deals_avg_30_day'),
            UpsertColumn::create('float'),
            UpsertColumn::create('earnings_date'),
        ];
    }

}
