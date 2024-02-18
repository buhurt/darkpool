<?php

use yii\db\Migration;

/**
 * Class m221217_183340_add_hide_value_to_westdark_pool
 */
class m221217_183340_add_hide_value_to_westdark_pool extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('west.darkpool', 'is_hide', $this->boolean()->defaultValue(false)->comment('Скрыть значение из графика'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('west.darkpool', 'is_hide');
    }
}
