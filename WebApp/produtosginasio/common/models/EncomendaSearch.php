<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Encomenda;

/**
 * EncomendaSearch represents the model behind the search form of `common\models\Encomenda`.
 */
class EncomendaSearch extends Encomenda
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'telefone', 'profile_id'], 'integer'],
            [['data', 'hora', 'morada', 'email', 'estadoEncomenda'], 'safe'],
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
        $query = Encomenda::find();

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
            'data' => $this->data,
            'hora' => $this->hora,
            'telefone' => $this->telefone,
            'profile_id' => $this->profile_id,
        ]);

        $query->andFilterWhere(['like', 'morada', $this->morada])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'estadoEncomenda', $this->estadoEncomenda]);

        return $dataProvider;
    }
}
