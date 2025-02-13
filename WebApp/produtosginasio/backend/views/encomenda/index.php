<?php

use common\models\Encomenda;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\EncomendaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Encomendas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="encomenda-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'data',
            'hora',
            //'morada:ntext',
            //'telefone',
            //'email:ntext',
            'estadoEncomenda:ntext',
            [
                'attribute' => 'profile_id',
                'value' => function ($model) {
                    return $model->profile->user->username ? $model->profile->user->username : 'N/A'; // Exibe o nome da marca
                },
                'label' => 'Cliente',
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update}', // Aqui você define as ações que quer mostrar (view e update)
                'urlCreator' => function ($action, Encomenda $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
