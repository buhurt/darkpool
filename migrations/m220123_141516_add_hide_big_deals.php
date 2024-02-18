<?php

use yii\db\Migration;

/**
 * Class m220123_141516_add_hide_big_deals
 */
class m220123_141516_add_hide_big_deals extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('trade.expit', 'is_hide', $this->boolean()->defaultValue(false)->comment('Скрыть значение из графика'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('trade.expit', 'is_hide');
    }
}
