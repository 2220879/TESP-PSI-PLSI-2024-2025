<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php if ($model->hasErrors('password_hash')): ?>
        <div class="alert alert-danger">
            <?php foreach ($model->errors['password_hash'] as $error): ?>
                <p><?= Html::encode($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['value' => $model->username]) ?>

    <?= $form->field($model, 'email')->textInput(['value' => $model->email]) ?>

    <?= $form->field($model, 'password_hash')->passwordInput(['value' => '', 'required' => true])->label("Password") ?>

    <?= $form->field($profile, 'nif')->textInput(['value' => $profile->nif]) ?>

    <?= $form->field($profile, 'morada')->textInput(['value' => $profile->morada]) ?>

    <?= $form->field($profile, 'telefone')->textInput(['value' => $profile->telefone]) ?>

    <?= $form->field($model, 'status')->dropDownList($status, ['value' => $model->status]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
