<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class MarcaController extends ActiveController
{
    public $modelClass = 'common\models\Marca';

    public function actionCount()
    {
        $marcaModel = new $this->modelClass;
        $recs = $marcaModel::find()->all();
        return ['count'=>count($recs)];
    }

    public function actionMarcas()
    {
        $marcaModel = new $this->modelClass;
        $recs = $marcaModel::find()->all();
        return ['marcas'=>$recs];
    }
}