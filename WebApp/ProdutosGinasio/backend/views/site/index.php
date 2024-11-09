<?php
$this->title = 'Início';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Vendas',
                'icon' => 'fas fa-shopping-cart',
                'theme' => 'success'
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?php $smallBox = \hail812\adminlte\widgets\InfoBox::begin([
                'text' => 'Cupões de desconto',
                'icon' => 'fa fa-tag',
                'theme' => 'info'
            ]) ?>
            <?php \hail812\adminlte\widgets\InfoBox::end() ?>
        </div>
    </div>
</div>