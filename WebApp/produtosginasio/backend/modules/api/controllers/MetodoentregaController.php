<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use common\models\Metodoentrega;

// Supondo que você tenha um modelo Metodoentrega
use Yii;
use yii\rest\ActiveController;

class MetodoentregaController extends ActiveController
{
    public $modelClass = 'common\models\Metodoentrega';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    // Método para contar o total de métodos de entrega
    public function actionCount()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            // Verifica se o utilizador tem o papel "cliente"
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Utilizador introduzido não tem permissões de cliente'];
            } else {
                $metodoEntregaModel = new $this->modelClass;
                $recs = $metodoEntregaModel::find()->all();
                return ['count' => count($recs)];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível contar os métodos de entrega.'];
    }

    public function actionMetodoentregas()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $metodoentregaModel = new $this->modelClass;
            $recs = Metodoentrega::find()->where(['vigor' => 1])->all();
            return ['metodoentregas' => $recs];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter os métodos de entrega.'];
    }
}

