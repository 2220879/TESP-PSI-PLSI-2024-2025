<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class MetodopagamentoController extends ActiveController
{
    public $modelClass = 'common\models\Metodopagamento';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    // Método para contar o total de métodos de pagamento
    public function actionCount()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $metodoPagamentoModel = new $this->modelClass;
            $recs = $metodoPagamentoModel::find()->all();
            return ['count' => count($recs)];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar os métodos de pagamento.'];
    }

    public function actionMetodopagamentos()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $metodopagamentoModel = new $this->modelClass;
            $recs = $metodopagamentoModel::find()->all();
            return ['metodopagamentos' => $recs];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel obter os métodos de pagamento.'];
    }
}
