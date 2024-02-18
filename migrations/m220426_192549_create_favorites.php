<?php

use app\components\Migration;

/**
 * Class m220426_192549_create_favorites
 */
class m220426_192549_create_favorites extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
// создаем табличку избранного
        $this->createTable('west.favorites', [
            'id' => $this->primaryKey(),
            'ticker_id' => $this->integer()->comment('Тикер'),
            'ticker' => $this->string()->comment('Тикер'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.favorites', 'Избранное');
        $this->addForeignKey('west_favorites_fk_created_by', 'west.favorites', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_favorites_fk_updated_by', 'west.favorites', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_favorites_fk_ticker_id', 'west.favorites', 'ticker_id', 'west.ticker', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_favorites_ticker_user_unique', 'west.favorites', ['ticker_id', 'created_by']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('west.favorites');
    }
}
