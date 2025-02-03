<?php

use common\models\Fatura;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\FaturaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Minhas Compras';
?>
<div class="container">
    <div class="compra-index">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php foreach ($faturas as $fatura): ?>
            <div class="compra-descricao">
                <h3>Fatura Nº <?= $fatura->id ?></h3>
                <p>
                    <?= Html::a('Ver Fatura', ['view', 'id' => $fatura->id], ['class' => 'btn btn-primary']) ?>
                </p>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>

</div>