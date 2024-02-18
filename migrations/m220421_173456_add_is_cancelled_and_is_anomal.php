<?php

use yii\db\Migration;

/**
 * Class m220421_173456_add_is_cancelled_and_is_anomaly
 */
class m220421_173456_add_is_cancelled_and_is_anomal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('west.darkpool', 'is_cancelled', $this->boolean()->defaultValue(false)->comment('Отмененная сделка'));
        $this->addColumn('west.darkpool', 'is_anomaly', $this->boolean()->defaultValue(false)->comment('Аномальный объем'));

        $this->update('west.darkpool', ['is_cancelled' => true], 'message ilike \'%cancelled%\'');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('west.darkpool', 'is_cancelled');
        $this->dropColumn('west.darkpool', 'is_anomaly');
    }
}
