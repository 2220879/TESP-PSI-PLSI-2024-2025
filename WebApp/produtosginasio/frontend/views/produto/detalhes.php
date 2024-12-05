<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */

$this->title = $model->nomeProduto;
\yii\web\YiiAsset::register($this);
?>
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
                            <?= Html::img('../../../backend/web/uploads/' . $imagem->filename, ['class' => 'd-block w-100', 'style' => 'height: 300px;']) ?>
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
            <p><label>Preço: <?= Html::encode(number_format($model->preco, 2, ',', '')) ?> €</label></p>
            <p><label>Marca: <?= Html::encode($model->marca->nomeMarca) ?></label></p>
            <p><label>Categoria: <?= Html::encode($model->categoria->nomeCategoria) ?></label></p>
            <p><label>Iva: <?= Html::encode($model->iva->percentagem * 100) ?>%</label></p>
            <p><label>Género: <?= Html::encode($model->genero->referencia) ?></label></p>
            <?php foreach ($model->tamanhos as $tamanho): ?>
                <hr>
                <p>Tamanhos disponíveis: </p>
                <p>
                    <button><?= Html::encode($tamanho->referencia) ?></button>
                </p>
            <?php endforeach; ?>

            <a href="<?= Url::to(['carrinhocompra/create', 'produto_id' => $model->id]) ?>"class="ms-3">
                <i class="fa fa-cart-plus" aria-hidden="true" style="color: white;"></i>
            </a>
            <a href="<?= Url::to(['favorito/create', 'produto_id' => $model->id]) ?>" class="ms-3">
                <i class="fa fa-heart" aria-hidden="true" style="color: white;"></i>
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

        <?php
        if (!Yii::$app->user->isGuest) {
            $form = ActiveForm::begin([
                'action' => ['avaliacao/create', 'id' => $model->id],
                'method' => 'post',
            ]);
            echo $form->field($avaliacao, 'descricao')->textarea()->label('Adicionar Avaliação');
            echo '<div class="form-group">';
            echo Html::submitButton('Submeter', ['class' => 'btn btn-primary', 'name' => 'submit-button']);
            echo '</div>';
            ActiveForm::end();
        }
        ?>

        <hr>

        <?php if (empty($avaliacoes)): ?>
            <p>Ainda não existem avaliações.</p>
        <?php else: ?>
            <?php foreach ($avaliacoes as $avaliacao): ?>
                <div class="avaliacoes">
                    <p><?= Html::encode($avaliacao->descricao) ?></p>
                    <?php if (Yii::$app->user->id == $avaliacao->profile->user->id): ?>
                        <?= Html::a('Editar', ['avaliacao/update', 'id' => $avaliacao->id], [
                            'class' => 'btn btn-info',
                        ]) ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</div>
