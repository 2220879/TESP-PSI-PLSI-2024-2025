<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Metodopagamento $model */

$this->title = 'Atualizar: ' . $model->metodoPagamento;
$this->params['breadcrumbs'][] = ['label' => 'Metodopagamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->metodoPagamento, 'url' => ['view', 'id' => $model->metodoPagamento]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="metodopagamento-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
