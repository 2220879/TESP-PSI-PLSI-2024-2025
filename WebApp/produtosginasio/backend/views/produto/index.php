<?php

use common\models\Produto;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ProdutoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produto-index">

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Criar Produto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nomeProduto',
            'preco',
            'quantidade',
            'descricaoProduto:ntext',
            //'marca_id',
            //'categoria_id',
            //'iva_id',
            //'genero_id',
            //'tamanho_id',

            // Exibindo os dados relacionados:
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
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Produto $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>

