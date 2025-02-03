<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class CategoriaController extends ActiveController
{
    public $modelClass = 'common\models\Categoria';

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
            $categoriaModel = new $this->modelClass;
            $recs = $categoriaModel::find()->all();
            return ['count' => count($recs)];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar as categorias.'];
    }

    public function actionCategorias()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $categoriaModel = new $this->modelClass;
            $recs = $categoriaModel::find()->all();
            return ['categorias' => $recs];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter as categorias.'];
    }
}