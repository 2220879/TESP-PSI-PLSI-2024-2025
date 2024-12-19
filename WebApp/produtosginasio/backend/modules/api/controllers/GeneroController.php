<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class GeneroController extends ActiveController
{
    public $modelClass = 'common\models\Genero';

    public function actionCount()
    {
        $generoModel = new $this->modelClass;
        $recs = $generoModel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionGeneros()
    {
        $generoModel = new $this->modelClass;
        $recs = $generoModel::find()->all();
        return ['generos' => $recs];
    }
}