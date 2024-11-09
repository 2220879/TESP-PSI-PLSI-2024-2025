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
                    ['label' => 'Produtos', 'header' => true],
                    [
                        'label' => 'Ver Produtos',
                        'items' => [
                            ['label' => 'Produtos', 'icon' => 'fa fa-book', 'url' => ['/produto/index']],
                            ['label' => 'Categorias', 'icon' => 'fa fa-inbox', 'url' => ['/categoria/index']],
                            ['label' => 'Marcas', 'icon' => 'fa fa-inbox', 'url' => ['/marca/index']],
                            ['label' => 'Géneros', 'icon' => 'fa fa-inbox', 'url' => ['/genero/index']],
                            ['label' => 'Ivas', 'icon' => 'fa fa-inbox', 'url' => ['/iva/index']],
                            ['label' => 'Tamanhos', 'icon' => 'fa fa-inbox', 'url' => ['/tamanho/index']],
                            ['label' => 'Avaliações', 'icon' => 'fa fa-star', 'url' => ['/avaliacoes/index']],
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