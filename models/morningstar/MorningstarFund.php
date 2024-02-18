<?php

namespace app\models\morningstar;

use app\behaviors\UTCTimestampBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "morningstar.fund".
 *
 * @property int $id
 * @property string|null $name Название
 * @property string|null $sec_id ID star
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class MorningstarFund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'morningstar.fund';
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
            [['name', 'sec_id'], 'string', 'max' => 255],
            [['sec_id', 'name'], 'unique', 'targetAttribute' => ['sec_id', 'name']],
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
            'sec_id' => 'ID star',
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
