<?php

use common\models\Produto;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ProdutoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product_section layout_padding">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['filtrar'],
            'options' => ['class' => 'form-horizontal'],
        ]); ?>

        <div class="form-group">
            <label for="pesquisar" class="col-sm-2 control-label">Pesquisar:</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <?= Html::textInput('pesquisar', isset($_GET['pesquisar']) ? $_GET['pesquisar'] : '', [
                        'id' => 'pesquisar',
                        'class' => 'form-control input-lg', // Barra de pesquisa maior
                        'placeholder' => 'Nome do Produto',
                    ]) ?>
                    <span class="input-group-btn">
                    <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-primary ml-2']) ?>
                </span>
                </div>
            </div>
        </div>

        <hr>

        <div class="form-group">
            <label class="col-sm-2 control-label">Filtrar Produtos:</label>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="categoria" class="control-label">Categoria:</label>
                <select name="categoria" id="categoria" class="form-control">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria->id ?>"
                            <?= isset($categoriaSelecionada) && $categoriaSelecionada == $categoria->id ? 'selected' : '' ?>>
                            <?= $categoria->nomeCategoria ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="marca" class="control-label">Marca:</label>
                <select name="marca" id="marca" class="form-control">
                    <option value="">Selecione uma marca</option>
                    <?php foreach ($marcas as $marca): ?>
                        <option value="<?= $marca->id ?>"
                            <?= isset($marcaSelecionada) && $marcaSelecionada == $marca->id ? 'selected' : '' ?>>
                            <?= $marca->nomeMarca ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="genero" class="control-label">Género:</label>
                <select name="genero" id="genero" class="form-control">
                    <option value="">Selecione o género</option>
                    <?php foreach ($generos as $genero): ?>
                        <option value="<?= $genero->id ?>"
                            <?= isset($generoSelecionado) && $generoSelecionado == $genero->id ? 'selected' : '' ?>>
                            <?= $genero->referencia ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <?= Html::submitButton('Filtrar', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <?php if (empty($produtos)): ?>
            <p>Sem produtos disponíveis</p>
        <?php else: ?>
            <div class="product_container">
                <div class="row">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="col-sm-6 col-md-4">
                            <div class="box">
                                <a href="#" class="p_cart">
                                    <i class="fa fa-cart-plus" aria-hidden="true"></i>
                                </a>
                                <div class="img-box">
                                    <?php if (!empty($produto->imagens) && isset($produto->imagens[0])): ?>
                                        <img src="<?= htmlspecialchars("../../../backend/web/uploads/" . $produto->imagens[0]->filename) ?>"
                                             alt="Imagem do Produto">
                                    <?php endif; ?>
                                </div>
                                <div class="detail-box">
                                    <a href="<?= Url::to(['produto/detalhes', 'id' => $produto->id]) ?>"
                                       class="p_name">
                                        <?= Html::encode($produto->nomeProduto) ?>
                                    </a>
                                    <h6 class="p_price">
                                    <span class="new_price">
                                        <?= html::encode(number_format($produto->preco, 2, ',', '.')) ?>€
                                    </span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
