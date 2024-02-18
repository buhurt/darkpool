<?php

namespace app\models;

use app\behaviors\UTCTimestampBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "trade.expit_visible".
 *
 * @property int $id
 * @property int|null $ticker_id Тикер
 * @property bool|null $is_hide Скрыто
 * @property int|null $user_id Пользователь
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class TradeExpitVisible extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'trade.expit_visible';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ticker_id', 'user_id', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['ticker_id', 'user_id', 'created_by', 'updated_by'], 'integer'],
            [['is_hide'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticker_id'], 'exist', 'skipOnError' => true, 'targetClass' => MoexTicker::class, 'targetAttribute' => ['ticker_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'ticker_id' => 'Тикер',
            'is_hide' => 'Скрыто',
            'user_id' => 'Пользователь',
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
     * @param int|null $ticker_id
     */
    public function setTickerId(?int $ticker_id): void
    {
        $this->ticker_id = $ticker_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * Скрыть / показать
     * @param $ticker
     * @param bool $hide
     * @return bool
     * @throws \yii\db\StaleObjectException
     */
    public function setHide($ticker, bool $hide = true): bool
    {
        $tickerId = MoexTicker::getTickerModel($ticker);
        if ($tickerId !== null) {
            if ($hide) {
                $model = new self();
                $model->setTickerId($tickerId->getId());
                $model->setUserId(Yii::$app->user->id);
                return $model->validate() && $model->save();
            }
            $model = self::findOne(['ticker_id' => $tickerId->getId(), 'user_id' => Yii::$app->user->id]);
            return $model->delete();
        }
        return false;
    }


}
