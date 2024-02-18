<?php

namespace app\models\west;

use app\behaviors\UTCTimestampBehavior;
use app\modules\user\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "west.favorites".
 *
 * @property int $id
 * @property int|null $ticker_id Тикер
 * @property string|null $ticker Тикер
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class WestFavorites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'west.favorites';
    }

    /**
     * ДОбавить запись в избранное
     * @param $tickerId
     * @return bool
     */
    public static function add($tickerId): bool
    {
        $model = new self;
        $model->ticker_id = $tickerId;
        if ($model->save()) {
            Yii::$app->cache->flush();
            return true;
        }
        return false;
    }

    /**
     * Удалить запись из избранного
     * @param $tickerId
     * @return bool
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteFavorites($tickerId): bool
    {
        $model = WestFavorites::findOne(['ticker_id' => $tickerId, 'created_by' => Yii::$app->user->id]);
        if ($model->delete()) {
            Yii::$app->cache->flush();
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticker_id', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['ticker_id', 'created_by', 'updated_by'], 'integer'],
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

    /**
     * @return ActiveQuery
     */
    public function getTickerInfo()
    {
        return $this->hasOne(WestTicker::class, ['id' => 'ticker_id']);
    }
}
