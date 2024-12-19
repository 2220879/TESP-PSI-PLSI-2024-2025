<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class TamanhoController extends ActiveController
{
    public $modelClass = 'common\models\Tamanho';

    public function actionCount()
    {
        $tamanhoModel = new $this->modelClass;
        $recs = $tamanhoModel::find()->all();
        return ['count'=>count($recs)];
    }

    public function actionTamanhos()
    {
        $tamanhoModel = new $this->modelClass;
        $recs = $tamanhoModel::find()->all();
        return ['tamanhos'=>$recs];
    }
}