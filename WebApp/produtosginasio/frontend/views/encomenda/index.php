<?php

use common\models\Encomenda;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */

$this->title = 'Minhas Encomendas';
?>
<div class="container">
    <div class="encomenda-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php foreach ($encomendas as $encomenda): ?>
            <div class="encomenda-descricao">
                <h3>Encomenda Nº <?= $encomenda->id ?></h3>
                <p>Data: <?= date('d-m-Y', strtotime($encomenda->data)) ?></p>
                <p>Hora: <?= $encomenda->hora ?></p>
                <p>Estado: <?= $encomenda->estadoEncomenda ?></p>
                <p>
                    <?= Html::a('Ver Detalhes', ['detalhes', 'id' => $encomenda->id], ['class' => 'btn btn-primary']) ?>
                </p>

            </div>
            <hr>
        <?php endforeach; ?>
    </div>
</div>
