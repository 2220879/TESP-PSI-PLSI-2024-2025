<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'nif')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'morada')->textInput() ?>

    <?= $form->field($model, 'telefone')->input('number', ['min' => 0]) ?>

    <?= $form->field($model, 'role')->dropDownList($roles) ?>

    <?= $form->field($model, 'status')->dropDownList($status) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
