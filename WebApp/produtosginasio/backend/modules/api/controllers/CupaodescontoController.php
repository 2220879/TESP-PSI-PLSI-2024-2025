<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class CupaodescontoController extends ActiveController
{
    public $modelClass = 'common\models\Cupaodesconto';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
            'except' => ['cupoesdescontovalidos'],
        ];
        return $behaviors;
    }

    public function actionCount()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $cupaodescontomodel = new $this->modelClass;
            $recs = $cupaodescontomodel::find()->all();
            return ['count' => count($recs)];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar os cupões de desconto.'];
    }

    public function actionCupaodesconto()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $cupaodescontomodel = new $this->modelClass;
            $recs = $cupaodescontomodel::find()->all();
            return ['cupaodesconto' => $recs];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter os cupões de desconto.'];
    }

    public function actionCupoesdescontovalidos()
    {
        $cupaodescontomodel = new $this->modelClass;

        $currentDate = date('Y-m-d');

        $recs = $cupaodescontomodel::find()
            ->where(['>=', 'dataFim', $currentDate])
            ->all();

        $cupoesDesconto = [];
        foreach ($recs as $rec) {
            $rec->desconto = $rec->desconto * 100;
            $rec->dataFim = date('d-m-Y', strtotime($rec->dataFim));
            $cupoesDesconto[] = $rec;
        }

        return $cupoesDesconto;
    }

}