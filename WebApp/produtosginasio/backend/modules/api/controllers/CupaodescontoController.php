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
        ];
        return $behaviors;
    }

    public function actionCount()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $cupaodescontomodel = new $this->modelClass;
                $recs = $cupaodescontomodel::find()->all();
                return ['count' => count($recs)];
            }
        }
        return 'Não foi possível contar os cupões de desconto.';
    }

    public function actionCupaodesconto()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $cupaodescontomodel = new $this->modelClass;
                $recs = $cupaodescontomodel::find()->all();
                return ['cupaodesconto' => $recs];
            }
        }
        return 'Não foi possível obter os cupões de desconto.';
    }
}