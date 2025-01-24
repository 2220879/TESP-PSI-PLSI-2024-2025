<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Tamanho;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class TamanhoController extends ActiveController
{
    public $modelClass = 'common\models\Tamanho';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
            'except' => ['tamanhos']
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
                $tamanhoModel = new $this->modelClass;
                $recs = $tamanhoModel::find()->all();
                return ['count' => count($recs)];
            }
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possivel contar os tamanhos.'];
    }

    public function actionTamanhos()
    {
        $produtosHasTamanhos = ProdutosHasTamanho::find()->all();

        //Extrai os IDs dos tamanhos
        $tamanhoIds = array_map(function ($produtoHasTamanho) {
            return $produtoHasTamanho->tamanho_id;
        }, $produtosHasTamanhos);

        //vai buscar os nomes dos tamanhos na tabela Tamanhos
        $tamanhos = Tamanho::find()
            ->select(['id', 'referencia'])
            ->where(['id' => $tamanhoIds])
            ->asArray()
            ->all();

        return ['tamanhos' => $tamanhos];
    }
}