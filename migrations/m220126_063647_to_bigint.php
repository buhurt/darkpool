<?php

use yii\db\Migration;

/**
 * Class m220126_063647_to_bigint
 */
class m220126_063647_to_bigint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('trade.expit', 'count_paper', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('trade.expit', 'count_paper', $this->integer());
    }
}
