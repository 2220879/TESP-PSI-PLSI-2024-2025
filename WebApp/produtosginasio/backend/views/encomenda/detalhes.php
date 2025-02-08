<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */

$this->title = 'Detalhes Encomenda';
$this->params['breadcrumbs'][] = ['label' => 'Encomendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="encomenda-detalhes">

    <?= GridView::widget([
        'dataProvider' => new yii\data\ArrayDataProvider([
            'allModels' => $produtos,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'nomeProduto',
                'value' => function ($model) {
                    return $model->nomeProduto;
                }
            ],
            [
                'attribute' => 'quantidade',
                'value' => function ($model) {
                    return $model->quantidade;
                }
            ],
        ],
    ]); ?>

</div>