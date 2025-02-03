<?php

namespace backend\modules\api\components;

use common\models\User;
use Yii;
use yii\filters\auth\AuthMethod;

class CustomAuth extends AuthMethod
{
    public function authenticate($user, $request, $response)
    {
        $authToken = $request->getQueryParam('auth_key');

        if (empty($authToken)) {
            throw new \yii\web\ForbiddenHttpException('Não tem sessão iniciada');
        }

        $user = User::findIdentityByAccessToken($authToken);

        if (!$user) {
            throw new \yii\web\ForbiddenHttpException('Não autenticado');
        }

        if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'O Utilizador não tem permissões de cliente'];
        }

        Yii::$app->params['id'] = $user->id;
        return $user;
    }
}