<?php

namespace app\models;

use app\behaviors\UTCTimestampBehavior;
use models\dto\TickerDto;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "moex.ticker".
 *
 * @property int $id
 * @property string|null $indexes ID индекса
 * @property string|null $ticker Тикер
 * @property string|null $short_name Название тикера
 * @property string|null $sec_name Название тикера
 * @property string|null $from Дата листинга
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class MoexTicker extends ActiveRecord
{
    public $actual;
    public $price;
    public $trade_date;
    public $all_total;
    public $color;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'moex.ticker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['indexes', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['created_by', 'updated_by'], 'integer'],
            [['from', 'created_at', 'updated_at', 'indexes'], 'safe'],
            [['ticker', 'short_name', 'sec_name'], 'string', 'max' => 255],
            [['ticker'], 'unique', 'targetAttribute' => ['ticker']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'indexes' => 'ID индекса',
            'ticker' => 'Тикер',
            'short_name' => 'Название тикера',
            'sec_name' => 'Название тикера',
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIndexes(): ?string
    {
        return $this->indexes;
    }

    public function setIndexes(?string $indexes): void
    {
        $this->indexes = $indexes;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(?string $ticker): void
    {
        $this->ticker = $ticker;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function setShortName(?string $short_name): void
    {
        $this->short_name = $short_name;
    }

    public function getSecName(): ?string
    {
        return $this->sec_name;
    }

    public function setSecName(?string $sec_name): void
    {
        $this->sec_name = $sec_name;
    }

    public function getFrom(): ?string
    {
        return $this->from;
    }

    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setCreatedBy(?int $created_by): void
    {
        $this->created_by = $created_by;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    public function setUpdatedBy(?int $updated_by): void
    {
        $this->updated_by = $updated_by;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * @param mixed $actual
     */
    public function setActual($actual): void
    {
        $this->actual = $actual;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getTradeDate()
    {
        return $this->trade_date;
    }

    /**
     * @param mixed $trade_date
     */
    public function setTradeDate($trade_date): void
    {
        $this->trade_date = $trade_date;
    }

    /**
     * @return mixed
     */
    public function getAllTotal()
    {
        return $this->all_total;
    }

    /**
     * @param mixed $all_total
     */
    public function setAllTotal($all_total): void
    {
        $this->all_total = $all_total;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
    }

    /**
     * Добавить новый тикер
     * @param MoexIndex $index
     * @param TickerDto $ticker
     * @return bool
     */
    public function create(MoexIndex $index, TickerDto $ticker): bool
    {
        $this->setIndexes($index->id);
        $this->setTicker($ticker->getName());
        $this->setFrom($ticker->getDateFrom());
        return $this->validate() && $this->save();
    }

    /**
     * Получить актуальный список тикеров
     */
    public function getTickerList(?string $ticker = null, bool $withObligation = false): array
    {
        $query = self::find()
            ->addSelect(['id', 'ticker'])
            ->andWhere(new Expression('id not in (select ticker_id from trade.expit_visible)'))
            ->andWhere(
                new Expression('ticker not ilike \'RU0%\' and ticker not ilike \'RU1%\' and ticker not ilike \'XS%\'')
            );
        if (!empty($ticker)) {
            $query->andWhere(['ticker' => $ticker]);
        }
        if (!$withObligation) {
            $query->andWhere(new Expression('ticker not ilike \'SU2%\' and ticker not ilike \'SU4%\''));
        }
        $query->orderBy('ticker');
        return $query->all();
    }

    /**
     * Получить актуальный список тикеров
     * @return array|ActiveRecord[]
     */
    public function getObligation(?string $ticker = null): array
    {
        $query = self::find()
            ->addSelect(['id', 'ticker'])
            ->andWhere(new Expression('id not in (select ticker_id from trade.expit_visible)'))
            ->andWhere(new Expression('ticker ilike \'SU%\''));
        if (!empty($ticker)) {
            $query->andWhere(['ticker' => $ticker]);
        }
        $query->orderBy('ticker');
        return $query->all();
    }

    /**
     * Получить модель тикера
     */
    public static function getTickerModel(string $ticker): ?MoexTicker
    {
        return self::findOne(['ticker' => $ticker]);
    }
}
