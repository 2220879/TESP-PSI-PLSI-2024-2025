<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */

$this->title = $model->nomeProduto;
\yii\web\YiiAsset::register($this);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
<div class="container">

    <!-- Exibe todas as mensagens flash -->
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>

    <div class="container-produto">
        <div class="produto-imagens">
            <div id="productCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($imagens as $index => $imagem): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <?= Html::img('./../../backend/web/uploads/' . $imagem->filename, ['class' => 'd-block w-100', 'style' => 'height: 300px;']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Próxima</span>
                </a>
            </div>
        </div>
        <div class="produto-atributos">
            <p><label>Preço: <?= Html::encode($model->preco) ?> €</label></p>
            <p><label>Marca: <?= Html::encode($model->marca->nomeMarca) ?></label></p>
            <p><label>Categoria: <?= Html::encode($model->categoria->nomeCategoria) ?></label></p>
            <p><label>Iva: <?= Html::encode($model->iva->percentagem) ?></label></p>
            <p><label>Género: <?= Html::encode($model->genero->referencia) ?></label></p>
            <p><label>Tamanho: <?= Html::encode($model->tamanho->referencia) ?></label></p>
            <a href="">
                <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
            </a>
            <!-- Botão de Favoritos simples -->
            <a href="<?= yii\helpers\Url::to(['favorito/create', 'produto_id' => $model->id]) ?>" class="ms-3">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <hr>
    <div class="produto-descricao">
        <h3>Descrição Produto</h3>
        <p><?= Html::encode($model->descricaoProduto) ?></p>
    </div>
    <hr>
    <div class="produto-avaliacoes">
        <h3 align="center">Avaliações Produto</h3>
    </div>

</div>
