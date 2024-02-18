<?php

use app\components\Migration;


/**
 * Class m220412_192329_create_exchange_list
 */
class m220412_192329_create_exchange_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // создаем табличку списка бирж
        $this->createTable('west.exchange', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название'),
            'name_short' => $this->string()->comment('Короткое название'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.exchange', 'Биржи');
        $this->addForeignKey('west_exchange_fk_created_by', 'west.exchange', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_exchange_fk_updated_by', 'west.exchange', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_exchange_name_unique', 'west.exchange', ['name_short']);

        $this->addColumn('west.ticker', 'exchange_id', $this->integer()->comment('Биржа'));
        $this->addColumn('west.ticker', 'type_id', $this->integer()->comment('Тип'));
        $this->addColumn('west.ticker', 'type', $this->string()->comment('Тип'));
        $this->addForeignKey('west_ticker_fk_exchange_id', 'west.ticker', 'exchange_id', 'west.exchange', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('west_ticker_fk_exchange_id', 'west.ticker');
        $this->dropColumn('west.ticker', 'exchange_id');
        $this->dropColumn('west.ticker', 'type_id');
        $this->dropColumn('west.ticker', 'type');
        $this->dropTable('west.exchange');
    }
}
