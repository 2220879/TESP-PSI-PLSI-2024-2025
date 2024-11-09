<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Tamanho $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tamanho-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'referencia')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
