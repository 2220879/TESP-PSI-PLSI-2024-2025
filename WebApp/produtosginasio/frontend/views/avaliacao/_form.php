<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Avaliacao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="avaliacao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descricao')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'produto_id')->textInput(['style' => 'display:none'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar Alterações', ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-trash"></i>Apagar', ['avaliacao/delete', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'confirm' => 'Deseja apagar esta avaliação?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
