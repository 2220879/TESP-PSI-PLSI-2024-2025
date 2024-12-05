<?php

use common\models\Usocupao;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\UsocupaoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Usocupaos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usocupao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'cupaodesconto_id',
            [
                'label' => 'Cupao de Desconto',
                'value' => function($model){
                    return $model->cupaodesconto->desconto;
                }
            ],
            //'profile_id',
            [
                'label' => 'Utilizador',
                'value' => function($model){
                    return $model->profile->user->username;
                }
            ],
            'dataUso',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Usocupao $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
