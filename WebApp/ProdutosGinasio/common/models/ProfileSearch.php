<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProfileSearch represents the model behind the search form of `common\models\Profile`.
 */
class ProfileSearch extends Profile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nif', 'telefone', 'user_id'], 'integer'],
            [['morada'], 'safe'],
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
        $query = Profile::find();

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
            'nif' => $this->nif,
            'telefone' => $this->telefone,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'morada', $this->morada]);

        return $dataProvider;
    }
}
