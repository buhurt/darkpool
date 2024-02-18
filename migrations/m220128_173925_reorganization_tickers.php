<?php

use app\components\Migration;

/**
 * Class m220128_173925_reorganization_tickers
 */
class m220128_173925_reorganization_tickers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('trade.expit', ['ticker_id' => null], ['not', ['ticker_id' => null]]);
        $this->dropForeignKey('trade_expit_fk_ticker_id', 'trade.expit');
        $this->truncateTable('moex.ticker');
        $this->dropForeignKey('moex_ticker_fk_index_id', 'moex.ticker');
        $this->dropConstraint('moex_ticker_index_tiker_id_unique', 'moex.ticker');
        $this->addConstraint('moex_ticker_tiker_id_unique', 'moex.ticker', ['ticker']);
        $this->dropColumn('moex.ticker', 'index_id');
        $this->addColumn('moex.ticker', 'indexes', $this->json()->comment('Индексы биржи'));
        $this->addForeignKey('trade_expit_fk_ticker_id', 'trade.expit', 'ticker_id', 'moex.ticker', 'id', 'SET NULL', 'CASCADE');

        $this->createTable('trade.expit_visible', [
            'id' => $this->primaryKey(),
            'ticker_id' => $this->integer()->comment('Тикер'),
            'is_hide' => $this->boolean()->defaultValue(true)->comment('Скрыто'),
            'user_id' => $this->integer()->comment('Пользователь'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('trade.expit_visible', 'Скрыть тикер с графика');
        $this->addForeignKey('trade_expit_visible_fk_ticker_id', 'trade.expit_visible', 'ticker_id', 'moex.ticker', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('trade_expit_visible_fk_user_id', 'trade.expit_visible', 'user_id', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('trade_expit_visible_fk_created_by', 'trade.expit_visible', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('trade_expit_visible_fk_updated_by', 'trade.expit_visible', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('moex.ticker', 'indexes');
        $this->addColumn('moex.ticker', 'index_id', $this->integer()->comment('ID индекса'));
        $this->addForeignKey('moex_ticker_fk_index_id', 'moex.ticker', 'index_id', 'moex.index', 'id', 'SET NULL', 'CASCADE');
        $this->dropConstraint('moex_ticker_tiker_id_unique', 'moex.ticker');
        $this->addConstraint('moex_ticker_index_tiker_id_unique', 'moex.ticker', ['index_id', 'ticker']);
        $this->dropTable('trade.expit_visible');

    }
}
