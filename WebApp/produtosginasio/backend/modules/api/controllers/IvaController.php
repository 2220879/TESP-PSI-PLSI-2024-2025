<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class IvaController extends ActiveController
{
    public $modelClass = 'common\models\Iva';

    public function actionCount()
    {
        $ivaModel = new $this->modelClass;
        $recs = $ivaModel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionIvas()
    {
        $ivaModel = new $this->modelClass;
        $recs = $ivaModel::find()->all();
        return ['ivas' => $recs];
    }
}