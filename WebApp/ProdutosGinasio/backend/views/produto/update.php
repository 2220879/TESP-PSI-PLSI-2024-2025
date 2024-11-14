<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */

$this->title = 'Update Produto: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="produto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nomeProduto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput() ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <?= $form->field($model, 'descricaoProduto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'marca_id')->dropDownList($marcas, ['prompt' => 'Selecione uma Marca']) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList($categorias, ['prompt' => 'Selecione uma Categoria']) ?>

    <?= $form->field($model, 'iva_id')->dropDownList($ivas, ['prompt' => 'Selecione um Iva']) ?>

    <?= $form->field($model, 'genero_id')->dropDownList($generos, ['prompt' => 'Selecione um Genero']) ?>

    <?= $form->field($model, 'tamanho_id')->dropDownList($tamanhos, ['prompt' => 'Selecione um Tamanho']) ?>

    <?= $form->field($imagemForm, 'imagens[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <h3>Imagens:</h3>
    <?php if ($model->imagens != null): ?>

        <div class="product-images">
            <?php foreach ($model->imagens as $imagem): ?>
                <div class="image-container">
                    <?= Html::img(Yii::getAlias('@web/uploads/') . $imagem->filename, ['class' => 'product-image', 'style' => 'width: 200px; height: 200px;']) ?>
                </div>
                <div>
                    <?php $form = ActiveForm::begin([
                        'action' => ['produto/updateimagem', 'id' => $imagem['id']],
                        'method' => 'post',
                    ]); ?>

                    <?= $form->field($imagemForm, 'imagens[]')->fileInput(['multiple' => false, 'accept' => 'image/*', 'required' => true]) ?>

                    <?= Html::submitButton('Atualizar', ['class' => 'btn btn-success']) ?>

                    <?php ActiveForm::end(); ?>
                </div>

                <div>
                    <?= Html::a('Apagar Imagem', ['produto/deleteimagem', 'id' => $imagem['id']], [
                        'data' => [
                            'confirm' => 'Tem certeza que deseja apagar esta imagem?',
                            'method' => 'post',
                        ],
                        'class' => 'btn btn-danger',
                    ]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>