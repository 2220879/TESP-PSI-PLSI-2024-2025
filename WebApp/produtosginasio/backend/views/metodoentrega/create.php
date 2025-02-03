<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Metodoentrega $model */

$this->title = 'Criar Metodo Entrega';
$this->params['breadcrumbs'][] = ['label' => 'Metodo Entregas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodoentrega-create">
    
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
