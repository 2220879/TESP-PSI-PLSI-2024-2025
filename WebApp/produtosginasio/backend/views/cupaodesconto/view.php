<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Cupaodesconto $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cupaodescontos    ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cupaodesconto-view">

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
            //'id',
            'codigo',
            'desconto',
            'dataFim',
        ],
    ]) ?>

    <h3>Uso de Cupões:</h3>

    <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $model->usocupos,
                'pagination' => [
                        'pageSize' => 20,
                ],
            ]),
            'columns' => [
                [
                    'attribute' => 'profile_id',
                    'label' => 'Cliente',
                    'value' => function ($model) {
                        return $model->profile->user->username;
                    },
                ],
                [
                    'attribute' => 'dataUso',
                    'label' => 'Data de Uso',
                    'value' => function ($model) {
                        return $model->dataUso;
                    }
                ],
            ],
]);?>

</div>
