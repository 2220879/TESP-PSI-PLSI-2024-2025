<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Fatura $model */

$this->title = 'Fatura_' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fatura-view">

    <p>
        <?= Html::a('Detalhes Fatura', ['linhavenda/index', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
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
            'dataEmissao',
            'horaEmissao',
            'valorTotal',
            'ivaTotal',
            'nif',
            [
                'attribute' => 'metodopagamento_id',
                'value' => function ($model) {
                    return $model->metodopagamento->metodoPagamento ? $model->metodopagamento->metodoPagamento : 'N/A';
                },
                'label' => 'Método Pagamento',
            ],
            [
                'attribute' => 'metodopagamento_id',
                'value' => function ($model) {
                    return $model->metodoentrega->descricao ? $model->metodoentrega->descricao : 'N/A';
                },
                'label' => 'Método Entrega',
            ],
            'encomenda_id',
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
