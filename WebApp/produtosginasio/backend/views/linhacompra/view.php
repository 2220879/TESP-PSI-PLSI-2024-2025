<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Linhacompra $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Linhacompras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="linhacompra-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', ['id' => $model->id, 'compra_id' => $model->compra->id]], ['class' => 'btn btn-primary']) ?>
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
            'quantidade',
            'preco',
            'iva',
            //'compra_id',
            [
                'label' => 'produto_id',
                'value' => $model->produto->nomeProduto,
            ],
        ],
    ]) ?>

</div>
