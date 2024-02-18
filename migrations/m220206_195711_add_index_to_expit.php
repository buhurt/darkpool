<?php

use yii\db\Migration;

/**
 * Class m220206_195711_add_index_to_expit
 */
class m220206_195711_add_index_to_expit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('trade_expit_ticker_date', 'trade.expit', 'ticker, trade_date');
        $this->createIndex('trade_expit_ticker_id_date', 'trade.expit', 'ticker_id, trade_date');
        $this->createIndex('trade_expit_ticker_id', 'trade.expit', 'ticker_id, is_hide');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('trade_expit_ticker_date', 'trade.expit');
        $this->dropIndex('trade_expit_ticker_id_date', 'trade.expit');
        $this->dropIndex('trade_expit_ticker_id', 'trade.expit');
    }
}
