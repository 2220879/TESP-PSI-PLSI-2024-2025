<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */

$this->title = 'Atualizar ' . $model->nomeProduto;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="produto-view">

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
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
            //'id',
            'nomeProduto',
            'preco',
            'quantidade',
            'descricaoProduto:ntext',
            [
                'attribute' => 'marca_id',
                'value' => function ($model) {
                    return $model->marca ? $model->marca->nomeMarca : 'N/A';
                },
                'label' => 'Marca',
            ],

            [
                'attribute' => 'categoria_id',
                'value' => function ($model) {
                    return $model->categoria ? $model->categoria->nomeCategoria : 'N/A';
                },
                'label' => 'Categoria',
            ],

            [
                'attribute' => 'iva_id',
                'value' => function ($model) {
                    return $model->iva ? $model->iva->percentagem : 'N/A';
                },
                'label' => 'IVA',
            ],

            [
                'attribute' => 'genero_id',
                'value' => function ($model) {
                    return $model->genero ? $model->genero->referencia : 'N/A';
                },
                'label' => 'Gênero',
            ],
        ],
    ]) ?>

    <?php
    echo GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $tamanhos,
            'pagination' => false,
        ]),
        'columns' => [
            [
                'label' => 'Tamanhos',
                'attribute' => 'tamanho_id',
                'value' => function ($model) {
                    return $model->tamanho ? $model->tamanho->referencia : 'N/A';
                },
            ],
            [
                'label' => 'Quantidades',
                'attribute' => 'quantidade',
                'value' => function ($model) {
                    return $model->quantidade ? $model->quantidade : 0;
                },
            ],
        ],
    ])
    ?>

    <hr>

    <h3>Imagens:</h3>

    <div class="product-images">
        <?php foreach ($model->imagens as $imagem): ?>
            <div class="image-container">
                <?= Html::img(Yii::getAlias('@web/uploads/') . $imagem->filename, ['class' => 'product-image', 'style' => 'width: 200px; height: 200px;']) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <h3>Avaliações:</h3>

    <?= GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $avaliacoes,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]),
        'columns' => [
            [
                'attribute' => 'descricao',
                'label' => 'Avaliação',
            ],
            [
                'attribute' => 'profile_id',
                'label' => 'Cliente',
                'value' => function ($model) {
                    return $model->profile->user->username;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $url = Url::to(['avaliacao/delete', 'id' => $model->id]);

                        return Html::a(
                            '<i class="fa fa-trash"></i>',
                            $url,
                            [
                                'class' => 'btn btn-danger btn-sm',
                                'data-confirm' => 'Tem certeza que deseja apagar esta avaliação?',
                                'data-method' => 'post',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>