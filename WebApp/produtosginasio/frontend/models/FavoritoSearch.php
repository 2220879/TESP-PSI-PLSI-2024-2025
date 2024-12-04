<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Favorito;

/**
 * FavoritoSearch represents the model behind the search form of `frontend\models\Favorito`.
 */
class FavoritoSearch extends Favorito
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'produto_id', 'profile_id'], 'integer'],
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
        $query = Favorito::find();

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
            'produto_id' => $this->produto_id,
            'profile_id' => $this->profile_id,
        ]);

        return $dataProvider;
    }
}
