<?php

namespace app\models\west;

use app\behaviors\UTCTimestampBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "west.exchange".
 *
 * @property int $id
 * @property string|null $name Название
 * @property string|null $name_short Короткое название
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class WestExchange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'west.exchange';
    }

    /**
     * @param $name
     * @param $nameShort
     * @return int
     */
    public static function getExchangeIdIfExist($name, $nameShort)
    {
        $model = self::findOne(['name' => $name]);
        if (empty($model)) {
            if (!empty($name)) {
                $model = self::create($name, $nameShort);
            } else {
                return null;
            }
        }
        return $model->id;
    }

    /**
     * @param $name
     * @param $nameShort
     * @return WestExchange
     */
    public static function create($name, $nameShort): WestExchange
    {
        $model = new self();
        $model->name = $name;
        $model->name_short = $nameShort;
        $model->save();
        return $model;
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
            [['name', 'name_short'], 'string', 'max' => 255],
            [['name_short'], 'unique'],
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
            'name' => 'Name',
            'name_short' => 'Name Short',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
