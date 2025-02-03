<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Marca;
use common\models\Categoria;
use common\models\Iva;
use common\models\Genero;
use common\models\Tamanho;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nomeProduto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'descricaoProduto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'marca_id')->dropDownList($marcas, ['prompt' => 'Selecione uma Marca']) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList($categorias, ['prompt' => 'Selecione uma Categoria']) ?>

    <?= $form->field($model, 'iva_id')->dropDownList($ivas, ['prompt' => 'Selecione um Iva']) ?>

    <?= $form->field($model, 'genero_id')->dropDownList($generos, ['prompt' => 'Selecione um Genero']) ?>

    <?= $form->field($imagemForm, 'imagens[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>