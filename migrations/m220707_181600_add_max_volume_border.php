<?php


/**
 * Class m220707_181600_add_max_volume_border
 */
class m220707_181600_add_max_volume_border extends \app\components\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('west.volume_boundary', [
            'id' => $this->primaryKey(),
            'ticker_id' => $this->integer()->comment('Тикер'),
            'ticker' => $this->string()->comment('Тикер'),
            'value' => $this->integer()->comment('Значение'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('west.volume_boundary', 'Верхняя граница объемов');
        $this->addForeignKey('west_volume_boundary_fk_created_by', 'west.volume_boundary', 'created_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_volume_boundary_fk_updated_by', 'west.volume_boundary', 'updated_by', 'public.user', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('west_volume_boundary_fk_ticker_id', 'west.volume_boundary', 'ticker_id', 'west.ticker', 'id', 'SET NULL', 'CASCADE');
        $this->addConstraint('west_volume_boundary_ticker_user_unique', 'west.volume_boundary', ['ticker_id', 'created_by']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('west.volume_boundary');
    }
}
