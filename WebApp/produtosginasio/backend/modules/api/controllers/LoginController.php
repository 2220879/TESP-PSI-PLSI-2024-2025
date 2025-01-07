<?php

namespace backend\modules\api\controllers;

use common\models\Carrinhocompra;
use common\models\Profile;
use common\models\User;
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
        $profile = Profile::find()->where(['user_id' => $user->id])->one();

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

        return ['auth_key' => $auth_key, 'username' => $user->username, 'email' => $user->email, 'profile_id' => $profile->id];
    }

    public function actionCriaruser()
    {

        $request = Yii::$app->request;

        $username = $request->getBodyParam('username');
        $email = $request->getBodyParam('email');
        $password = $request->getBodyParam('password');
        $nif = $request->getBodyParam('nif');
        $morada = $request->getBodyParam('morada');
        $telefone = $request->getBodyParam('telefone');

        if (empty($username) || empty($password) || empty($email) || empty($nif) || empty($morada) || empty($telefone)) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Campos vazios'];
        } else if (User::find()->where(['username' => $username])->one()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Username inserido já está em uso.'];
        } else if (User::find()->where(['email' => $email])->one()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Email inserido já está em uso.'];
        } else if (Profile::find()->where(['nif' => $nif])->one()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Nif inserido já está em uso.'];
        } else if (Profile::find()->where(['telefone' => $telefone])->one()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Telefone inserido já está em uso.'];
        }

        //cria um novo Utilizador
        $user = new User();

        //atribui os respetivos os dados ao novo user
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();

        //se correu tudo bem ao gravar os dados do user
        if ($user->save(false)) {

            //atribui a role
            $auth = Yii::$app->authManager;
            $cliente = $auth->getRole('cliente');
            $auth->assign($cliente, $user->getId());

            //cria um novo perfil ao utilizador criado
            $profile = new Profile();

            //atribui o respetivo valor para cada campo do perfil
            $profile->nif = $nif;
            $profile->morada = $morada;
            $profile->telefone = $telefone;
            $profile->user_id = $user->id;

            //se o registo do perfil foi concluído
            if ($profile->save()) {
                $carrinhoCompras = new Carrinhocompra();
                $carrinhoCompras->quantidade = 0;
                $carrinhoCompras->valorTotal = 0.00;
                $carrinhoCompras->profile_id = $profile->id;
                $carrinhoCompras->save();
                return ['username' => $username, 'email' => $email, 'password' => $password, 'nif' => $nif, 'morada' => $morada, 'telefone' => $telefone];
            }
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Falha na criação de um novo cliente'];

    }

}