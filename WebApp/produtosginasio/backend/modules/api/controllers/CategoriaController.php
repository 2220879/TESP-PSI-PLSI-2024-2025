<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class CategoriaController extends ActiveController
{
    public $modelClass = 'common\models\Categoria';

    public function actionCount()
    {
        $categoriaModel = new $this->modelClass;
        $recs = $categoriaModel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionCategorias()
    {
        $categoriaModel = new $this->modelClass;
        $recs = $categoriaModel::find()->all();
        return ['categorias' => $recs];
    }
}