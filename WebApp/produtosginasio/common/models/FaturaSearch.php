<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Fatura;

/**
 * FaturaSearch represents the model behind the search form of `common\models\Fatura`.
 */
class FaturaSearch extends Fatura
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nif', 'metodopagamento_id', 'metodoentrega_id', 'encomenda_id', 'profile_id'], 'integer'],
            [['dataEmissao', 'horaEmissao'], 'safe'],
            [['valorTotal', 'ivaTotal'], 'number'],
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
        $query = Fatura::find();

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
            'dataEmissao' => $this->dataEmissao,
            'horaEmissao' => $this->horaEmissao,
            'valorTotal' => $this->valorTotal,
            'ivaTotal' => $this->ivaTotal,
            'nif' => $this->nif,
            'metodopagamento_id' => $this->metodopagamento_id,
            'metodoentrega_id' => $this->metodoentrega_id,
            'encomenda_id' => $this->encomenda_id,
            'profile_id' => $this->profile_id,
        ]);

        return $dataProvider;
    }
}
