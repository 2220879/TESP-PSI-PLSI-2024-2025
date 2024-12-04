<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Produto;

/**
 * ProdutoSearch represents the model behind the search form of `common\models\Produto`.
 */
class ProdutoSearch extends Produto
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quantidade', 'marca_id', 'categoria_id', 'iva_id', 'genero_id'], 'integer'],
            [['nomeProduto', 'descricaoProduto'], 'safe'],
            [['preco'], 'number'],
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
        $query = Produto::find();

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
            'preco' => $this->preco,
            'quantidade' => $this->quantidade,
            'marca_id' => $this->marca_id,
            'categoria_id' => $this->categoria_id,
            'iva_id' => $this->iva_id,
            'genero_id' => $this->genero_id,
        ]);

        $query->andFilterWhere(['like', 'nomeProduto', $this->nomeProduto])
            ->andFilterWhere(['like', 'descricaoProduto', $this->descricaoProduto]);

        return $dataProvider;
    }
}
