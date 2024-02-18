<?php

use yii\db\Migration;

/**
 * Class m220321_115517_fix_notional_bigint
 */
class m220321_115517_fix_notional_bigint extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('west.darkpool', 'notional', $this->bigInteger());
        $this->alterColumn('temp.west_darkpool', 'notional', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('west.darkpool', 'notional', $this->integer());
        $this->alterColumn('temp.west_darkpool', 'notional', $this->integer());
    }
}
