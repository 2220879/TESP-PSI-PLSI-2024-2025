<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Finalizar Compra';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="finalizar-compra container mt-4">
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
                        <!-- Mostrar o formulário de cupão -->
                        <?php $formCupao = ActiveForm::begin(['action' => ['compra'], 'method' => 'post']); ?>
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <?= Html::hiddenInput('cupao', Yii::$app->request->post('cupao') ?? ($cupao ? $cupao->codigo : null)) ?>
                        <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>
                        <?= Html::hiddenInput('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null) ?>
                        <?= Html::hiddenInput('cliente', Yii::$app->request->post('cliente') ?? null) ?>
                        <?= Html::hiddenInput('email', Yii::$app->request->post('email') ?? '') ?>
                        <?= Html::hiddenInput('nif', Yii::$app->request->post('nif') ?? '') ?>
                        <?= Html::hiddenInput('morada', Yii::$app->request->post('morada') ?? '') ?>
                        <?= Html::hiddenInput('telefone', Yii::$app->request->post('telefone') ?? '') ?>
                        <?= Html::textInput('cupao', '', ['placeholder' => 'Código do cupão', 'class' => 'form-control mb-2', 'id' => 'cupao']) ?>
                        <?= Html::dropDownList('cliente', null,
                            \yii\helpers\ArrayHelper::map($clientes, 'id', 'username'),
                            [
                                'prompt' => 'Selecione um cliente',
                                'class' => 'form-control',
                            ]
                        ) ?>
                        <button type="submit" class="btn btn-primary">Aplicar</button>
                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Secção do Método de Entrega -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #007bff; color: white;">
                    <h4 class="mb-0">Escolha o Método de Entrega</h4>
                </div>
                <div class="card-body">
                    <?php $formEntrega = ActiveForm::begin(['action' => ['compra'], 'method' => 'post']); ?>
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    <?= Html::hiddenInput('cupao', $cupao ? $cupao->codigo : null) ?>
                    <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>
                    <?= Html::hiddenInput('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null) ?>
                    <?= Html::hiddenInput('cliente', Yii::$app->request->post('cliente') ?? null) ?>
                    <?= Html::hiddenInput('email', Yii::$app->request->post('email') ?? '') ?>
                    <?= Html::hiddenInput('nif', Yii::$app->request->post('nif') ?? '') ?>
                    <?= Html::hiddenInput('morada', Yii::$app->request->post('morada') ?? '') ?>
                    <?= Html::hiddenInput('telefone', Yii::$app->request->post('telefone') ?? '') ?>
                    <?= Html::dropDownList(
                        'metodo_entrega',
                        Yii::$app->request->post('metodo_entrega') ?? null,
                        \yii\helpers\ArrayHelper::map($metodosEntrega, 'id', 'descricao'),
                        [
                            'prompt' => 'Selecione um método de entrega',
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                            'required' => true
                        ]
                    ) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <!-- Secção do Método de Pagamento -->
            <div class="card shadow-sm mb-4">
                <div class="card-header" style="background-color: #007bff; color: white;">
                    <h4 class="mb-0">Escolha o Método de Pagamento</h4>
                </div>
                <div class="card-body">
                    <?php $formPagamento = ActiveForm::begin(['action' => ['compra'], 'method' => 'post']); ?>
                    <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                    <?= Html::hiddenInput('cupao', Yii::$app->request->post('cupao') ?? ($cupao ? $cupao->codigo : null)) ?>
                    <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>
                    <?= Html::hiddenInput('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null) ?>
                    <?= Html::hiddenInput('cliente', Yii::$app->request->post('cliente') ?? null) ?>
                    <?= Html::hiddenInput('email', Yii::$app->request->post('email') ?? '') ?>
                    <?= Html::hiddenInput('nif', Yii::$app->request->post('nif') ?? '') ?>
                    <?= Html::hiddenInput('morada', Yii::$app->request->post('morada') ?? '') ?>
                    <?= Html::hiddenInput('telefone', Yii::$app->request->post('telefone') ?? '') ?>

                    <?= Html::dropDownList('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null,
                        \yii\helpers\ArrayHelper::map($metodosPagamento, 'id', 'metodoPagamento'),
                        [
                            'prompt' => 'Selecione um método de pagamento',
                            'class' => 'form-control',
                            'onchange' => 'this.form.submit()',
                            'required' => true
                        ]
                    ) ?>

                    <?php
                    $metodoPagamentoSelecionado = Yii::$app->request->post('metodo_pagamento');
                    ?>

                    <?php if ($metodoPagamentoSelecionado == '1'): ?>
                        <!-- Formulário para PayPal -->
                        <div id="paypal-formulario" style="margin-top: 20px;">
                            <div class="form-group">
                                <label for="email_paypal">E-mail do PayPal:</label>
                                <?= Html::input('email', 'email_paypal', Yii::$app->request->post('email_paypal', ''), [
                                    'class' => 'form-control',
                                    'id' => 'email_paypal',
                                    'placeholder' => 'email_paypal',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                    <?php elseif ($metodoPagamentoSelecionado == '2'): ?>
                        <!-- Formulário para Cartão de Crédito -->
                        <div id="cartao-formulario" style="margin-top: 20px;">
                            <div class="form-group">
                                <label for="numero_cartao">Número do Cartão:</label>
                                <?= Html::input('text', 'numero_cartao', Yii::$app->request->post('numero_cartao', ''), [
                                    'class' => 'form-control',
                                    'id' => 'numero_cartao',
                                    'placeholder' => 'numero_cartao',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="form-group">
                                <label for="data_validade">Data de Validade:</label>
                                <?= Html::input('month', 'data_validade', Yii::$app->request->post('data_validade', ''), [
                                    'class' => 'form-control',
                                    'id' => 'data_validade',
                                    'placeholder' => 'data_validade',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="form-group">
                                <label for="codigo_seguranca">Código de Segurança (CVV):</label>
                                <?= Html::input('text', 'codigo_seguranca', Yii::$app->request->post('codigo_seguranca', ''), [
                                    'class' => 'form-control',
                                    'id' => 'codigo_seguranca',
                                    'placeholder' => 'codigo_seguranca',
                                    'required' => true
                                ]) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <!-- Secção de seleção cliente -->
            <div class="card shadow-sm mb-4">
                <!-- Secção de seleção cliente -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #007bff; color: white;">
                        <h4 class="mb-0">Selecione o Cliente</h4>
                    </div>
                    <div class="card-body">
                        <?php $formCliente = ActiveForm::begin(['action' => ['compra'], 'method' => 'post']); ?>
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <?= Html::hiddenInput('cupao', Yii::$app->request->post('cupao') ?? ($cupao ? $cupao->codigo : null)) ?>
                        <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>
                        <?= Html::hiddenInput('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null) ?>
                        <?= Html::hiddenInput('cliente', Yii::$app->request->post('cliente') ?? null) ?>
                        <?= Html::hiddenInput('email', Yii::$app->request->post('email') ?? '') ?>
                        <?= Html::hiddenInput('nif', Yii::$app->request->post('nif') ?? '') ?>
                        <?= Html::hiddenInput('morada', Yii::$app->request->post('morada') ?? '') ?>
                        <?= Html::hiddenInput('telefone', Yii::$app->request->post('telefone') ?? '') ?>
                        <?= Html::dropDownList('cliente', Yii::$app->request->post('cliente') ?? null,
                            \yii\helpers\ArrayHelper::map($clientes, 'id', 'username'),
                            [
                                'prompt' => 'Selecione um cliente',
                                'class' => 'form-control',
                                'id' => 'cliente',
                                'onchange' => 'this.form.submit()',
                                'required' => true
                            ]
                        ) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <!-- Secção do Cupão -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header" style="background-color: #007bff; color: white;">
                            <h4 class="mb-0">Dados Encomenda</h4>
                        </div>
                        <div class="card-body">
                            <?php $formEncomenda = ActiveForm::begin(['action' => ['compra'], 'method' => 'post']); ?>
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                            <?= Html::hiddenInput('cupao', Yii::$app->request->post('cupao') ?? ($cupao ? $cupao->codigo : null)) ?>
                            <?= Html::hiddenInput('metodo_entrega', Yii::$app->request->post('metodo_entrega') ?? null) ?>
                            <?= Html::hiddenInput('metodo_pagamento', Yii::$app->request->post('metodo_pagamento') ?? null) ?>
                            <?= Html::hiddenInput('cliente', Yii::$app->request->post('cliente') ?? null) ?>
                            <?= Html::hiddenInput('email', Yii::$app->request->post('email') ?? '') ?>
                            <?= Html::hiddenInput('nif', Yii::$app->request->post('nif') ?? '') ?>
                            <?= Html::hiddenInput('morada', Yii::$app->request->post('morada') ?? '') ?>
                            <?= Html::hiddenInput('telefone', Yii::$app->request->post('telefone') ?? '') ?>
                            <!-- Campo Email -->
                            <label>Email:</label>
                            <?= Html::input('text', 'email', Yii::$app->request->post('email'), [
                                'class' => 'form-control',
                                'id' => 'email',
                                'placeholder' => 'Email',
                                'required' => true
                            ]) ?>

                            <!-- Campo NIF -->
                            <label>Nif:</label>
                            <?= Html::input('number', 'nif', Yii::$app->request->post('nif'), [
                                'class' => 'form-control',
                                'id' => 'nif',
                                'min' => 0,
                                'placeholder' => 'Nif',
                            ]) ?>

                            <!-- Campo Morada -->
                            <label>Morada:</label>
                            <?= Html::input('text', 'morada', Yii::$app->request->post('morada'), [
                                'class' => 'form-control',
                                'id' => 'morada',
                                'placeholder' => 'Morada',
                                'required' => true
                            ]) ?>

                            <!-- Campo Telefone -->
                            <label>Telefone:</label>
                            <?= Html::input('number', 'telefone', Yii::$app->request->post('telefone'), [
                                'class' => 'form-control',
                                'id' => 'telefone',
                                'placeholder' => 'Telefone',
                                'min' => 0,
                                'required' => true
                            ]) ?>
                            <button type="submit" class="btn btn-primary mt-2">Guardar Dados</button>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumo do Carrinho -->
        <div class="col-lg-4">
            <div class="card shadow-sm position-fixed"
                 style="bottom: 80px; right: 20px; width: 320px; max-height: calc(100vh - 120px); overflow-y: auto;">
                <div class="card-header" style="background-color: #007bff; color: white;">
                    <h4 class="mb-0">Resumo da Compra</h4>
                </div>
                <div class="card-body">
                    <p><strong>Total de
                            Produtos:</strong> <?= Html::encode(number_format($valorProdutos, 2, ',', '.')) ?>€</p>
                    <p><strong>Desconto:</strong> <?= Html::encode(number_format($desconto, 2, ',', '.')) ?>%</p>
                    <p><strong>Custo de Envio:</strong> <?= Html::encode(number_format($custoEnvio, 2, ',', '.')) ?>€
                    </p>
                    <p><strong>Valor Poupado:</strong> <?= Html::encode(number_format($ValorPoupado, 2, ',', '.')) ?>€
                    </p>
                    <p class="h5"><strong>Total
                            Final:</strong> <?= Html::encode(number_format($valorFinal, 2, ',', '.')) ?>€</p>
                </div>
                <div class="card-footer">
                    <form action="<?= Url::to(['realizarvenda/fecharcompra']) ?>" method="post">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <input type="hidden" name="cupao"
                               value="<?= Yii::$app->request->post('cupao') ?? ($cupao ? $cupao->codigo : null) ?>">
                        <input type="hidden" name="cliente" value="<?= Yii::$app->request->post('cliente') ?? null ?>">
                        <input type="hidden" name="metodo_entrega"
                               value="<?= Yii::$app->request->post('metodo_entrega') ?? null ?>">
                        <input type="hidden" name="metodo_pagamento"
                               value="<?= Yii::$app->request->post('metodo_pagamento') ?? null ?>">
                        <input type="hidden" name="email" value="<?= Yii::$app->request->post('email') ?? '' ?>">
                        <input type="hidden" name="nif" value="<?= Yii::$app->request->post('nif') ?? '' ?>">
                        <input type="hidden" name="morada" value="<?= Yii::$app->request->post('morada') ?? '' ?>">
                        <input type="hidden" name="telefone" value="<?= Yii::$app->request->post('telefone') ?? '' ?>">
                        <button type="submit" class="btn btn-success w-100">Confirmar Compra</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>