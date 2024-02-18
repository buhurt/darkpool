<?php

namespace app\models\morningstar;

use app\behaviors\UTCTimestampBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "morningstar.source".
 *
 * @property int $id
 * @property string|null $name Название
 * @property string|null $url Адрес API URL
 * @property string|null $apikey Ключ авторизации
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class MorningstarSource extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'morningstar.source';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by', 'updated_by'], 'default', 'value' => null],
            [['created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'url', 'apikey'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'url' => 'Адрес API URL',
            'apikey' => 'Ключ авторизации',
            'created_by' => 'Кто создал',
            'updated_by' => 'Кто изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    public function behaviors()
    {
        return [
            UTCTimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }
}
