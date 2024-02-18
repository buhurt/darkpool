<?php

namespace app\models;

use app\behaviors\UTCTimestampBehavior;
use app\components\CustomLoadActiveRecord;
use app\modules\user\models\User;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "trade.expit".
 *
 * @property int $id
 * @property string|null $trade_date Дата отчета сделок
 * @property string|null $trade_day Торговый день
 * @property int|null $ticker_id Тикер
 * @property string|null $ticker Тикер
 * @property string|null $emitent_name Наименование эмитента, вид ц/б
 * @property string|null $reg_num Номер гос.регистрации
 * @property string|null $period_execution Срок исполнения, дней
 * @property int|null $deals_count Кол-во сделок
 * @property float|null $deals_volume Объем сделок, руб.
 * @property int|null $count_paper Кол-во ц/б
 * @property float|null $max_price Макс. цена
 * @property float|null $min_price Мин. цена
 * @property float|null $est_price Рассчетная цена
 * @property string $big_deals Крупные сделки
 * @property boolean $is_hide Скрыто на графике
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class TradeExpit extends CustomLoadActiveRecord
{
    public $big_deals;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trade.expit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trade_date', 'created_at', 'updated_at', 'big_deals'], 'safe'],
            [['ticker_id', 'deals_count', 'count_paper', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['ticker_id', 'deals_count', 'count_paper', 'created_by', 'updated_by'], 'integer'],
            [['deals_volume', 'max_price', 'min_price', 'est_price'], 'number'],
            [['is_hide'], 'boolean'],
            [['trade_day', 'ticker', 'emitent_name', 'reg_num', 'period_execution'], 'string', 'max' => 255],
            [['trade_date', 'trade_day', 'ticker_id', 'period_execution', 'deals_volume'], 'unique', 'targetAttribute' => ['trade_date', 'trade_day', 'ticker_id', 'period_execution', 'deals_volume']],
            [['ticker_id'], 'exist', 'skipOnError' => true, 'targetClass' => MoexTicker::class, 'targetAttribute' => ['ticker_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trade_date' => 'Дата отчета сделок',
            'trade_day' => 'Торговый день',
            'ticker_id' => 'Тикер',
            'ticker' => 'Тикер',
            'emitent_name' => 'Наименование эмитента, вид ц/б',
            'reg_num' => 'Номер гос.регистрации',
            'period_execution' => 'Срок исполнения, дней',
            'deals_count' => 'Кол-во сделок',
            'deals_volume' => 'Объем сделок, руб.',
            'count_paper' => 'Кол-во ц/б',
            'max_price' => 'Макс. цена',
            'min_price' => 'Мин. цена',
            'est_price' => 'Рассчетная цена',
            'created_by' => 'Кто создал',
            'updated_by' => 'Кто изменил',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
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

    public function getTradeDate(): ?string
    {
        return $this->trade_date;
    }

    public function setTradeDate(?string $trade_date): void
    {
        $this->trade_date = $trade_date;
    }

    public function getTradeDay(): ?string
    {
        return $this->trade_day;
    }

    public function setTradeDay(?string $trade_day): void
    {
        $this->trade_day = $trade_day;
    }

    public function getTickerId(): ?int
    {
        return $this->ticker_id;
    }

    public function setTickerId(?int $ticker_id): void
    {
        $this->ticker_id = $ticker_id;
    }

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(?string $ticker): void
    {
        $this->ticker = $ticker;
    }

    public function getEmitentName(): ?string
    {
        return $this->emitent_name;
    }

    public function setEmitentName(?string $emitent_name): void
    {
        $this->emitent_name = $emitent_name;
    }

    public function getRegNum(): ?string
    {
        return $this->reg_num;
    }

    public function setRegNum(?string $reg_num): void
    {
        $this->reg_num = $reg_num;
    }

    public function getPeriodExecution(): ?string
    {
        return $this->period_execution;
    }

    public function setPeriodExecution(?string $period_execution): void
    {
        $this->period_execution = $period_execution;
    }

    public function getDealsCount(): ?int
    {
        return $this->deals_count;
    }

    public function setDealsCount(?int $deals_count): void
    {
        $this->deals_count = $deals_count;
    }

    public function getDealsVolume(): ?float
    {
        return $this->deals_volume;
    }

    public function setDealsVolume(?float $deals_volume): void
    {
        $this->deals_volume = $deals_volume;
    }

    public function getCountPaper(): ?int
    {
        return $this->count_paper;
    }

    public function setCountPaper(?int $count_paper): void
    {
        $this->count_paper = $count_paper;
    }

    public function getMaxPrice(): ?float
    {
        return $this->max_price;
    }

    public function setMaxPrice(?float $max_price): void
    {
        $this->max_price = $max_price;
    }

    public function getMinPrice(): ?float
    {
        return $this->min_price;
    }

    public function setMinPrice(?float $min_price): void
    {
        $this->min_price = $min_price;
    }

    public function getEstPrice(): ?float
    {
        return $this->est_price;
    }

    public function setEstPrice(?float $est_price): void
    {
        $this->est_price = $est_price;
    }

    public function isIsHide(): bool
    {
        return $this->is_hide;
    }

    public function setIsHide(bool $isHide): void
    {
        $this->is_hide = $isHide;
    }

    /**
     * Получить последнюю дату, за которую были данные
     * @return array|ActiveRecord|null
     */
    public static function getLastActualDate()
    {
        return self::find()->addSelect(new Expression('max(trade_date)'))->column()[0];
    }

    /**
     * Скрыть / показать значение на графике
     * @param bool $hide
     */
    public function setVisible(bool $hide): void
    {
        $isHide = $hide == 1;
        $this->setIsHide($isHide);
        $this->save();
    }

    /**
     * Проверить, скрыт ли график
     * @return bool
     */
    public function isHide(): bool
    {
        return TradeExpitVisible::findOne(['ticker_id' => $this->getTickerId()]) !== null;
    }

    /**
     * Получить актуальные данные для графиков
     */
    public static function getDataForChart(string $page = 'new', bool $obligation = false): ActiveQuery
    {
        $query = MoexTicker::find()->alias('t');
        $query->addSelect(['t.id', 't.ticker']);
        $query->addSelect(new Expression('string_agg(actual."trade_date"::varchar, \',\' order by actual.trade_date)    trade_date,
            string_agg(actual.deals_volume::varchar, \',\' order by actual.trade_date) AS all_total,
            string_agg(actual.color::varchar, \'|\' order by actual.trade_date)           color,
            string_agg(actual.price::varchar, \'|\' order by actual.trade_date)           price'));
        if ($page === 'new') {
            $query->addSelect(new Expression('1 as actual'));
            $query->leftJoin(new Expression('(select ticker_id, trade_date, sum(deals_volume) deals_volume, json_agg(est_price) price,
                    case
                       when sum(deals_volume) > 1000000000 then \'rgba(255,119,0,0.4)\'
                    else
                       \'rgb(0,201,167)\'
                    end as color
                    from trade.expit t1
                    where (exists(select 1 from trade.expit t2 where t1.ticker_id = t2.ticker_id and trade_date = (select max(trade_date) from trade.expit)))
                      and ("is_hide" = FALSE)
                    group by trade_date, ticker_id
                    order by trade_date)') . ' as actual', 't.id = actual.ticker_id');
        } else if ($page === 'old') {
            $query->addSelect(new Expression('0 as actual'));
            $query->leftJoin(new Expression('(select ticker_id, trade_date, sum(deals_volume) deals_volume, json_agg(est_price) price,
                    case
                       when sum(deals_volume) > 1000000000 then \'rgba(255,119,0,0.4)\'
                    else
                       \'rgb(0,201,167)\'
                    end as color
                    from trade.expit t1
                    where (not exists(select 1 from trade.expit t2 where t1.ticker_id = t2.ticker_id and trade_date = (select max(trade_date) from trade.expit)))
                      and ("is_hide" = FALSE)
                    group by trade_date, ticker_id
                    order by trade_date)') . ' as actual', 't.id = actual.ticker_id');
        }
        $query->andWhere(['not', ['actual.trade_date' => null]])
            ->andWhere(new Expression('t.id not in (select ticker_id from trade.expit_visible)'));
        if (!$obligation) {
            $query->andWhere(new Expression('ticker not ilike \'RU0%\' 
            and ticker not ilike \'RU1%\' 
            and ticker not ilike \'XS%\'
            and ticker not ilike \'SU2%\' 
            and ticker not ilike \'SU4%\' and ticker is not null and ticker != \'\''));
        } else {
            $query->andWhere(new Expression('ticker ilike \'SU%\''));
        }
        $query->addGroupBy('t.id, t.ticker');
        $query->orderBy('t.ticker');

        return $query;
    }

    /**
     * Получить актуальные данные для графика
     */
    public static function getDataForOneChart(int $tickerId): ActiveQuery
    {
        $query = MoexTicker::find()->alias('t');
        $query->addSelect(['t.id', 't.ticker']);
        $query->addSelect(new Expression('string_agg(actual."trade_date"::varchar, \',\' order by actual.trade_date)    trade_date,
            string_agg(actual.deals_volume::varchar, \',\' order by actual.trade_date) AS all_total,
            string_agg(actual.color::varchar, \'|\' order by actual.trade_date)           color,
            string_agg(actual.price::varchar, \'|\' order by actual.trade_date)           price'));
        $query->leftJoin(new Expression('(select ticker_id, trade_date, sum(deals_volume) deals_volume, json_agg(est_price) price,
                    case
                       when sum(deals_volume) > 1000000000 then \'rgba(255,119,0,0.4)\'
                    else
                       \'rgb(0,201,167)\'
                    end as color
                    from trade.expit t1
                    where ("is_hide" = FALSE)
                    group by trade_date, ticker_id
                    order by trade_date)') . ' as actual', 't.id = actual.ticker_id');
        $query->andWhere(['not', ['actual.trade_date' => null]]);
        $query->andWhere(['t.id' => $tickerId]);
        $query->groupBy('t.id, t.ticker');
        $query->orderBy('t.ticker');
        return $query;
    }

}
