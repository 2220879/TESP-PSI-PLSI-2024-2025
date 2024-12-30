<?php

namespace backend\modules\api\controllers;

use backend\models\UserForm;
use Yii;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class LoginController extends ActiveController
{
    //Variável do Modelo
    public $modelClass = 'common\models\User';

    public function actionLogin()
    {
        $userModel = new $this->modelClass;
        $request = Yii::$app->request;
        $username = $request->getBodyParam('username');
        $password = $request->getBodyParam('password');

        if (empty($username) || empty($password)) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Campos vazios'];
        }

        $user = $userModel::find()->where(['username' => $username])->one();

        if (!$user || !$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Credenciais incorretas'];
        }

        // Verifica se o usuário tem o papel "cliente"
        if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
        }

        $auth_key = $user->getAuthKey();

        if (!$auth_key) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Não foi possível obter a auth_key'];
        }

        return ['auth_key' => $auth_key];
    }

    public function actionCriaruser()
    {
        //instancia o UserForm
        $model = new UserForm();

        $request = Yii::$app->request;

        $username = $request->getBodyParam('username');
        $email = $request->getBodyParam('email');
        $password = $request->getBodyParam('password');
        $nif = $request->getBodyParam('nif');
        $morada = $request->getBodyParam('morada');
        $telefone = $request->getBodyParam('telefone');

        if ($model->create($username, $email, $password, $nif, $morada, $telefone)) {
            return ['username' => $username, 'email' => $email, 'password' => $password, 'nif' => $nif, 'morada' => $morada, 'telefone' => $telefone];
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Falha na criação de um novo cliente'];

    }

}