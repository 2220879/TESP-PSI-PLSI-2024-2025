<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="encomenda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'estadoEncomenda')->dropDownList($status, ['value' => $model->estadoEncomenda]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
