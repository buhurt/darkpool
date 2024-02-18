<?php

use app\components\Migration;


/**
 * Class m220121_174846_fix_constraint
 */
class m220121_174846_fix_constraint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropConstraint('trade_expit_date_ticker_unique', 'trade.expit');
        $this->addConstraint('trade_expit_date_ticker_unique', 'trade.expit', ['trade_date', 'trade_day', 'ticker_id', 'period_execution', 'deals_volume']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropConstraint('trade_expit_date_ticker_unique', 'trade.expit');
        $this->addConstraint('trade_expit_date_ticker_unique', 'trade.expit', ['trade_date', 'trade_day', 'ticker_id', 'period_execution']);
    }
}
