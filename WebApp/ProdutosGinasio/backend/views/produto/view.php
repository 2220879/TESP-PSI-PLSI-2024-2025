<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="produto-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nomeProduto',
            'preco',
            'quantidade',
            'descricaoProduto:ntext',
            [
                'attribute' => 'marca_id',
                'value' => function ($model) {
                    return $model->marca ? $model->marca->nomeMarca : 'N/A'; // Exibe o nome da marca
                },
                'label' => 'Marca',
            ],

            [
                'attribute' => 'categoria_id',
                'value' => function ($model) {
                    return $model->categoria ? $model->categoria->nomeCategoria : 'N/A'; // Exibe o nome da categoria
                },
                'label' => 'Categoria',
            ],

            [
                'attribute' => 'iva_id',
                'value' => function ($model) {
                    return $model->iva ? $model->iva->percentagem : 'N/A'; // Exibe a percentagem do IVA
                },
                'label' => 'IVA',
            ],

            [
                'attribute' => 'genero_id',
                'value' => function ($model) {
                    return $model->genero ? $model->genero->referencia : 'N/A'; // Exibe o nome do gênero
                },
                'label' => 'Gênero',
            ],

            [
                'attribute' => 'tamanho_id',
                'value' => function ($model) {
                    return $model->tamanho ? $model->tamanho->referencia : 'N/A'; // Exibe o nome do tamanho
                },
                'label' => 'Tamanho',
            ],
        ],
    ]) ?>

    <h3>Imagens:</h3>

    <div class="product-images">
        <?php foreach ($model->imagens as $imagem): ?>
            <div class="image-container">
                <?= Html::img(Yii::getAlias('@web/uploads/') . $imagem->filename, ['class' => 'product-image', 'style' => 'width: 200px; height: 200px;']) ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>