<?php

namespace app\models\search;

use app\models\TradeExpit;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TradeExpitSearch represents the model behind the search form of `app\models\TradeExpit`.
 */
class TradeExpitSearch extends TradeExpit
{
    public $ticker_search;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ticker_id', 'deals_count', 'count_paper', 'created_by', 'updated_by'], 'integer'],
            [['trade_date', 'trade_day', 'ticker', 'emitent_name', 'reg_num', 'period_execution', 'big_deals', 'created_at', 'updated_at'], 'safe'],
            [['deals_volume', 'max_price', 'min_price', 'est_price'], 'number'],
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
     */
    public function search($params)
    {
        $query = TradeExpit::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'ticker_id' => $this->ticker_id,
            'deals_count' => $this->deals_count,
            'deals_volume' => $this->deals_volume,
            'count_paper' => $this->count_paper,
            'max_price' => $this->max_price,
            'min_price' => $this->min_price,
            'est_price' => $this->est_price,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'trade_day', $this->trade_day])
            ->andFilterWhere(['ilike', 'ticker', $this->ticker])
            ->andFilterWhere(['ilike', 'emitent_name', $this->emitent_name])
            ->andFilterWhere(['ilike', 'reg_num', $this->reg_num])
            ->andFilterWhere(['ilike', 'period_execution', $this->period_execution])
            ->andFilterWhere(['ilike', 'big_deals', $this->big_deals]);

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
        $query = TradeExpit::find()->alias('t');
        $query->andWhere(['ticker' => $ticker]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $dataProvider->setSort([
                'attributes' => [
                    'trade_date',
                    'trade_day',
                    'reg_num',
                    'period_execution',
                    'deals_count',
                    'deals_volume',
                    'count_paper',
                    'max_price',
                    'min_price',
                    'est_price',
                ],
                'defaultOrder' => [
                    'trade_date' => SORT_DESC,
                ],
            ]
        );

        return $dataProvider;
    }
}
