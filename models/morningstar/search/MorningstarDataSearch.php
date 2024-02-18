<?php

namespace app\models\morningstar\search;

use app\models\morningstar\MorningstarData;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MorningstarDataSearch represents the model behind the search form of `app\models\morningstar\MorningstarData`.
 */
class MorningstarDataSearch extends MorningstarData
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'source_id', 'fund_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'date', 'created_at', 'updated_at'], 'safe'],
            [['current_shares', 'total_shares_held', 'total_assets', 'change_amount', 'change_percentage'], 'number'],
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
        $query = MorningstarData::find();

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
            'source_id' => $this->source_id,
            'fund_id' => $this->fund_id,
            'current_shares' => $this->current_shares,
            'total_shares_held' => $this->total_shares_held,
            'total_assets' => $this->total_assets,
            'change_amount' => $this->change_amount,
            'change_percentage' => $this->change_percentage,
            'date' => $this->date,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}
