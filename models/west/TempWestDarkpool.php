<?php

namespace app\models\west;

use app\components\CustomLoadActiveRecord;

/**
 * This is the model class for table "temp.west_darkpool".
 *
 * @property int $id
 * @property string|null $date Дата сделки
 * @property string|null $timestamp Время сделки
 * @property string|null $ticker Тикер
 * @property int|null $volume Кол-во бумаг (Объем)
 * @property float|null $price Цена
 * @property float|null $percent_avg_30_day Процент средний за 30 дней
 * @property int|null $notional Сумма (условная)
 * @property string|null $message Сообщение
 * @property string|null $type Тип
 * @property string|null $security_type SecurityType
 * @property string|null $industry Индустрия
 * @property string|null $sector Сектор
 * @property int|null $deals_avg_30_day Кол-во среднее за 30 дней
 * @property int|null $float Float
 * @property string|null $earnings_date Дата получения дохода
 */
class TempWestDarkpool extends CustomLoadActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'temp.west_darkpool';
    }

    /**
     * @param $offset
     * @param $limit
     * @return array
     */
    public static function getDataForInsert($offset, $limit): array
    {
        return self::find()
            ->addSelect([
                'date',
                'timestamp',
                'ticker',
                'sum(volume) volume',
                'avg("price") price',
                'max("percent_avg_30_day") percent_avg_30_day',
                'sum(notional) notional',
                'type',
                'max("message") message',
                'max("security_type") security_type',
                'max("industry") industry',
                'max("sector") sector',
                'max("deals_avg_30_day") deals_avg_30_day',
                'max("float") deals_avg_30_day',
                'max("earnings_date") earnings_date',
                'case when max(west_darkpool.message) ilike \'%cancelled%\' then true else false end as is_cancelled',
            ])->groupBy('ticker, date, timestamp, type')
            ->orderBy('date')
            ->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['date', 'timestamp', 'earnings_date'], 'safe'],
            [['volume', 'notional', 'deals_avg_30_day', 'float'], 'default', 'value' => null],
            [['volume', 'notional', 'deals_avg_30_day', 'float'], 'integer'],
            [['price', 'percent_avg_30_day'], 'number'],
            [['ticker', 'message', 'type', 'security_type', 'industry', 'sector'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'timestamp' => 'Timestamp',
            'ticker' => 'Ticker',
            'volume' => 'Volume',
            'price' => 'Price',
            'percent_avg_30_day' => 'Percent Avg 30 Day',
            'notional' => 'Notional',
            'message' => 'Message',
            'type' => 'Type',
            'security_type' => 'Security Type',
            'industry' => 'Industry',
            'sector' => 'Sector',
            'deals_avg_30_day' => 'Deals Avg 30 Day',
            'float' => 'Float',
            'earnings_date' => 'Earnings Date',
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    public function setNotional(?int $notional): void
    {
        $this->notional = $notional;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setEarningsDate(?string $earnings_date): void
    {
        $this->earnings_date = $earnings_date;
    }


}
