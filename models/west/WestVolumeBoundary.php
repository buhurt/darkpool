<?php

namespace app\models\west;

use app\behaviors\UTCTimestampBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "west.volume_boundary".
 *
 * @property int $id
 * @property int|null $ticker_id Тикер
 * @property string|null $ticker Тикер
 * @property int|null $value Значение
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class WestVolumeBoundary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'west.volume_boundary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticker_id', 'value', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['ticker_id', 'value', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticker'], 'string', 'max' => 255],
            [['ticker_id', 'created_by'], 'unique', 'targetAttribute' => ['ticker_id', 'created_by']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['ticker_id'], 'exist', 'skipOnError' => true, 'targetClass' => WestTicker::class, 'targetAttribute' => ['ticker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticker_id' => 'Тикер',
            'ticker' => 'Тикер',
            'value' => 'Значение',
            'created_by' => 'Кто создал',
            'updated_by' => 'Кто изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            UTCTimestampBehavior::class,
            BlameableBehavior::class,
        ]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}
