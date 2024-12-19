<?php

use common\models\Produto;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var $produtos */

$this->title = 'Realizar Venda';
// Data provider para o carrinho
$dataProvider = new ArrayDataProvider([
    'allModels' => $carrinho,
    'pagination' => ['pageSize' => 10,
    ],
    'sort' => [
        'attributes' => ['nomeProduto', 'tamanho', 'quantidade', 'preco'],
    ],
]);
?>

<?php
foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
    echo '<div class="alert alert-' . $type . '">' . $message . '</div>';
}
?>

<script>
    const quantidadesMaximas = {};

    function atualizaQuantidade(pid) {
        const tamanhoReferenciaElement = document.getElementById('tamanho_referencia_' + pid);
        const quantidadeSelect = document.getElementById('quantidade_' + pid);
        const adicionarButton = document.getElementById('adicionar_button_' + pid);

        if (!tamanhoReferenciaElement || !quantidadeSelect) {
            console.error(`Elementos com id tamanho_referencia_${pid} ou quantidade_${pid} não encontrados.`);
            return;
        }

        const tamanhoReferencia = tamanhoReferenciaElement.value;

        if (!quantidadesMaximas[pid]) {
            console.error(`quantidadesMaximas[${pid}] não está definido.`);
            return;
        }

        const quantidadeMax = quantidadesMaximas[pid][tamanhoReferencia] || 0;

        quantidadeSelect.innerHTML = '';

        if (quantidadeMax > 0) {
            adicionarButton.disabled = false;
            for (let i = 1; i <= quantidadeMax; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = i;
                quantidadeSelect.appendChild(option);
            }
        } else {
            adicionarButton.disabled = true;
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        <?php foreach ($produtos as $produto): ?>
        quantidadesMaximas[<?= $produto->id ?>] = {};
        <?php foreach ($produto->produtosHasTamanhos as $produtosHasTamanho): ?>
        quantidadesMaximas[<?= $produto->id ?>]["<?= $produtosHasTamanho->tamanho->referencia ?>"] = <?= $produtosHasTamanho->quantidade ?>;
        <?php endforeach; ?>

        setTimeout(function () {
            if (document.getElementById('tamanho_referencia_<?= $produto->id ?>') !== null) {
                atualizaQuantidade(<?= $produto->id ?>);

                document.getElementById('tamanho_referencia_<?= $produto->id ?>').addEventListener('change', function () {
                    atualizaQuantidade(<?= $produto->id ?>);
                });
            }
        }, 0);
        <?php endforeach; ?>
    });
</script>

<div class="realizarvenda-index">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="product_container">
                    <div class="row"> <?php foreach ($produtos as $produto): ?>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="box">
                                    <div class="img-box"> <?php foreach ($produto->imagens as $imagem): ?>
                                            <div class="image-container"> <?= Html::img(Yii::getAlias('@web/uploads/') . $imagem->filename, ['class' => 'product-image', 'style' => 'width: 200px; height: 200px;']) ?> </div> <?php endforeach; ?>
                                    </div>
                                    <div class="detail-box"><p><?= Html::encode($produto->nomeProduto) ?></p> <h6
                                                class="p_price"><span
                                                    class="new_price"><?= Html::encode(number_format($produto->preco, 2, ',', '.')) ?>€</span>
                                        </h6></div> <?php if (!empty($produto->produtosHasTamanhos)): ?>
                                        <hr> <?php $quantidadesMaximas = [];
                                        foreach ($produto->produtosHasTamanhos as $produtosHasTamanho) {
                                            $quantidadesMaximas[$produtosHasTamanho->tamanho->referencia] = $produtosHasTamanho->quantidade;
                                        }
                                        $selectedTamanho = $_GET['tamanho_referencia'] ?? $produto->produtosHasTamanhos[0]->tamanho->referencia;
                                        $quantidadeMax = $quantidadesMax[$selectedTamanho] ?? 0; ?>
                                        <form action="<?= Url::to(['realizarvenda/adicionarproduto']) ?>" method="GET">
                                            <label for="tamanho_referencia">Tamanho:</label>
                                            <select name="tamanho_referencia"
                                                    id="tamanho_referencia_<?= $produto->id ?>"
                                                    onchange="atualizaQuantidade(<?= $produto->id ?>)">
                                                <?php foreach ($produto->produtosHasTamanhos as $produtosHasTamanho): ?>
                                                    <option value="<?= $produtosHasTamanho->tamanho->referencia ?>" <?= $selectedTamanho == $produtosHasTamanho->tamanho->referencia ? 'selected' : '' ?>> <?= Html::encode($produtosHasTamanho->tamanho->referencia) ?> </option> <?php endforeach; ?>
                                            </select> <label for="quantidade">Quantidade:</label>
                                            <select name="quantidade" id="quantidade_<?= $produto->id ?>">
                                                <!-- A quantidade do produto vai ser apresentada consoante -->
                                            </select>
                                            <input type="hidden" name="produto_id" value="<?= $produto->id ?>"/>
                                            <button type="submit" id="adicionar_button_<?= $produto->id ?>"
                                                    class="btn btn-success"> Adicionar
                                            </button>
                                        </form> <?php else: ?>
                                        <hr>
                                        <form action="<?= Url::to(['realizarvenda/adicionarproduto']) ?>" method="GET">
                                            <input type="hidden" name="produto_id" value="<?= $produto->id ?>"/>
                                            <select name="quantidade" id="quantidade_<?= $produto->id ?>">
                                                <?php
                                                $min = 1;
                                                $max = $produto->quantidade;

                                                //gerar as opções dentro do intervalo
                                                for ($i = $min; $i <= $max; $i++) {
                                                    echo "<option value='$i'>$i</option>";
                                                }
                                                ?>
                                            </select>
                                            <button type="submit"
                                                    class="btn btn-success" <?= ($produto->quantidade == 0) ? 'disabled' : '' ?>>
                                                Adicionar
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Resumo do Carrinho</h4>
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'nomeProduto',
                            'tamanho',
                            [
                                'attribute' => 'quantidade',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::beginForm(['realizarvenda/editarquantidade'], 'post') .
                                        Html::hiddenInput('id', $model['id']) .
                                        Html::hiddenInput('tamanho', $model['tamanho']) .
                                        Html::input('number', 'quantidade', $model['quantidade'], ['min' => 1]) .
                                        Html::submitButton('Atualizar', ['class' => 'btn btn-primary']) .
                                        Html::endForm();
                                },
                            ],
                            [
                                'attribute' => 'preco',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return number_format($model['preco'], 2, ',', '.') . ' €';
                                },
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{delete}',
                                'buttons' => [
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa fa-trash"></i>', Url::to(['realizarvenda/removerproduto', 'id' => $model['id'], 'tamanho' => $model['tamanho']]), [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data-confirm' => 'Tem certeza que deseja remover este produto?',
                                            'data-method' => 'post',
                                        ]);
                                    },
                                ],
                            ],
                        ],
                    ]); ?>
                </div>
                <?php if (!empty($carrinho)): ?>
                    <?= Html::a('Finalizar Compra', ['compra'], [
                        'class' => 'btn btn-success',
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>