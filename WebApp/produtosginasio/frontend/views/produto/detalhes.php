<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Produto $model */
/** @var common\models\Avaliacao $avaliacao */
/** @var common\models\Tamanho[] $tamanhos */
/** @var common\models\Imagem[] $imagens */
/** @var common\models\Avaliacao[] $avaliacoes */

$this->title = $model->nomeProduto;
\yii\web\YiiAsset::register($this);
?>
<div class="container">

    <!-- Mensagens de alerta -->
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

            <!-- Tamanhos e Quantidades -->
            <div class="tamanhos-container">
                <p><strong>Escolha o tamanho e a quantidade:</strong></p>
                <div class="row">
                    <?php foreach ($tamanhos as $produtoHasTamanho): ?>
                        <div class="col-3 text-center">
                            <button
                                    class="tamanho-button <?= $produtoHasTamanho->quantidade > 0 ? '' : 'disabled' ?>"
                                    data-tamanho-id="<?= $produtoHasTamanho->tamanho_id ?>"
                                    style="width: 60px; height: 60px; margin-bottom: 10px; border: 2px solid white; background-color: transparent; color: white;"
                                <?= $produtoHasTamanho->quantidade > 0 ? '' : 'disabled' ?>>
                                <?= Html::encode($produtoHasTamanho->tamanho->referencia) ?>
                            </button>
                            <input
                                    type="number"
                                    class="form-control quantidade-input"
                                    id="quantidade-<?= $produtoHasTamanho->tamanho_id ?>"
                                    data-tamanho-id="<?= $produtoHasTamanho->tamanho_id ?>"
                                    min="0"
                                    max="<?= $produtoHasTamanho->quantidade ?>"
                                    style="display: none;"
                                    disabled>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Botão para Adicionar ao Carrinho -->
            <a href="#" id="adicionar-carrinho" class="ms-3">
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

        <?php if (!Yii::$app->user->isGuest): ?>
            <?php $form = ActiveForm::begin([
                'action' => ['avaliacao/create', 'id' => $model->id],
                'method' => 'post',
            ]); ?>
            <?= $form->field($avaliacao, 'descricao')->textarea()->label('Adicionar Avaliação') ?>
            <div class="form-group">
                <?= Html::submitButton('Submeter', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>

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

<script>
    document.querySelectorAll('.tamanho-button').forEach(button => {
        button.addEventListener('click', function () {
            const tamanhoId = this.dataset.tamanhoId;

            // Remover estilo ativo de todos os botões
            document.querySelectorAll('.tamanho-button').forEach(b => {
                b.style.border = "2px solid white"; // Borda branca
                b.style.backgroundColor = "transparent"; // Fundo transparente
                b.style.color = "white"; // Texto branco
            });

            // Adicionar estilo ativo no botão clicado
            this.style.border = "2px solid #007bff"; // Borda azul
            this.style.backgroundColor = "#007bff"; // Fundo azul
            this.style.color = "white"; // Texto branco

            // Desabilitar e esconder todos os inputs de quantidade
            document.querySelectorAll('.quantidade-input').forEach(input => {
                input.disabled = true;
                input.style.display = 'none';
            });

            // Habilitar e mostrar o campo de quantidade correspondente
            const quantidadeInput = document.getElementById('quantidade-' + tamanhoId);
            quantidadeInput.style.display = 'block';
            quantidadeInput.disabled = false;

            // Definir valor padrão e limite máximo
            quantidadeInput.max = quantidadeInput.getAttribute('max');
            quantidadeInput.value = quantidadeInput.max > 0 ? 1 : 0;
        });
    });

    document.getElementById('adicionar-carrinho').addEventListener('click', function() {
        const quantidadeInput = document.querySelector('.quantidade-input:enabled'); // Buscar o input habilitado (somente o que corresponde ao tamanho selecionado)

        if (!quantidadeInput) {
            alert('Selecione um tamanho antes de adicionar ao carrinho.');
            return;
        }

        const tamanhoId = quantidadeInput.dataset.tamanhoId; // Tamanho ID
        const quantidadeEscolhida = quantidadeInput.value; // Quantidade escolhida

        if (quantidadeEscolhida <= 0) {
            alert('Escolha uma quantidade válida.');
            return;
        }

        // Construir a URL para o controlador Carrinhocompra -> actionCreate
        const url = '<?= Url::to(["carrinhocompra/create"]) ?>?produto_id=<?= $model->id ?>&tamanho_id=' + tamanhoId + '&quantidade=' + quantidadeEscolhida;

        // Redirecionar para a URL gerada
        window.location.href = url;
    });
</script>

