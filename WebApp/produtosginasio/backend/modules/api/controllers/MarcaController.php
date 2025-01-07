<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class MarcaController extends ActiveController
{
    public $modelClass = 'common\models\Marca';

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
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $marcaModel = new $this->modelClass;
                $recs = $marcaModel::find()->all();
                return ['count' => count($recs)];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel contar as marcas.'];
    }

    public function actionMarcas()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $marcaModel = new $this->modelClass;
                $recs = $marcaModel::find()->all();
                return ['marcas' => $recs];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter as marcas.'];
    }
}