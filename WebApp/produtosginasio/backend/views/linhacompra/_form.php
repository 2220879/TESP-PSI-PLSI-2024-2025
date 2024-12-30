<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Linhacompra $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="linhacompra-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'iva')->textInput() ?>

    <?= $form->field($model, 'compra_id')->hiddenInput(['value' => $compra->id])->label(false) ?>

    <?= $form->field($model, 'produto_id')->dropDownList($produtos, ['prompt' => 'Selecione um Produto'])->label('Produto') ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <div class="form-group">
        <label for="tamanhos_quantidades">Tamanhos e Quantidades</label>
        <div id="tamanhos_quantidades">
            <?php foreach ($tamanhos as $id => $referencia): ?>
                <div class="tamanho-group">
                    <label><?= $referencia ?></label>
                    <?= Html::input('number', 'quantidade_tamanho[' . $id . ']', '', ['class' => 'form-control', 'placeholder' => 'Quantidade', 'min' => 0]) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
