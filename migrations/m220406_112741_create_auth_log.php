<?php

use yii\db\Migration;

/**
 * Class m220406_112741_create_auth_log
 */
class m220406_112741_create_auth_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('public.auth_log', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('Пользователь'),
            'location' => $this->string()->comment('Местонахождение'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Создано'),
            'ip' => $this->string()->comment('IP'),
            'user_agent' => $this->text()->comment('Данные пользователя'),
        ]);
        $this->addCommentOnTable('public.auth_log', 'История авторизаций пользователя');

        $this->addForeignKey('auth_log_fk_user_id', 'public.auth_log', 'user_id', 'public.user', 'id', 'SET NULL', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('public.auth_log');
    }
}
