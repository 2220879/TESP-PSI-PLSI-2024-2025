<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Atualizar Utilizador: ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['value' => $user->username]) ?>

    <?= $form->field($model, 'email')->textInput(['value' => $user->email]) ?>

    <?= $form->field($model, 'password')->passwordInput(['value' => '']) ?>

    <?= $form->field($model, 'nif')->textInput(['value' => $profile->nif]) ?>

    <?= $form->field($model, 'morada')->textInput(['value' => $profile->morada]) ?>

    <?= $form->field($model, 'telefone')->textInput(['value' => $profile->telefone]) ?>

    <?= $form->field($model, 'role')->dropDownList($roles, ['value' => key(Yii::$app->authManager->getRolesByUser($user->id))]) ?>

    <?= $form->field($model, 'status')->dropDownList($status, ['value' => $user->status]) ?>

    <div class="form-group">
        <?= Html::submitButton('Alterar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
