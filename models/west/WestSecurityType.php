<?php

namespace app\models\west;

use app\behaviors\UTCTimestampBehavior;
use app\models\User;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "west.security_type".
 *
 * @property int $id
 * @property string|null $name Название
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class WestSecurityType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'west.security_type';
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
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
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

    /**
     * @param $name
     * @return int
     */
    public static function getTypeIdIfExist($name): int
    {
        $model = self::findOne(['name' => $name]);
        if (empty($model)) {
            $model = self::create($name);
        }
        return $model->id;
    }

    /**
     * @param $name
     * @return WestSecurityType
     */
    public static function create($name): WestSecurityType
    {
        $model = new self();
        $model->name = $name;
        $model->save();
        return $model;
    }
}
