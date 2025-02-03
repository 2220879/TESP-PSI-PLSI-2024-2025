<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Metodoentrega $model */

$this->title = $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Metodoentregas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="metodoentrega-view">

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
            'descricao:ntext',
            'diasEntrega:ntext',
            'preco',
            //'vigor',
            [
                'label' => 'Vigor',
                'value' => $model->vigor ? 'Está em vigor' : 'Não está em vigor',
            ],
        ],
    ]) ?>

</div>
