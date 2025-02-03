<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Linhacompra $model */

$this->title = 'Atualizar Linha Nº ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Linhacompras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="linhacompra-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'iva')->textInput() ?>

    <?= $form->field($model, 'compra_id')->hiddenInput(['value' => $compra->id])->label(false) ?>

    <?= $form->field($model, 'produto_id')->dropDownList($produtos, ['prompt' => 'Selecione um Produto'])->label('Produto') ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
