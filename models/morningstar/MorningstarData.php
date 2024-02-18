<?php

namespace app\models\morningstar;

use app\behaviors\UTCTimestampBehavior;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "morningstar.data".
 *
 * @property int $id
 * @property int|null $source_id Источник
 * @property int|null $fund_id Фонд
 * @property string|null $name Название
 * @property float|null $current_shares Текущие акции
 * @property float|null $total_shares_held Общее количество принадлежащих акций
 * @property float|null $total_assets Общие активы
 * @property float|null $change_amount Сумма изменения
 * @property float|null $change_percentage Процент изменения
 * @property string|null $date Дата
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class MorningstarData extends \yii\db\ActiveRecord
{
    public $current_shares2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'morningstar.data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'fund_id', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['source_id', 'fund_id', 'created_by', 'updated_by'], 'integer'],
            [['current_shares', 'total_shares_held', 'total_assets', 'change_amount', 'change_percentage'], 'number'],
            [['date', 'created_at', 'updated_at', 'current_shares2'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['source_id', 'fund_id', 'current_shares', 'date'], 'unique', 'targetAttribute' => ['source_id', 'fund_id', 'current_shares', 'date']],
            [['fund_id'], 'exist', 'skipOnError' => true, 'targetClass' => MorningstarFund::class, 'targetAttribute' => ['fund_id' => 'id']],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => MorningstarSource::class, 'targetAttribute' => ['source_id' => 'id']],
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
            'source_id' => 'Источник',
            'fund_id' => 'Фонд',
            'name' => 'Название',
            'current_shares' => 'Текущие акции',
            'total_shares_held' => 'Общее количество принадлежащих акций',
            'total_assets' => 'Общие активы',
            'change_amount' => 'Сумма изменения',
            'change_percentage' => 'Процент изменения',
            'date' => 'Дата',
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
