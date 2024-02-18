<?php

namespace app\models\west\search;

use app\models\west\WestDarkpool;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WestDarkpoolSearch represents the model behind the search form of `app\models\west\WestDarkpool`.
 */
class WestDarkpoolSearch extends WestDarkpool
{
    public $countDay;
    public $percent;
    public $startDate;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'ticker_id',
                    'deals_volume',
                    'notional',
                    'security_type_id',
                    'deals_avg_30_day',
                    'float',
                    'created_by',
                    'updated_by',
                ],
                'integer',
            ],
            [
                [
                    'startDate',
                    'countDay',
                    'percent',
                    'trade_date',
                    'trade_time',
                    'ticker',
                    'message',
                    'type',
                    'earnings_date',
                    'created_at',
                    'updated_at',
                ],
                'safe',
            ],
            [
                [
                    'price',
                    'percent_avg_30_day',
                ],
                'number',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search(array $params): ActiveDataProvider
    {
        $query = WestDarkpool::getDataForChart($params);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'trade_date' => $this->trade_date,
            'trade_time' => $this->trade_time,
            'ticker_id' => $this->ticker_id,
            'deals_volume' => $this->deals_volume,
            'price' => $this->price,
            'notional' => $this->notional,
            'percent_avg_30_day' => $this->percent_avg_30_day,
            'security_type_id' => $this->security_type_id,
            'deals_avg_30_day' => $this->deals_avg_30_day,
            'float' => $this->float,
            'earnings_date' => $this->earnings_date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'ticker', $this->ticker])
            ->andFilterWhere(['ilike', 'message', $this->message])
            ->andFilterWhere(['ilike', 'type', $this->type]);
        $query->cache(86400);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchTrades($ticker)
    {
        $query = WestDarkpool::find()->alias('t');
        $query->andWhere(['ticker' => $ticker]);
        $query->andWhere('deals_volume != 0');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $dataProvider->setSort([
                'attributes' => [
                    'trade_date',
                    'trade_time',
                    'ticker',
                    'deals_volume',
                    'price',
                    'notional',
                    'percent_avg_30_day',
                    'deals_avg_30_day',
                ],
                'defaultOrder' => [
                    'trade_date' => SORT_DESC,
                    'trade_time' => SORT_DESC,
                ],
            ]
        );

        return $dataProvider;
    }
}
