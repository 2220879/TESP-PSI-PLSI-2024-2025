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
            throw new \yii\web\ForbiddenHttpException('Token de acesso ausente ou inválido na URL');
        }

        $user = User::findIdentityByAccessToken($authToken);

        if (!$user) {
            throw new \yii\web\ForbiddenHttpException('Não autenticado');
        }

        Yii::$app->params['id'] = $user->id;
        return $user;
    }
}