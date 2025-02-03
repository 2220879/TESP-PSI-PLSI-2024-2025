<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class GeneroController extends ActiveController
{
    public $modelClass = 'common\models\Genero';

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
            $generoModel = new $this->modelClass;
            $recs = $generoModel::find()->all();
            return ['count' => count($recs)];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar os géneros'];
    }

    public function actionGeneros()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $generoModel = new $this->modelClass;
            $recs = $generoModel::find()->all();
            return ['generos' => $recs];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter os géneros.'];
    }
}