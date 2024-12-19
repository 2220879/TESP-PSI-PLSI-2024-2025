<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::home() ?>" class="brand-link">
        <img src="<?= $assetDir ?>/../../../../frontend/web/images/Icon_Projeto.png" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Produtos Ginásio</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'Yii2 PROVIDED', 'header' => true],
                    ['label' => 'Gii', 'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Gestão de Utilizadores', 'icon' => '', 'header' => true],
                    ['label' => 'Utilizadores', 'icon' => 'fa fa-users', 'url' => ['/user/index']],
                    ['label' => 'Métodos de Entrega', 'icon' => '', 'header' => true],
                    ['label' => 'Métodos de Entrega', 'icon' => 'fa fa-truck', 'url' => ['/metodoentrega/index']],
                    ['label' => 'Métodos de Pagamento', 'icon' => '', 'header' => true],
                    ['label' => 'Métodos de Pagamento', 'icon' => 'fa fa-credit-card', 'url' => ['/metodopagamento/index']],
                    ['label' => 'Encomendas', 'header' => true],
                    [
                        'label' => 'Encomendas',
                        'items' => [
                            ['label' => 'Encomendas', 'icon' => 'fa-solid fa-boxes', 'url' => ['/encomenda/index']],
                        ],
                    ],
                    ['label' => 'Vendas', 'header' => true],
                    [
                        'label' => 'Vendas',
                        'items' => [
                            ['label' => 'Realizar Venda', 'icon' => 'fa fa-cart-plus', 'url' => ['/realizarvenda/index']],
                            ['label' => 'Ver Vendas', 'icon' => 'fa-solid fa-euro-sign', 'url' => ['/venda/index']],
                        ],
                    ],
                    ['label' => 'Mercadoria', 'header' => true],
                    [
                        'label' => 'Mercadoria',
                        'items' => [
                            ['label' => 'Fornecedores', 'icon' => 'fa fa-truck', 'url' => ['/fornecedor/index']],
                            ['label' => 'Compras', 'icon' => 'fa fa-book', 'url' => ['/compra/index']],
                        ],
                    ],
                    ['label' => 'Gestão', 'header' => true],
                    [
                        'label' => 'Gestão',
                        'items' => [
                            ['label' => 'Produtos', 'icon' => 'fa fa-book', 'url' => ['/produto/index']],
                            ['label' => 'Categorias', 'icon' => 'fa fa-inbox', 'url' => ['/categoria/index']],
                            ['label' => 'Marcas', 'icon' => 'fa fa-inbox', 'url' => ['/marca/index']],
                            ['label' => 'Géneros', 'icon' => 'fa fa-inbox', 'url' => ['/genero/index']],
                            ['label' => 'Ivas', 'icon' => 'fa fa-inbox', 'url' => ['/iva/index']],
                            ['label' => 'Tamanhos', 'icon' => 'fa fa-inbox', 'url' => ['/tamanho/index']],
                            ['label' => 'CupaoDesconto', 'icon' => 'fa fa-tag', 'url' => ['/cupaodesconto/index']],
                        ],
                    ],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>