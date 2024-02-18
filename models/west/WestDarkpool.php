<?php

namespace app\models\west;

use app\behaviors\UTCTimestampBehavior;
use app\components\CustomLoadActiveRecord;
use app\models\User;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "west.darkpool".
 *
 * @property int $id
 * @property string|null $trade_date Дата сделки
 * @property string|null $trade_time Время сделки
 * @property int|null $ticker_id Тикер
 * @property string|null $ticker Тикер
 * @property int|null $deals_volume Кол-во бумаг (Объем)
 * @property float|null $price Цена
 * @property int|null $notional Сумма (условная)
 * @property float|null $percent_avg_30_day Процент средний за 30 дней
 * @property string|null $message Сообщение
 * @property string|null $type Тип
 * @property int|null $security_type_id SecurityType
 * @property int|null $deals_avg_30_day Кол-во среднее за 30 дней
 * @property int|null $float Float
 * @property string|null $earnings_date Дата получения дохода
 * @property boolean $is_cancelled Отмененная сделка
 * @property boolean $is_anomaly Аномальный объем
 * @property boolean $is_hide Аномальный объем
 * @property int|null $created_by Кто создал
 * @property int|null $updated_by Кто изменил
 * @property string|null $created_at Дата создания
 * @property string|null $updated_at Дата изменения
 */
