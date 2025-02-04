<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Metodoentrega $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="metodoentrega-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'diasEntrega')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'preco')->input('number', ['step' => '0.01', 'min' => 0]) ?>
    <?= $form->field($model, 'vigor')->dropDownList($vigor, ['prompt' => 'Selecione um vigor']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
