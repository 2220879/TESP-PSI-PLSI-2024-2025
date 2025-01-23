<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Categoria;
use common\models\Genero;
use common\models\Marca;
use common\models\Produto;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class ProdutoController extends ActiveController
{
    public $modelClass = 'common\models\Produto';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
            'except' => ['produtos', 'ultimosprodutosinseridos'],
        ];
        return $behaviors;
    }

    public function actionCount()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $produtosmodel = new $this->modelClass;
                $recs = $produtosmodel::find()->all();
                return ['count' => count($recs)];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel contar os produtos.'];

    }

    public function actionUltimosprodutosinseridos()
    {
        $produtos = Produto::find()
            ->with(['imagens' => function ($query) {
                // Carrega apenas a primeira imagem associada
                $query->orderBy(['id' => SORT_ASC])->limit(1);
            }])
            ->orderBy(['id' => SORT_DESC])
            ->limit(9)
            ->all();

        $baseUrl = 'http://172.22.21.204' . Yii::getAlias('@web/uploads/');
        $resultado = [];

        foreach ($produtos as $produto) {
            //array dos produto
            $produtoData = [
                'id' => $produto->id,
                'nomeProduto' => $produto->nomeProduto,
                'preco' => $produto->preco,
                'quantidade' => $produto->quantidade,
                'descricaoProduto' => $produto->descricaoProduto,
                'marca' => $produto->marca->nomeMarca,
                'categoria' => $produto->categoria->nomeCategoria,
                'iva' => $produto->iva->percentagem,
                'genero' => $produto->genero->referencia,
                'imagem' => null
            ];

            // Verifica se o produto tem imagens associadas
            if (!empty($produto->imagens)) {
                // Vai buscar a primeira imagem
                $primeiraImagem = $produto->imagens[0];

                // Monta a URL completa da imagem
                $produtoData['imagem'] = $baseUrl . $primeiraImagem->filename;
            }

            //adiciona os dados do produto ao array de resultados
            $resultado[] = $produtoData;
        }
        return $resultado;

    }

    public function actionProdutos()
    {
        $produtos = Produto::find()
            ->with(['imagens' => function ($query) {
                // Carrega apenas a primeira imagem associada
                $query->orderBy(['id' => SORT_ASC])->limit(1);
            }])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        $baseUrl = 'http://172.22.21.204' . Yii::getAlias('@web/uploads/');
        $resultado = [];

        foreach ($produtos as $produto) {
            //array dos produto
            $produtoData = [
                'id' => $produto->id,
                'nomeProduto' => $produto->nomeProduto,
                'preco' => $produto->preco,
                'quantidade' => $produto->quantidade,
                'descricaoProduto' => $produto->descricaoProduto,
                'marca' => $produto->marca->nomeMarca,
                'categoria' => $produto->categoria->nomeCategoria,
                'iva' => $produto->iva->percentagem,
                'genero' => $produto->genero->referencia,
                'imagem' => null
            ];

            // Verifica se o produto tem imagens associadas
            if (!empty($produto->imagens)) {
                // Vai buscar a primeira imagem
                $primeiraImagem = $produto->imagens[0];

                // Monta a URL completa da imagem
                $produtoData['imagem'] = $baseUrl . $primeiraImagem->filename;
            }

            //adiciona os dados do produto ao array de resultados
            $resultado[] = $produtoData;
        }
        return $resultado;

    }

    public function actionBuscarpornome($nomeProduto)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $produtosmodel = new $this->modelClass;
                $produtos = $produtosmodel::find()->where(['nomeProduto' => $nomeProduto])->all();
                return $produtos;
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os produtos pelo nome pretendido.'];
    }

    public function actionBuscarportamanho($tamanho_id)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
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
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os produtos pelo tamanho selecionado.'];
    }

    public function actionBuscarpormarca($marca_id)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $produtosmodel = new $this->modelClass;
                $produtos = $produtosmodel::find()->where(['marca_id' => $marca_id])->all();

                if (empty($produtos)) {
                    return ['message' => 'Nenhum produto encontrado com o ID de marca especificado.'];
                }

                return $produtos;
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os produtos pela marca selecionada.'];
    }

    public function actionBuscarporcategoria($categoria_id)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $produtosmodel = new $this->modelClass;
                $produtos = $produtosmodel::find()->where(['categoria_id' => $categoria_id])->all();

                if (empty($produtos)) {
                    return ['message' => 'Nenhum produto encontrado com o ID de categoria especificado.'];
                }

                return $produtos;
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os produtos pela categoria selecionada.'];
    }

    public function actionBuscarporgenero($genero_id)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $produtosmodel = new $this->modelClass;
                $produtos = $produtosmodel::find()->where(['genero_id' => $genero_id])->all();

                if (empty($produtos)) {
                    return ['message' => 'Nenhum produto encontrado com o ID de genero especificado.'];
                }

                return $produtos;
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os produtos pela genero selecionado.'];
    }

    public function actionImagens($produto_id)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
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
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os imagems do produto selecionado.'];
    }

    /*public function actionDetalhes($id)
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
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
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter os detalhes do produto pretendido.'];
    }*/
}
