<?php

use yii\db\Migration;

/**
 * Class m231225_082522_create_settings_bot
 */
class m231225_082522_create_settings_bot extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('CREATE SCHEMA "bot"');
        $this->execute('COMMENT ON SCHEMA "bot" IS \'Настройки для бота\';');
        $this->createTable('bot.settings', [
            'id' => $this->primaryKey(),
            'lightning' => $this->boolean()->defaultValue(false)->comment('Молния'),
            'include_words' => $this->text()->comment('Ключевые слова'),
            'exclude_words' => $this->text()->comment('Исключаемые слова'),
            'created_by' => $this->integer()->comment('Кто создал'),
            'updated_by' => $this->integer()->comment('Кто изменил'),
            'created_at' => $this->timestamp()->defaultExpression('NOW()')->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата изменения'),
        ]);

        $this->insert('bot.settings', [
            'lightning' => true,
            'include_words' => null,
            'exclude_words' => 'СРЕДНЕВЗВЕШЕННЫЙ КУРС; ОБЛИГАЦИЙ; КАЗАХСТАНА; АЗЕРБАЙДЖАНА; КАЗАХСТАНЕ; АЗЕРБАЙДЖАНЕ; ',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('DROP SCHEMA "bot" CASCADE');
    }
}
