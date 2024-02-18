<?php

namespace app\models\west;

use app\behaviors\UTCTimestampBehavior;
use app\models\User;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "west.ticker".
 *
 * @property int $id
 * @property string|null $ticker Тикер
 * @property string|null $short_name Название тикера
 * @property int|null $industry_id Индустрия
 * @property int|null $sector_id Сектор
 * @property int|null $exchange_id Биржа
 * @property int|null $type_id Тип
 * @property string|null $type Тип
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class WestTicker extends ActiveRecord
{
    public $trade_date;
    public $all_volume;
    public $all_notional;
    public $price;
    public $actual;
    public $result;
    public $avg_deals;
    public $last_day;
    public $is_favorites;
    public $color;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'west.ticker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['industry_id', 'sector_id', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['industry_id', 'sector_id', 'type_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticker', 'short_name', 'type'], 'string', 'max' => 255],
            [['ticker'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [['industry_id'], 'exist', 'skipOnError' => true, 'targetClass' => WestIndustry::class, 'targetAttribute' => ['industry_id' => 'id']],
            [['sector_id'], 'exist', 'skipOnError' => true, 'targetClass' => WestSector::class, 'targetAttribute' => ['sector_id' => 'id']],
            [['exchange_id'], 'exist', 'skipOnError' => true, 'targetClass' => WestExchange::class, 'targetAttribute' => ['exchange_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'ticker' => 'Тикер',
            'short_name' => 'Название тикера',
            'industry_id' => 'Индустрия',
            'sector_id' => 'Сектор',
            'exchange_id' => 'Биржа',
            'type_id' => 'Тип',
            'type' => 'Тип',
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    /**
     * @return int|null
     */
    public function getIndustryId(): ?int
    {
        return $this->industry_id;
    }

    /**
     * @return int|null
     */
    public function getSectorId(): ?int
    {
        return $this->sector_id;
    }

    /**
     * @return int|null
     */
    public function getExchangeId(): ?int
    {
        return $this->exchange_id;
    }

    /**
     * @return int|null
     */
    public function getTypeId(): ?int
    {
        return $this->type_id;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    /**
     * @return int|null
     */
    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * @return mixed
     */
    public function getTradeDate()
    {
        return $this->trade_date;
    }

    /**
     * @return mixed
     */
    public function getAllVolume()
    {
        return $this->all_volume;
    }

    /**
     * @return mixed
     */
    public function getAllNotional()
    {
        return $this->all_notional;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getAvgDeals()
    {
        return $this->avg_deals;
    }

    /**
     * @return mixed
     */
    public function getLastDay()
    {
        return $this->last_day;
    }

    /**
     * @return mixed
     */
    public function getIsFavorites()
    {
        return $this->is_favorites;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Получить тикер по названию, если нет, то создать
     * @param $ticker
     * @param null $industry
     * @param null $sector
     * @param null $exchange
     * @param null $type
     * @return int
     */
    public static function getTickerIdIfExist($ticker, $industry = null, $sector = null, $exchange = null, $type = null): int
    {
        $model = self::findOne(['ticker' => $ticker]);
        if ($model === null) {
            $model = self::create($ticker, $industry, $sector, $exchange, $type);
        }
        return $model->id;
    }

    /**
     * Добавить тикер
     * @param $ticker
     * @param null $industry
     * @param null $sector
     * @param null $exchange
     * @param null $type
     * @return WestTicker
     */
    public static function create($ticker, $industry = null, $sector = null, $exchange = null, $type = null): WestTicker
    {
        $model = new self();
        $model->ticker = $ticker;
        $model->industry_id = $industry;
        $model->sector_id = $sector;
        $model->exchange_id = $exchange;
        $model->type = $type;
        $model->save();
        return $model;
    }

    /**
     * Добавить тикер
     * @param $ticker
     * @param $name
     * @param $exchange
     * @param $type
     * @return WestTicker
     */
    public static function createOrUpdate($ticker, $name, $exchange, $type): WestTicker
    {
        $model = self::findOne(['ticker' => $ticker]);
        if ($model === null) {
            $model = new self();
        }
        $model->ticker = $ticker;
        $model->short_name = $name;
        $model->exchange_id = $exchange;
        $model->type = $type;
        $model->save();
        return $model;
    }

    /**
     * Получить актуальный список тикеров
     * @param null $ticker
     * @return array|ActiveRecord[]
     */
    public function getTickerList($ticker = null): array
    {
        $query = self::find()
            ->addSelect(['id', 'ticker']);
        if (!empty($ticker)) {
            $query->andWhere(['ticker' => $ticker]);
        }
        $query->orderBy('ticker');
        return $query->all();
    }
}
