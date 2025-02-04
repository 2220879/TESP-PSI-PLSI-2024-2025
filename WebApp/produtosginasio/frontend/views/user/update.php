<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

$this->title = 'Atualizar Dados: ';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['value' => $user->username]) ?>

    <?= $form->field($model, 'email')->textInput(['value' => $user->email]) ?>

    <?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>

    <?= $form->field($model, 'nif')->input('number', ['value' => $profile->nif, 'min' => 0]) ?>

    <?= $form->field($model, 'morada')->textInput(['value' => $profile->morada]) ?>

    <?= $form->field($model, 'telefone')->input('number', ['value' => $profile->telefone, 'min' => 0]) ?>

    <div class="form-group">
        <?= Html::submitButton('Alterar Dados', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
