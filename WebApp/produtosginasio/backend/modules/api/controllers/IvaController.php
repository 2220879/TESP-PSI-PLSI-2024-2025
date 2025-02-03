<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class IvaController extends ActiveController
{
    public $modelClass = 'common\models\Iva';

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
            $ivaModel = new $this->modelClass;
            $recs = $ivaModel::find()->all();
            return ['count' => count($recs)];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar os ivas.'];
    }

    public function actionIvas()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $ivaModel = new $this->modelClass;
            $recs = $ivaModel::find()->all();
            return ['ivas' => $recs];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os ivas.'];
    }
}