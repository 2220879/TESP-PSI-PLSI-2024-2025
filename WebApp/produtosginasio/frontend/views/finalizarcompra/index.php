<?php

use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Finalizar Compra';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="finalizar-compra container mt-4">
    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Exibir flash message de sucesso -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php elseif (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">

            <!-- Secção do Cupão -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #007bff; color: white;">
                    <h4 class="mb-0">Aplicar Cupão</h4>
                </div>
                <div class="card-body">
                    <?php if ($cupao && strtotime($cupao->dataFim) >= time()): ?>
                        <!-- Exibe o cupão válido -->
                        <p style="color: #28a745; font-weight: bold;">
                            <strong>Cupão Aplicado:</strong> <?= Html::encode($cupao->codigo) ?>
                        </p>
                    <?php else: ?>
                        <!-- Exibe o formulário de cupão -->
                        <?php $formCupao = ActiveForm::begin(['action' => ['finalizarcompra/index', 'carrinho_id' => $carrinho->id], 'method' => 'post']); ?>
                        <?= Html::textInput('cupao', '', ['placeholder' => 'Código do cupão', 'class' => 'form-control mb-2']) ?>
                        <button type="submit" class="btn btn-primary">Aplicar</button>
                        <?php ActiveForm::end(); ?>

                        <!-- Mensagem de erro, caso o cupão tenha expirado -->
                        <?php if ($cupao && strtotime($cupao->dataFim) < time()): ?>
                            <div class="alert alert-danger mt-2" role="alert">
                                <strong>Erro!</strong> Este cupão está expirado.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Secção do Método de Entrega -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #007bff; color: white;">
                    <h4 class="mb-0">Escolha o Método de Entrega</h4>
                </div>
                <div class="card-body">
                    <?php $formEntrega = ActiveForm::begin(['action' => ['finalizarcompra/index', 'carrinho_id' => $carrinho->id], 'method' => 'post']); ?>
                    <?= Html::hiddenInput('cupao', $cupao ? $cupao->codigo : null) ?>
                    <?= Html::dropDownList(
                        'metodo_entrega',
                        Yii::$app->request->post('metodo_entrega') ?? null,
                        \yii\helpers\ArrayHelper::map($metodosEntrega, 'id', 'descricao'),
                        [
                            'prompt' => 'Selecione um método de entrega',
                            'class' => 'form-control',
                        ]
                    ) ?>
                    <button type="submit" id="aplicar-metodo-entrega" class="btn btn-primary mt-2">Aplicar Método de
                        Entrega
                    </button>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <!-- Secção do Método de Pagamento -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #007bff; color: white;">
                    <h4 class="mb-0">Escolha o Método de Pagamento</h4>
                </div>
                <div class="card-body">
                    <?php $formPagamento = ActiveForm::begin(['action' => ['finalizarcompra/index', 'carrinho_id' => $carrinho->id], 'method' => 'post']); ?>
                    <?= Html::hiddenInput('cupao', $cupao ? $cupao->codigo : null) ?>
                    <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>

                    <?= Html::dropDownList('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null,
                        \yii\helpers\ArrayHelper::map($metodosPagamento, 'id', 'metodoPagamento'),
                        [
                            'prompt' => 'Selecione um método de pagamento',
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()'  // Envia o formulário quando a seleção mudar
                        ]
                    ) ?>

                    <?php
                    // Exibir os formulários de pagamento com base na escolha do método de pagamento
                    $metodoPagamentoSelecionado = Yii::$app->request->post('metodo_pagamento');
                    ?>

                    <?php if ($metodoPagamentoSelecionado == '1'): ?>
                        <!-- Formulário para PayPal -->
                        <div id="paypal-formulario" style="margin-top: 20px;">
                            <div class="form-group">
                                <label for="email_paypal">E-mail do PayPal:</label>
                                <input type="email" class="form-control" name="email_paypal"
                                       placeholder="Digite seu e-mail do PayPal" required>
                            </div>
                        </div>
                    <?php elseif ($metodoPagamentoSelecionado == '2'): ?>
                        <!-- Formulário para Cartão de Crédito -->
                        <div id="cartao-formulario" style="margin-top: 20px;">
                            <div class="form-group">
                                <label for="numero_cartao">Número do Cartão:</label>
                                <input type="text" class="form-control" name="numero_cartao"
                                       placeholder="Digite o número do cartão" required>
                            </div>
                            <div class="form-group">
                                <label for="data_validade">Data de Validade:</label>
                                <input type="month" class="form-control" name="data_validade" required>
                            </div>
                            <div class="form-group">
                                <label for="codigo_seguranca">Código de Segurança (CVV):</label>
                                <input type="text" class="form-control" name="codigo_seguranca"
                                       placeholder="Digite o código CVV" required>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <!-- Secção de Dados do Utilizador -->
            <?php
            // Iniciar sessão para armazenar dados do cliente
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Limpar dados da sessão ao carregar a página
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                unset($_SESSION['dados_cliente']);
            }

            // Obter o utilizador autenticado
            $user = Yii::$app->user->identity;
            $profile = \common\models\Profile::findOne(['user_id' => $user]);
            if (!$profile) {
                throw new NotFoundHttpException('Perfil não encontrado.');
            }

            // Valores padrão dos campos
            $email = $user->email ?? '';
            $nif = $profile->nif ?? ''; // Dados vindos do profile
            $morada = $profile->morada ?? ''; // Dados vindos do profile
            $telefone = $profile->telefone ?? ''; // Dados vindos do profile

            // Verificar se o formulário foi submetido e salvar na sessão
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
                $_SESSION['dados_cliente'] = [
                    'email' => $_POST['email'],
                    'nif' => $_POST['nif'],
                    'morada' => $_POST['morada'],
                    'telefone' => $_POST['telefone']
                ];
            }

            // Recuperar dados da sessão (se existentes)
            $dadosCliente = $_SESSION['dados_cliente'] ?? [
                'email' => $email,
                'nif' => $nif,
                'morada' => $morada,
                'telefone' => $telefone
            ];
            ?>
            <div class="finalizar-compra container mt-4">

                <!-- Exibir flash message de sucesso -->
                <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <?= Yii::$app->session->getFlash('success') ?>
                    </div>
                <?php endif; ?>

                <!-- Formulário para os dados do cliente -->
                <div class="finalizar-compra container mt-4">

                    <!-- Exibir flash message de sucesso -->
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success">
                            <?= Yii::$app->session->getFlash('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulário para os dados do cliente -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background-color: #007bff; color: white;">
                            <h4 class="mb-0">Dados do Cliente</h4>
                        </div>
                        <div class="card-body">
                            <?php $formEncomenda = ActiveForm::begin(['action' => '', 'method' => 'post']); ?>
                            <?= Html::hiddenInput('cupao', $cupao ? $cupao->codigo : null) ?>
                            <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>
                            <?= Html::hiddenInput('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null) ?>
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

                            <!-- Campo Email -->
                            <label>Email:</label>
                            <?= Html::input('text', 'email', $dadosCliente['email'], [
                                'class' => 'form-control',
                                'id' => 'email',
                                'placeholder' => 'Email',
                                'required' => true
                            ]) ?>

                            <!-- Campo NIF -->
                            <label>Nif:</label>
                            <?= Html::input('number', 'nif', $dadosCliente['nif'], [
                                'class' => 'form-control',
                                'id' => 'nif',
                                'placeholder' => 'Nif',
                                'required' => true
                            ]) ?>

                            <!-- Campo Morada -->
                            <label>Morada:</label>
                            <?= Html::input('text', 'morada', $dadosCliente['morada'], [
                                'class' => 'form-control',
                                'id' => 'morada',
                                'placeholder' => 'Morada',
                                'required' => true
                            ]) ?>

                            <!-- Campo Telefone -->
                            <label>Telefone:</label>
                            <?= Html::input('number', 'telefone', $dadosCliente['telefone'], [
                                'class' => 'form-control',
                                'id' => 'telefone',
                                'placeholder' => 'Telefone',
                                'required' => true
                            ]) ?>

                            <button type="submit" id="guardar-dados" class="btn btn-primary mt-2">Guardar Dados</button>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Resumo do Carrinho -->
            <div class="col-lg-4">
                <div class="card shadow-sm position-fixed"
                     style="bottom: 80px; right: 20px; width: 320px; max-height: calc(100vh - 120px); overflow-y: auto;">
                    <div class="card-header" style="background-color: #007bff; color: white;">
                        <h4 class="mb-0">Resumo do Carrinho</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Total de
                                Produtos:</strong> <?= Html::encode(number_format($valorProdutos, 2, ',', '.')) ?>€</p>
                        <p><strong>Desconto:</strong> <?= Html::encode(number_format($desconto, 2, ',', '.')) ?>%</p>
                        <p><strong>Custo de Envio:</strong> <?= Html::encode(number_format($custoEnvio, 2, ',', '.')) ?>
                            €</p>
                        <p><strong>Valor
                                Poupado:</strong> <?= Html::encode(number_format($ValorPoupado, 2, ',', '.')) ?>€</p>
                        <p class="h5"><strong>Total
                                Final:</strong> <?= Html::encode(number_format($valorFinal, 2, ',', '.')) ?>€</p>
                    </div>
                    <div class="card-footer">
                        <!-- Formulário de Finalização de Compra -->
                        <form action="<?= Url::to(['finalizarcompra/concluircompra', 'carrinho_id' => $carrinho->id]) ?>"
                              method="post">
                            <!-- CSRF Token para segurança -->
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

                            <!-- Campos ocultos para enviar os dados necessários -->
                            <input type="hidden" name="cupao"
                                   value="<?= Yii::$app->request->post('cupao') ?? ($cupao ? $cupao->codigo : null) ?>">
                            <input type="hidden" name="cliente"
                                   value="<?= Yii::$app->request->post('cliente') ?? null ?>">
                            <input type="hidden" name="metodo_entrega"
                                   value="<?= Yii::$app->request->post('metodo_entrega') ?? null ?>">
                            <input type="hidden" name="metodo_pagamento"
                                   value="<?= Yii::$app->request->post('metodo_pagamento') ?? null ?>">
                            <input type="hidden" name="email" value="<?= Yii::$app->request->post('email') ?? '' ?>">
                            <input type="hidden" name="nif" value="<?= Yii::$app->request->post('nif') ?? '' ?>">
                            <input type="hidden" name="morada" value="<?= Yii::$app->request->post('morada') ?? '' ?>">
                            <input type="hidden" name="telefone"
                                   value="<?= Yii::$app->request->post('telefone') ?? '' ?>">

                            <!-- Botão para concluir a compra -->
                            <button type="submit" id="confirmar-compra" class="btn btn-success w-100">Confirmar Compra
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




