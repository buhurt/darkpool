<?php

namespace app\models;

use app\behaviors\UTCTimestampBehavior;
use models\dto\IndexDto;
use Yii;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "moex.index".
 *
 * @property int $id
 * @property string|null $moex_id Индекс ID
 * @property string|null $short_name Название
 * @property string|null $from Дата листинга
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class MoexIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moex.index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'default', 'value' => null],
            [['created_by', 'updated_by'], 'integer'],
            [['moex_id', 'short_name'], 'string', 'max' => 255],
            [['moex_id'], 'unique'],
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
            'moex_id' => 'Индекс ID',
            'short_name' => 'Название',
            'from' => 'Дата листинга',
            'created_by' => 'Кто создал',
            'updated_by' => 'Кто изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    public function behaviors(): array
    {
        return [
            UTCTimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    public function create(IndexDto $indexDto): bool
    {
        $this->moex_id = $indexDto->getMoexId();
        $this->short_name = $indexDto->getShortName();
        $this->from = $indexDto->getDateFrom();
        return $this->validate() && $this->save();
    }

    public function getAllData(): array
    {
        return self::find()->all();
    }
}
