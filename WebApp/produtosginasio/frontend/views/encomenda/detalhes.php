<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */

$this->title = 'Detalhes Encomenda Nº ' . $encomenda->id;
\yii\web\YiiAsset::register($this);
?>
<div class="container">
    <div class="encomenda-detalhes">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Voltar', ['index', 'id' => $encomenda->profile->user->id], ['class' => 'btn btn-primary']) ?>
        </p>

        <div class="encomenda-descricao">
            <hr>
            <p>Data: <?= date('d-m-Y', strtotime($encomenda->data)) ?></p>
            <p>Hora: <?= $encomenda->hora ?></p>
            <p>Morada: <?= $encomenda->morada ?></p>
            <p>Telefone: <?= $encomenda->telefone ?></p>
            <p>Email: <?= $encomenda->email ?></p>
            <p>Estado: <?= $encomenda->estadoEncomenda ?></p>
            <hr>
            <h4>Descrição Encomenda</h4>
            <?php foreach ($Linhasfatura as $linha): ?>
                <div class="encomenda-descricao">
                    <p>Produto: <?= $linha->nomeProduto ?></p>
                    <p>Quantidade: <?= $linha->quantidade ?></p>
                    <p>Preço: <?= number_format($linha->subtotal, 2, ',', '.') ?>€</p>
                </div>
                <br>
            <?php endforeach; ?>

        </div>

    </div>
</div>
