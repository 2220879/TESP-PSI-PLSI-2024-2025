<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\FaturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Vendas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fatura-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'dataEmissao',
            'horaEmissao',
            'valorTotal',
            'ivaTotal',
            //'nif',
            //'metodopagamento_id',
            //'metodoentrega_id',
            //'encomenda_id',
            [
                'attribute' => 'profile_id',
                'value' => function ($model) {
                    return $model->profile->user->username ? $model->profile->user->username : 'N/A'; // Exibe o nome da marca
                },
                'label' => 'Cliente',
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, Fatura $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
