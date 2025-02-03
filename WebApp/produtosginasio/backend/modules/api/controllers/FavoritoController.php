<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Favorito;
use common\models\Imagem;
use common\models\Produto;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class FavoritoController extends ActiveController
{
    public $modelClass = 'common\models\Favorito';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCount()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $favoritosmodel = new $this->modelClass;
            $recs = $favoritosmodel::find()->all();
            return ['count' => count($recs)];
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar os favoritos.'];

    }

    public function actionFavoritos()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $profile = Profile::find()->where(['user_id' => $userID])->one();
            $favoritosmodel = new $this->modelClass;

            $favoritos = $favoritosmodel::find()->where(['profile_id' => $profile->id])->all();
            $baseUrl = 'http://172.22.21.204' . Yii::getAlias('@web/uploads/');
            $resultados = [];
            foreach ($favoritos as $favorito) {
                $produto = Produto::findOne($favorito->produto_id);
                $data = [
                    'id' => $favorito->id,
                    'produto_id' => $favorito->produto_id,
                    'profile_id' => $favorito->profile_id,
                    'nomeProduto' => $produto->nomeProduto,
                    'preco' => number_format($produto->preco, 2),
                    'imagem' => null,];
                if (!empty($produto->imagens)) {
                    //vai buscar a primeira imagem
                    $primeiraImagem = $produto->imagens[0];
                    $data['imagem'] = $baseUrl . $primeiraImagem->filename;
                }
                //adiciona os dados do produto ao array
                $resultados[] = $data;
            }

            return $resultados;
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter os favoritos.'];
    }

    public function actionAtribuirprodutofavorito()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $request = Yii::$app->request;

            $profile = Profile::find()->where(['user_id' => $user->id])->one();

            $produtoId = $request->getBodyParam('produto');

            if (Favorito::find()->where(['produto_id' => $produtoId, 'profile_id' => $profile->id])->exists()) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Produto já adicionado nos favoritos.'];
            }

            $favorito = new Favorito();
            $favorito->produto_id = $produtoId;
            $favorito->profile_id = $profile->id;
            if ($favorito->save()) {
                $produto = Produto::find()->where(['id' => $produtoId])->one();

                $imagens = Produto::find()
                    ->with(['imagens' => function ($query) {
                        //carrega apenas a primeira imagem associada
                        $query->orderBy(['id' => SORT_ASC])->limit(1);
                    }])
                    ->where(['id' => $produtoId])
                    ->all();

                $baseUrl = 'http://172.22.21.204' . Yii::getAlias('@web/uploads/');

                // Verifica se o produto tem imagens associadas
                if (!empty($produto->imagens)) {
                    //vai buscar a primeira imagem
                    $primeiraImagem = $produto->imagens[0];

                    $imagem = $baseUrl . $primeiraImagem->filename;
                } else {
                    $imagem = null;
                }

                return [
                    'id' => $favorito->id,
                    'produto_id' => $favorito->produto_id,
                    'profile_id' => $favorito->profile_id,
                    'nomeProduto' => $produto->nomeProduto,
                    'preco' => $produto->preco,
                    'imagem' => $imagem
                ];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Favorito não encontrado.'];
    }

    public function actionApagarprodutofavorito()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $request = Yii::$app->request;
            $favoritoID = $request->getBodyParam('favorito');

            $favorito = Favorito::find()->where(['id' => $favoritoID])->one();

            if ($favorito != null) {
                $favorito->delete();

                return $favorito->delete();
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Favorito não encontrado.'];
            }
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível apagar o produto pretendido nos favoritos.'];
    }
}