<?php

namespace app\models\Bot;

/**
 * This is the model class for table "bot.settings".
 *
 * @property int $id
 * @property bool|null $lightning Молния
 * @property string|null $include_words Ключевые слова
 * @property string|null $exclude_words Исключаемые слова
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class BotSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bot.settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lightning'], 'boolean'],
            [['include_words', 'exclude_words'], 'string'],
            [['created_by', 'updated_by'], 'default', 'value' => null],
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lightning' => 'Молния',
            'include_words' => 'Ключевые слова (Через ; точку с запятой)',
            'exclude_words' => 'Исключаемые слова',
            'created_by' => 'Кто создал',
            'updated_by' => 'Кто изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }
}
