<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Compra $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="compra-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'total')->input('number', ['step' => '0.01', 'min' => 0]) ?>

    <?= $form->field($model, 'dataDespesa')->input('date') ?>

    <?= $form->field($model, 'fornecedor_id')->dropDownList($fornecedores, ['prompt' => 'Selecione uma Fornecedor']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
