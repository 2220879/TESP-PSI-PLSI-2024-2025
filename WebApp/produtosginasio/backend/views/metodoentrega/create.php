<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Metodoentrega $model */

$this->title = 'Create Metodo Entrega';
$this->params['breadcrumbs'][] = ['label' => 'Metodo Entregas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="metodoentrega-create">

    <h1><?= Html::encode($this->title) ?></h1>
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
