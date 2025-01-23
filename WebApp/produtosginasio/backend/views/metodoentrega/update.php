<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Metodoentrega $model */

$this->title = 'Atualizar Metodo Entrega: ' . $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Metodoentregas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->descricao, 'url' => ['view', 'id' => $model->descricao]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="metodoentrega-update">

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?= $this->render('_form', [
        'model' => $model,
        'vigor' => $vigor,
    ]) ?>

</div>
