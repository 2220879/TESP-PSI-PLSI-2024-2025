<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\FaturaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="fatura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'dataEmissao') ?>

    <?= $form->field($model, 'horaEmissao') ?>

    <?= $form->field($model, 'valorTotal') ?>

    <?= $form->field($model, 'ivaTotal') ?>

    <?php // echo $form->field($model, 'nif') ?>

    <?php // echo $form->field($model, 'metodopagamento_id') ?>

    <?php // echo $form->field($model, 'metodoentrega_id') ?>

    <?php // echo $form->field($model, 'encomenda_id') ?>

    <?php // echo $form->field($model, 'profile_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
