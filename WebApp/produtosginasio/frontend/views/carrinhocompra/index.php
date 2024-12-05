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
                    <div class="product_container">
                        <div class="row">
                            <?php foreach ($linhasCarrinho as $linha): ?>
                                <div class="col-sm-12">
                                    <div class="box">
                                        <!-- <a href="#" class="p_cart">
                                            <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                        </a>-->
                                        <div class="p_cart">
                                            <!-- Botão para remover produto do carrinho -->
                                            <?= Html::a('<i class="fa fa-trash"></i>', ['carrinhocompra/delete', 'id' => $linha->id], [
                                                'class' => 'btn btn-danger',
                                                'data' => [
                                                    'confirm' => 'Tem certeza que deseja remover este produto do carrinho?',
                                                    'method' => 'post',
                                                ],
                                            ]) ?>
                                        </div>
                                        <div class="img-box">
                                            <?php if (!empty($linha->produto->imagens) && isset($linha->produto->imagens[0])): ?>
                                                <img src="<?= htmlspecialchars("../../../backend/web/uploads/" . $linha->produto->imagens[0]->filename) ?>"
                                                     alt="Imagem do Produto">
                                            <?php endif; ?>
                                        </div>
                                        <div class="detail-box">
                                            <a href="<?= Url::to(['produto/detalhes', 'id' => $linha->produto->id]) ?>"
                                               class="p_name">
                                                <?= Html::encode($linha->produto->nomeProduto) ?>
                                            </a>
                                            <h6 class="p_price">
                                            <span class="new_price">
                                                <?= Html::encode(number_format($linha->precoUnit, 2, ',', '.')) ?>€
                                            </span>
                                            </h6>
                                            <p><strong>Quantidade:</strong>
                                                <span class="quantity-container">
                                                    <!-- Botão para diminuir a quantidade -->
                                                    <a href="<?= Url::to(['carrinhocompra/diminuir', 'id' => $linha->id]) ?>" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-minus"></i>
                                                    </a>
                                                    <!-- Exibe a quantidade -->
                                                    <span class="quantity-display">
                                                        <?= Html::encode($linha->quantidade) ?>
                                                    </span>
                                                    <!-- Botão para aumentar a quantidade -->
                                                    <a href="<?= Url::to(['carrinhocompra/aumentar', 'id' => $linha->id]) ?>" class="btn btn-success btn-sm">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </span>
                                            </p>

                                            <p><strong>Subtotal:</strong> <?= Html::encode(number_format($linha->subtotal, 2, ',', '.')) ?>€</p>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Coluna do Total do Carrinho -->
                <div class="col-md-4">
                    <div class="cart-total-section">
                        <h3>Total do Carrinho</h3>
                        <p><strong>Total de Produtos:</strong> <?= Html::encode(number_format($carrinho->valorTotal, 2, ',', '.')) ?>€</p>
                        <p><strong>Quantidade Total:</strong> <?= Html::encode($carrinho->quantidade) ?></p>
                        <div class="cart-actions">
                            <!-- Botões de finalização do carrinho -->
                            <a href="<?= Url::to(['pedido/criar', 'carrinho_id' => $carrinho->id]) ?>" class="btn btn-success">Finalizar Compra</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>
