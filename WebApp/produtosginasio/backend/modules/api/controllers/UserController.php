<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public $modelProfileClass = 'common\models\Profile';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function actionAtualizaruser()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $request = Yii::$app->request;

            $username = $request->getBodyParam('username');
            $email = $request->getBodyParam('email');
            $password = $request->getBodyParam('password');
            $nif = $request->getBodyParam('nif');
            $morada = $request->getBodyParam('morada');
            $telefone = $request->getBodyParam('telefone');

            //altera os respetivos os dados do user a editar
            $user->username = $username;
            $user->email = $email;

            //se o campo da password não estiver vazia, edita a password
            if ($password != null) {
                $user->setPassword($password);
            }

            $user->generateAuthKey();

            //se a alteração dos dados do user foram gravados com sucesso
            if ($user->save(false)) {

                //seleciona o perfil do user a editar
                $profile = Profile::findOne(['user_id' => $user->id]);

                //altera os respetivos os dados do perfil do user a editar
                $profile->nif = $nif;
                $profile->morada = $morada;
                $profile->telefone = $telefone;

                //se o registo do perfil foi concluído
                if ($profile->save()) {
                    return ['auth_key' => $user->auth_key];
                }
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi realizado a atualização dos dados.'];

    }

    public function actionDadosuserprofile()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $cliente = User::find()->where(['id' => $userID])->one();
            $profile = Profile::find()->where(['user_id' => $userID])->one();

            if ($cliente != null && $profile != null) {
                return ['username' => $cliente->username, 'email' => $cliente->email, 'nif' => $profile->nif, 'morada' => $profile->morada, 'telefone' => $profile->telefone];
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Falha a obter dos dados do cliente especifico'];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel os dados do utilizador'];
    }
}