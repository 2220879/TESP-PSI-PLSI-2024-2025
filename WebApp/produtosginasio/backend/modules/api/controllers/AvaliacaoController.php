<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Avaliacao;
use common\models\Fatura;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class AvaliacaoController extends ActiveController
{
    public $modelClass = 'common\models\Avaliacao';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCriaravaliacao()
    {
        $request = Yii::$app->request;
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $profile = Profile::find()->where(['user_id' => $user->id])->one();

                $avaliacao = new Avaliacao();

                $descricao = $request->getBodyParam('descricao');
                $produtoId = $request->getBodyParam('produto');

                $avaliacao->descricao = $descricao;
                $avaliacao->produto_id = $produtoId;
                $avaliacao->profile_id = $profile->id;
                $avaliacao->save();

                return 'Avaliação criada com sucesso!';
            }
        }

        return 'Não foi possível criar a Avaliação ao produto pretendido.';
    }

    public function actionAlteraravaliacao()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $request = Yii::$app->request;
                $avaliacaoId = $request->getBodyParam('avaliacao');

                $profile = Profile::find()->where(['user_id' => $user->id])->one();
                $avaliacao = Avaliacao::find()->where(['id' => $avaliacaoId, 'profile_id' => $profile->id])->one();

                if ($avaliacao != null) {
                    $avaliacao->update();
                    return 'Avaliação alterada com sucesso!';
                } else {
                    return 'Avaliação não encontrada.';
                }
            }
        }

        return 'Não foi possível alterar a avaliação do produto pretendido.';
    }

    public function actionApagaravaliacao()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $request = Yii::$app->request;
                $avaliacaoId = $request->getBodyParam('avaliacao');

                $profile = Profile::find()->where(['user_id' => $user->id])->one();
                $avaliacao = Avaliacao::find()->where(['id' => $avaliacaoId, 'profile_id' => $profile->id])->one();

                if ($avaliacao != null) {
                    $avaliacao->delete();
                    return 'Avaliação apagada com sucesso!';
                } else {
                    return 'Avaliação não encontrada.';
                }
            }
        }

        return 'Não foi possível apagar a avaliação do produto pretendido.';
    }
}