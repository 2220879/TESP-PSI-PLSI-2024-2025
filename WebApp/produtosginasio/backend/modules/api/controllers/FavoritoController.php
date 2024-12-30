<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Favorito;
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
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $favoritosmodel = new $this->modelClass;
                $recs = $favoritosmodel::find()->all();
                return ['count' => count($recs)];
            }
        }

        return 'Não foi possível contar os favoritos.';

    }

    public function actionFavoritos()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $favoritosmodel = new $this->modelClass;
                $produtos = $favoritosmodel::find()->all();
                return ['produtos' => $produtos];
            }
        }
        return 'Não foi possível obter os favoritos.';
    }

    public function actionAtribuirprodutofavorito()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            }
            else{
                $request = Yii::$app->request;

                $profile = Profile::find()->where(['user_id' => $user->id])->one();

                $favorito = new Favorito();

                $produtoId = $request->getBodyParam('produto');

                $favorito->produto_id = $produtoId;
                $favorito->profile_id = $profile->id;
                $favorito->save();

                return 'Favorito com sucesso!';
            }
        }
        return 'Favorito não encontrado.';
    }

    public function actionApagarprodutofavorito()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $request = Yii::$app->request;
                $produtoID = $request->getBodyParam('produto');

                $profile = Profile::find()->where(['user_id' => $user->id])->one();
                $favorito = Favorito::find()->where(['produto_id' => $produtoID, 'profile_id' => $profile->id])->one();

                if ($favorito != null) {
                    $favorito->delete();
                    return 'Favorito apagado com sucesso!';
                } else {
                    return 'Favorito não encontrada.';
                }
            }
        }

        return 'Não foi possível apagar o produto pretendido nos favoritos.';
    }
}