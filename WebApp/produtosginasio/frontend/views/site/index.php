<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Produtos de Ginásio';
?>
<div class="slider_section">
    <?php
    if ($cupoes) {
        $first = true;
        ?>
        <div id="customCarousel1" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($cupoes as $cupao): ?>
                    <div class="carousel-item <?= $first ? 'active' : '' ?>">
                        <!-- Marca o primeiro cupão como 'active' -->
                        <div class="container">
                            <div class="row">
                                <div class="col-md-8 mx-auto">
                                    <div class="detail_box">
                                        <h1>
                                            Aproveite <br/>
                                            Desconto - <?= $cupao->desconto ?>%
                                        </h1>
                                        <p>
                                            Código de desconto para aplicar numa compra:
                                            <b><?= $cupao->codigo ?></b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </div>

            <div class="carousel_btn-box">
                <a class="carousel-control-prev" href="#customCarousel1" role="button" data-slide="prev">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    <?php } ?>
</div>
<!-- end slider section -->

<!-- product section -->

<div class="product_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Novos Produtos
            </h2>
        </div>
        <div class="product_container">
            <div class="row">
                <?php foreach ($produtosRecentes as $produtoRecente): ?>
                    <div class="col-sm-6 col-md-4 ">
                        <div class="box">
                            <div class="img-box">
                                <?php if (!empty($produtoRecente->imagens) && isset($produtoRecente->imagens[0])): ?>
                                    <img src="<?= htmlspecialchars("./../../backend/web/uploads/" . $produtoRecente->imagens[0]->filename) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="detail-box">
                                <a href="<?= Url::to(['produto/detalhes', 'id' => $produtoRecente->id]) ?>"
                                   class="p_name">
                                    <?= Html::Encode($produtoRecente->nomeProduto) ?>
                                </a>
                                <h6 class="p_price">
                              <span class="new_price">
                                <?= number_format($produtoRecente->preco, 2, ',', '.') ?>€
                              </span>
                                </h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- end product section -->

<!-- about section -->

<div class="about_section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 ml-auto">
                <div class="detail-box">
                    <div class="heading_container ">
                        <h2>
                            Sobre os Produtos Ginásio
                        </h2>
                    </div>
                    <p>
                        Este sistema é uma plataforma que permite a venda de produtos de ginásio, como roupa, artigos de
                        desporto, entre outros...
                    <hr>
                    O sistema foi desenvolvido no âmbito do projeto final de curso de TeSP em Programação de Sistemas de
                    Informação, que abrange as unidades curriculares de Plataformas de Sistemas de Informação, Acesso
                    Móvel a Sistemas de Informação e Serviços e Interoperabilidade de Sistemas.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="img-box" style="display: flex; justify-content: center; align-items: center; height: 100%;">
                    <img src="images/Icon_Projeto.png" alt="Ícone do Projeto" style="width: 40%; height: auto;"/>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end about section -->

<!-- why us section -->
<div class="why_us_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Porquê escolher-nos?
            </h2>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="box">
                    <div class="detail-box">
                        <h5>
                            Principais Formas de Pagamento
                        </h5>
                        <hr>
                        <p>
                            <i class="fa fa-paypal"></i> Paypal
                            <br><br>
                            <i class="fa fa-credit-card"></i> Cartão de Crédito
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="box">
                    <div class="detail-box">
                        <h5>
                            Métodos de Entrega
                        </h5>
                        <hr>
                        <p>
                            <?php foreach ($metodosentrega as $metodo): ?>
                                <?= $metodo->descricao ?><br>
                            <?php endforeach; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end why us section -->


<!-- client section -->
<div class="client_section layout_padding">
    <div class="container">
        <div class="heading_container heading_center">
            <h2>
                Opiniões em alguns dos nossos Produtos
            </h2>
        </div>
        <div id="carouselExample2Indicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $isFirst = true; //variável para determinar o item ativo
                foreach ($avaliacoes as $avaliacao):
                    $activeClass = $isFirst ? 'active' : '';
                    $isFirst = false;
                    ?>
                    <div class="carousel-item <?= $activeClass ?>">
                        <div class="row">
                            <div class="col-md-7 mx-auto">
                                <div class="client_container">
                                    <div class="client_detail">
                                        <p>
                                            <i class="fa fa-quote-left" aria-hidden="true"></i> &nbsp;
                                            <?= Html::encode($avaliacao->descricao) ?>
                                            <!-- Exibe o comentário da avaliação -->
                                            <i class="fa fa-quote-right" aria-hidden="true"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <ol class="carousel-indicators">
                <?php
                $index = 0;
                foreach ($avaliacoes as $avaliacao):
                    ?>
                    <li data-target="#carouselExample2Indicators" data-slide-to="<?= $index ?>"
                        class="<?= $index == 0 ? 'active' : '' ?>"></li>
                    <?php
                    $index++;
                endforeach;
                ?>
            </ol>
        </div>
    </div>
</div>
<!-- end client section -->


<!-- info section -->

<div class="info_section layout_padding2">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">
                <h4>
                    Contactos
                </h4>
                <div class="contact_items">
                    <a href="">
                        <div class="img-box">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                        </div>
                        <h6>
                            Leiria
                        </h6>
                    </a>
                    <a href="">
                        <div class="img-box">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </div>
                        <h6>
                            produtosginasio@gmail.com
                        </h6>
                    </a>
                    <a href="">
                        <div class="img-box">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                        </div>
                        <h6>
                            (+351) 912345678
                        </h6>
                    </a>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="info_social">
                <div>
                    <a href="">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                    </a>
                </div>
                <div>
                    <a href="">
                        <i class="fa fa-twitter" aria-hidden="true"></i>
                    </a>
                </div>
                <div>
                    <a href="">
                        <i class="fa fa-linkedin" aria-hidden="true"></i>
                    </a>
                </div>
                <div>
                    <a href="">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>