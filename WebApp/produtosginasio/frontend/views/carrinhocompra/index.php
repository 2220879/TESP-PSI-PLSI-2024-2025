<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $linhasCarrinho */
/** @var \frontend\models\Carrinhocompra $carrinho */

$this->title = 'Carrinho de Compras';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product_section layout_padding">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                <?= Html::encode($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach; ?>

        <?php if (empty($linhasCarrinho)): ?>
            <p>Seu carrinho está vazio.</p>
            <p>
                <?= Html::a('Explorar Produtos', ['produto/index'], ['class' => 'btn btn-primary']) ?>
            </p>
        <?php else: ?>
            <div class="row">
                <!-- Coluna dos Produtos -->
                <div class="col-md-8">
                    <div class="product_container p-4" style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px;">
                        <?php foreach ($linhasCarrinho as $linha): ?>
                            <div class="product-item d-flex align-items-center p-3 mb-3" style="border-bottom: 1px solid #ddd;">
                                <div class="img-box" style="width: 150px; height: 150px; overflow: hidden; margin-right: 20px;">
                                    <?php if (!empty($linha->produto->imagens) && isset($linha->produto->imagens[0])): ?>
                                        <img src="<?= htmlspecialchars("../../../backend/web/uploads/" . $linha->produto->imagens[0]->filename) ?>"
                                             alt="Imagem do Produto" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php endif; ?>
                                </div>
                                <div class="detail-box flex-grow-1">
                                    <a href="<?= Url::to(['produto/detalhes', 'id' => $linha->produto->id]) ?>"
                                       class="p_name" style="font-weight: bold; font-size: 1.4rem; color: #000;">
                                        <?= Html::encode($linha->produto->nomeProduto) ?>
                                    </a>
                                    <h6 class="p_price" style="margin: 10px 0; color: #000;">
            <span class="new_price">
                <?= Html::encode(number_format($linha->precoUnit, 2, ',', '.')) ?>€
            </span>
                                    </h6>
                                    <!-- Exibindo o tamanho -->
                                    <p style="color: #000;"><strong>Tamanho:</strong>
                                        <?= Html::encode($linha->tamanho ? $linha->tamanho->referencia : 'N/A') ?>
                                    </p>
                                    <p style="color: #000;"><strong>Quantidade:</strong>
                                        <span class="quantity-container">
                    <a href="<?= Url::to(['carrinhocompra/diminuir', 'id' => $linha->id]) ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-minus"></i>
                    </a>
                    <span class="quantity-display">
                        <?= Html::encode($linha->quantidade) ?>
                    </span>
                    <a href="<?= Url::to(['carrinhocompra/aumentar', 'id' => $linha->id]) ?>" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i>
                    </a>
                </span>
                                    </p>
                                    <p style="color: #000;"><strong>Subtotal:</strong> <?= Html::encode(number_format($linha->subtotal, 2, ',', '.')) ?>€</p>

                                </div>
                                <div class="p_cart">
                                    <?= Html::a('<i class="fa fa-trash"></i>', ['carrinhocompra/delete', 'id' => $linha->id], [
                                        'class' => 'btn btn-danger',
                                        'data' => [
                                            'confirm' => 'Tem certeza que deseja remover este produto do carrinho?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Coluna do Total do Carrinho -->
                <div class="col-md-4">
                    <div class="cart-total-section p-4" style="background-color: #fff; border: 1px solid #ddd; border-radius: 8px;">
                        <h3 style="border-bottom: 1px solid #ddd; padding-bottom: 10px; color: #000;">Total do Carrinho</h3>
                        <p style="color: #000;"><strong>Total de Produtos:</strong> <?= Html::encode(number_format($carrinho->valorTotal, 2, ',', '.')) ?>€</p>
                        <p style="color: #000;"><strong>Quantidade Total:</strong> <?= Html::encode($carrinho->quantidade) ?></p>
                        <div class="cart-actions">
                            <a href="<?= Url::to(['finalizarcompra/index', 'carrinho_id' => $carrinho->id]) ?>" class="btn btn-success w-100">Finalizar Compra</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


