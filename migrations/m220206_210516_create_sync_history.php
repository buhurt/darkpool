<?php

use yii\db\Migration;

/**
 * Class m220206_210516_create_sync_history
 */
class m220206_210516_create_sync_history extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('public.sync', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->comment('Название синки'),
            'date_start' => $this->dateTime()->comment('Начало'),
            'date_end' => $this->dateTime()->comment('Конец'),
            'status' => $this->integer()->comment('Статус'),
            'result' => $this->string()->comment('Результат'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);
        $this->addCommentOnTable('public.sync', 'Синхронизации');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('public.sync');
    }
}