class WestDarkpool extends CustomLoadActiveRecord
{
    public $all_total;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'west.darkpool';
    }

    /**
     * @return bool
     */
    public function isHide(): bool
    {
        return $this->is_hide;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trade_date', 'trade_time', 'earnings_date', 'created_at', 'updated_at'], 'safe'],
            [
                ['ticker_id', 'deals_volume', 'notional', 'security_type_id', 'deals_avg_30_day', 'float', 'created_by', 'updated_by'],
                'default',
                'value' => null
            ],
            [['ticker_id', 'deals_volume', 'notional', 'security_type_id', 'deals_avg_30_day', 'float', 'created_by', 'updated_by'], 'integer'],
            [['price', 'percent_avg_30_day'], 'number'],
            [['is_cancelled', 'is_anomaly', 'is_hide'], 'boolean'],
            [['ticker', 'message', 'type'], 'string', 'max' => 255],
            [['trade_date', 'trade_time', 'ticker_id'], 'unique', 'targetAttribute' => ['trade_date', 'trade_time', 'ticker_id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
            [
                ['security_type_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => WestSecurityType::class,
                'targetAttribute' => ['security_type_id' => 'id']
            ],
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
            'trade_date' => 'Дата сделки',
            'trade_time' => 'Время сделки',
            'ticker_id' => 'Тикер',
            'ticker' => 'Тикер',
            'deals_volume' => 'Кол-во бумаг (Объем)',
            'price' => 'Цена',
            'notional' => 'Сумма (условная)',
            'percent_avg_30_day' => 'Процент средний за 30 дней',
            'message' => 'Сообщение',
            'type' => 'Тип',
            'security_type_id' => 'SecurityType',
            'deals_avg_30_day' => 'Кол-во среднее за 30 дней',
            'float' => 'Float',
            'is_cancelled' => 'Отмененная сделка',
            'is_anomaly' => 'Аномальный объем',
            'is_hide' => 'Скрыть значение',
            'earnings_date' => 'Дата получения дохода',
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
    public function getTradeDate(): ?string
    {
        return $this->trade_date;
    }

    /**
     * @param string|null $trade_date
     */
    public function setTradeDate(?string $trade_date): void
    {
        $this->trade_date = $trade_date;
    }

    /**
     * @return string|null
     */
    public function getTradeTime(): ?string
    {
        return $this->trade_time;
    }

    /**
     * @param string|null $trade_time
     */
    public function setTradeTime(?string $trade_time): void
    {
        $this->trade_time = $trade_time;
    }

    /**
     * @return int|null
     */
    public function getTickerId(): ?int
    {
        return $this->ticker_id;
    }

    /**
     * @param int|null $ticker_id
     */
    public function setTickerId(?int $ticker_id): void
    {
        $this->ticker_id = $ticker_id;
    }

    /**
     * @return string|null
     */
    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    /**
     * @param string|null $ticker
     */
    public function setTicker(?string $ticker): void
    {
        $this->ticker = $ticker;
    }

    /**
     * @return int|null
     */
    public function getDealsVolume(): ?int
    {
        return $this->deals_volume;
    }

    /**
     * @param int|null $deals_volume
     */
    public function setDealsVolume(?int $deals_volume): void
    {
        $this->deals_volume = $deals_volume;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getNotional(): ?int
    {
        return $this->notional;
    }

    /**
     * @param int|null $notional
     */
    public function setNotional(?int $notional): void
    {
        $this->notional = $notional;
    }

    /**
     * @return float|null
     */
    public function getPercentAvg30Day(): ?float
    {
        return $this->percent_avg_30_day;
    }

    /**
     * @param float|null $percent_avg_30_day
     */
    public function setPercentAvg30Day(?float $percent_avg_30_day): void
    {
        $this->percent_avg_30_day = $percent_avg_30_day;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int|null
     */
    public function getSecurityTypeId(): ?int
    {
        return $this->security_type_id;
    }

    /**
     * @param int|null $security_type_id
     */
    public function setSecurityTypeId(?int $security_type_id): void
    {
        $this->security_type_id = $security_type_id;
    }

    /**
     * @return int|null
     */
    public function getDealsAvg30Day(): ?int
    {
        return $this->deals_avg_30_day;
    }

    /**
     * @param int|null $deals_avg_30_day
     */
    public function setDealsAvg30Day(?int $deals_avg_30_day): void
    {
        $this->deals_avg_30_day = $deals_avg_30_day;
    }

    /**
     * @return int|null
     */
    public function getFloat(): ?int
    {
        return $this->float;
    }

    /**
     * @param int|null $float
     */
    public function setFloat(?int $float): void
    {
        $this->float = $float;
    }

    /**
     * @return string|null
     */
    public function getEarningsDate(): ?string
    {
        return $this->earnings_date;
    }

    /**
     * @param string|null $earnings_date
     */
    public function setEarningsDate(?string $earnings_date): void
    {
        $this->earnings_date = $earnings_date;
    }

    /**
     * @return bool
     */
    public function isIsCancelled(): bool
    {
        return $this->is_cancelled;
    }

    /**
     * @param bool $is_cancelled
     */
    public function setIsCancelled(bool $is_cancelled): void
    {
        $this->is_cancelled = $is_cancelled;
    }

    /**
     * @param bool $is_hide
     */
    public function setIsHide(bool $is_hide): void
    {
        $this->is_hide = $is_hide;
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
     * Получить актуальные данные для графиков
     * @throws InvalidConfigException
     */
    public static function getDataForChart($params = null): ActiveQuery
    {
        if (isset($params['page']) || isset($params['WestDarkpoolSearch']['page'])) {
            $page = $params['page'] ?? $params['WestDarkpoolSearch']['page'];
            $days = $params['WestDarkpoolSearch']['countDay'] ?? 30;
            $percent = $params['WestDarkpoolSearch']['percent'] ?? 100;
            $startDate = $params['WestDarkpoolSearch']['startDate'] ?? date('Y-m-d');
            $startDate = \Yii::$app->formatter->asDate($startDate, 'php:Y-m-d');
        } else {
            $page = 'new';
        }
        $query = WestTicker::find()->alias('t');
        $query->addSelect(['t.id', 't.ticker', 't.short_name', 't.type']);
        $query->addSelect(
            new Expression(
                'string_agg(d.trade_date::varchar, \',\' order by d.trade_date) trade_date,
            string_agg(d.all_volume::varchar, \',\' order by d.trade_date)  all_volume,
            string_agg(d.all_notional::varchar, \',\' order by d.trade_date)  all_notional,
            string_agg(d.price::varchar, \'|\' order by d.trade_date)      price,
            string_agg(d.color::varchar, \'|\' order by d.trade_date)           color,
            exists(select 1 from west.darkpool t2 where t.id = t2.ticker_id and trade_date = (select max(trade_date) from west.darkpool)) actual,
            exists(select id from west.favorites f where f.ticker_id = t.id and f.created_by = ' . \Yii::$app->user->id . ') is_favorites'
            )
        );
        $query->andWhere(['not', ['d.trade_date' => null]]);
        $query->addGroupBy('t.id, t.ticker');
        if ($page === 'new' || $page === 'old' || $page === 'favorites') {
            $query->leftJoin(
                new Expression(
                    '(select t1.ticker_id, t1.trade_date, sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) all_volume, sum(t1.notional) - coalesce(cn.notional, 0) all_notional, json_agg(price) price,
                    case
                               when sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) > coalesce(max(vb2.value), null) then \'rgba(255,99,97,0.6)\'
                               else
                                   \'rgb(0,201,167)\'
                               end as          color
                    from west.darkpool t1
                        LEFT JOIN west.volume_boundary vb2 on t1.ticker_id = vb2.ticker_id and vb2.created_by = ' . \Yii::$app->user->id . '
                        left join (select ticker_id, trade_date, sum(deals_volume) deals_volume, sum(notional) notional
                                    from west.darkpool
                                    where is_cancelled = true
                                    group by ticker_id, trade_date) cn on cn.ticker_id = t1.ticker_id and cn.trade_date = t1.trade_date
                    and t1.is_cancelled = false
                    group by t1.trade_date, t1.ticker_id, cn.deals_volume, cn.notional
                    having sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) > 0
                    order by t1.trade_date)'
                ) . ' as d',
                't.id = d.ticker_id'
            );
            $query->orderBy('t.ticker');
            if ($page === 'new') {
                $query->andWhere(
                    new Expression(
                        'exists(select 1 from west.darkpool t2 where t.id = t2.ticker_id and trade_date = (select max(trade_date) from west.darkpool)) = true'
                    )
                );
            }
            if ($page === 'old') {
                $query->andWhere(
                    new Expression(
                        'exists(select 1 from west.darkpool t2 where t.id = t2.ticker_id and trade_date = (select max(trade_date) from west.darkpool)) = false'
                    )
                );
            }
            if ($page === 'favorites') {
                $query->andWhere(
                    new Expression(
                        'exists(select id from west.favorites f where f.ticker_id = t.id and f.created_by = ' . \Yii::$app->user->id . ') = true'
                    )
                );
            }
        } else {
            if ($page === 'high') {
                $query->addSelect(new Expression("(max(q.deals_volume) / avg(d.all_volume)) * 100 - 100 as result"));
                $query->addSelect(new Expression('avg(d.all_volume) avg_deals'));
                $query->addSelect(new Expression('q.deals_volume last_day'));
                $query->addSelect(new Expression('1 as actual'));
                $query->addSelect(new Expression('string_agg(d.color::varchar, \'|\' order by d.trade_date)           color'));
                $query->leftJoin(
                    new Expression(
                        "(select t2.ticker_id, sum(t2.deals_volume) - coalesce(cn.deals_volume, 0) deals_volume, 
            case
                               when sum(t2.deals_volume) - coalesce(cn.deals_volume, 0) > coalesce(max(vb2.value), null) then 'rgba(255,99,97,0.6)'
                               else
                                   'rgba(rgb(0,201,167))'
                               end as          color
                   from west.darkpool t2
                        LEFT JOIN west.volume_boundary vb2 on t2.ticker_id = vb2.ticker_id and vb2.created_by = " . \Yii::$app->user->id . "
                        left join (select ticker_id, trade_date, sum(deals_volume) deals_volume, sum(notional) notional
                                        from west.darkpool
                                        where is_cancelled = true
                                        group by ticker_id, trade_date) cn
                                       on cn.ticker_id = t2.ticker_id and cn.trade_date = t2.trade_date
                   where t2.trade_date = '$startDate'
                   group by t2.trade_date, t2.ticker_id, cn.deals_volume, cn.notional
                   order by t2.trade_date)"
                    ) . ' as q',
                    'q.ticker_id = t.id'
                );
                $query->leftJoin(
                    new Expression(
                        "(select t1.ticker_id, t1.trade_date, sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) all_volume, sum(t1.notional) - coalesce(cn.notional, 0) all_notional, json_agg(price) price,
            case
                               when sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) > coalesce(max(vb2.value), null) then 'rgba(255,99,97,0.6)'
                               else
                                   'rgb(0,201,167)'
                               end as          color
                    from west.darkpool t1
                            LEFT JOIN west.volume_boundary vb2 on t1.ticker_id = vb2.ticker_id and vb2.created_by = " . \Yii::$app->user->id . "
                            left join (select ticker_id, trade_date, sum(deals_volume) deals_volume, sum(notional) notional
                                        from west.darkpool
                                        where is_cancelled = true
                                        group by ticker_id, trade_date) cn
                                       on cn.ticker_id = t1.ticker_id and cn.trade_date = t1.trade_date
                    where (exists(select 1 from west.darkpool t2 where t1.ticker_id = t2.ticker_id and trade_date = '$startDate'))
                    and t1.trade_date >= date('$startDate'::timestamp AT TIME ZONE 'UTC' - INTERVAL '$days DAY') 
                    and t1.trade_date <= date('$startDate'::timestamp AT TIME ZONE 'UTC')
                    and t1.deals_volume != 0
                    and t1.is_cancelled = false
                    group by t1.trade_date, t1.ticker_id, cn.deals_volume, cn.notional
                    having sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) > 0
                    order by t1.trade_date)"
                    ) . ' as d',
                    't.id = d.ticker_id'
                );
                $query->orderBy('result desc');
                $query->addGroupBy('q.deals_volume');
                return (new ActiveQuery(WestTicker::class))->from(['res' => $query])->where("res.result >= $percent");
            }
        }

        return $query;
    }

    /**
     * Получить актуальные данные для графика
     */
    public static function getDataForOneChart(int $tickerId): ActiveQuery
    {
        $query = WestTicker::find()->alias('t');
        $query->addSelect(['t.id', 't.ticker', 't.short_name', 't.type']);
        $query->addSelect(
            new Expression(
                'string_agg(d.trade_date::varchar, \',\' order by d.trade_date) trade_date,
            string_agg(d.all_volume::varchar, \',\' order by d.trade_date)  all_volume,
            string_agg(d.all_notional::varchar, \',\' order by d.trade_date)  all_notional,
            string_agg(d.price::varchar, \'|\' order by d.trade_date)      price,
            string_agg(d.color::varchar, \'|\' order by d.trade_date)           color,
            exists(select id from west.favorites f where f.ticker_id = t.id and f.created_by = ' . \Yii::$app->user->id . ') is_favorites'
            )
        );
        $query->leftJoin(
            new Expression(
                '(select t1.ticker_id, t1.trade_date, sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) all_volume, sum(t1.notional) - coalesce(cn.notional, 0) all_notional, json_agg(price) price,
                    case
                               when sum(t1.deals_volume) - coalesce(cn.deals_volume, 0) > coalesce(max(vb2.value), null) then \'rgba(237,76,120)\'
                               else
                                   \'rgb(0,201,167)\'
                               end as          color
                    from west.darkpool t1
                        LEFT JOIN west.volume_boundary vb2 on t1.ticker_id = vb2.ticker_id and vb2.created_by = ' . \Yii::$app->user->id . '
                        left join (select ticker_id, trade_date, sum(deals_volume) deals_volume, sum(notional) notional
                                        from west.darkpool
                                        where is_cancelled = true
                                        group by ticker_id, trade_date) cn
                                       on cn.ticker_id = t1.ticker_id and cn.trade_date = t1.trade_date
                    where t1.is_cancelled = false and t1.is_hide = FALSE
                    group by t1.trade_date, t1.ticker_id, cn.deals_volume, cn.notional
                    order by t1.trade_date)'
            ) . ' as d',
            't.id = d.ticker_id'
        );
        $query->andWhere(['not', ['d.trade_date' => null]]);
        $query->andWhere(['t.id' => $tickerId]);
        $query->groupBy('t.id, t.ticker');
        $query->orderBy('t.ticker');
        return $query;
    }

    /**
     * Скрыть / показать значение на графике
     * @param bool $hide
     */
    public function setVisible(bool $hide): void
    {
        $this->setIsHide($hide == 1);
        $this->save();
    }
}
