<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Cupaodesconto $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cupaodesconto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desconto')->input('number', ['step' => '0.01', 'min' => 0, 'max' => 1]) ?>

    <?= $form->field($model, 'dataFim')->input('date') ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
