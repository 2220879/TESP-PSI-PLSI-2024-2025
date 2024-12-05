<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Linhacompra $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="linhacompra-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'iva')->textInput() ?>

    <?= $form->field($model, 'compra_id')->hiddenInput(['value' => $compra->id])->label(false) ?>

    <?= $form->field($model, 'produto_id')->dropDownList($produtos, ['prompt' => 'Selecione um Produto'])->label('Produto') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
