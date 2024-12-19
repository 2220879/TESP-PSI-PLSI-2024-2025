<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use yii\rest\ActiveController;

class ProdutoController extends ActiveController
{
    public $modelClass = 'common\models\Produto';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];

        return $behaviors;
    }

    public function actionCount()
    {
        $produtosmodel = new $this->modelClass;
        $recs = $produtosmodel::find()->all();
        return ['count' => count($recs)];

    }

    public function actionProdutos()
    {
        $produtosmodel = new $this->modelClass;
        $produtos = $produtosmodel::find()->all();
        return ['produtos' => $produtos];

    }

    public function actionBuscarpornome($nomeProduto)
    {
        $produtosmodel = new $this->modelClass;
        $produtos = $produtosmodel::find()->where(['nomeProduto' => $nomeProduto])->all();
        return $produtos;
    }

    public function actionBuscarportamanho($tamanho_id)
    {
        $produtosmodel = new $this->modelClass;

        // Consulta com join para a tabela intermediária
        $produtos = $produtosmodel::find()->innerJoin('produtos_has_tamanhos', 'produtos_has_tamanhos.produto_id = produtos.id')
            ->where(['produtos_has_tamanhos.tamanho_id' => $tamanho_id])
            ->all();

        if (empty($produtos)) {
            return [
                'status' => 'error',
                'message' => 'Nenhum produto encontrado com o ID de tamanho especificado.'
            ];
        }

        return [
            'status' => 'success',
            'data' => $produtos
        ];
    }

    public function actionBuscarpormarca($marca_id)
    {
        $produtosmodel = new $this->modelClass;
        $produtos = $produtosmodel::find()->where(['marca_id' => $marca_id])->all();

        if (empty($produtos)) {
            return ['message' => 'Nenhum produto encontrado com o ID de marca especificado.'];
        }

        return $produtos;
    }

    public function actionBuscarporcategoria($categoria_id)
    {
        $produtosmodel = new $this->modelClass;
        $produtos = $produtosmodel::find()->where(['categoria_id' => $categoria_id])->all();

        if (empty($produtos)) {
            return ['message' => 'Nenhum produto encontrado com o ID de categoria especificado.'];
        }

        return $produtos;
    }

    public function actionBuscarporgenero($genero_id)
    {
        $produtosmodel = new $this->modelClass;
        $produtos = $produtosmodel::find()->where(['genero_id' => $genero_id])->all();

        if (empty($produtos)) {
            return ['message' => 'Nenhum produto encontrado com o ID de genero especificado.'];
        }

        return $produtos;
    }

    public function actionImagens($produto_id)
    {
        $produtomodel = new $this->modelClass;

        $produtos = $produtomodel::findOne($produto_id);

        if (!$produtos) {
            return [
                'status' => 'error',
                'message' => 'Produto não encontrado.'
            ];
        }

        $imagens = $produtos->imagens;

        if (empty($imagens)) {
            return [
                'status' => 'error',
                'message' => 'Nenhuma imagem encontrada para o produto especificado.'
            ];
        }

        return [
            'status' => 'success',
            'data' => $imagens
        ];
    }

    public function actionDetalhes($id)
    {
        $produtomodel = new $this->modelClass;
        //$produto = $produtomodel::findOne(['id' => $id]);
        $produto = $produtomodel::find()->with('imagens')->where(['id' => $id])->one();
        if (!$produto) {
            return [
                'status' => 'error',
                'message' => 'Nenhum produto encontrado.'
            ];
        }
        if ($produto) {
            $imagens = $produto->imagens;
            foreach ($imagens as $imagem) {
                //
            }
        }

        return [
            'status' => 'success',
            'data' => $produto,
            'images' => $imagens
        ];
    }
}
