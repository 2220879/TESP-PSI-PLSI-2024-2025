<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cupaodesconto $model */

$this->title = 'Update Cupaodesconto: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cupaodescontos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cupaodesconto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
