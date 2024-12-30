<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */

$this->title = 'Encomenda Nº' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Encomendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="encomenda-view">

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'data',
            'hora',
            'morada:ntext',
            'telefone',
            'email:ntext',
            [
                'attribute' => 'estadoEncomenda:ntext',
                'value' => function ($model) {
                    return $model->estadoEncomenda ? $model->estadoEncomenda : 'N/A';
                },
                'label' => 'Estado Encomenda',
            ],
            [
                'attribute' => 'profile_id',
                'value' => function ($model) {
                    return $model->profile->user->username ? $model->profile->user->username : 'N/A';
                },
                'label' => 'Utilizador',
            ],
        ],
    ]) ?>

</div>
