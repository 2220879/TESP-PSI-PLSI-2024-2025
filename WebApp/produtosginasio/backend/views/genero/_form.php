<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Genero $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="genero-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'referencia')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
