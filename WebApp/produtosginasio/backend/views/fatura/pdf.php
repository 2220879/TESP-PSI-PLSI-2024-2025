<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .brand {
            display: flex;
            align-items: center;
        }

        .brand img {
            margin-right: 10px;
        }

        .fatura-info {
            text-align: right;
            white-space: nowrap;
        }

        .invoice-header h1 {
            font-size: 24px;
            margin: 0;
        }

        .invoice-header p {
            margin: 5px 0;
            font-size: 16px;
        }

        .invoice-details {
            margin-top: 20px;
        }

        .cupao-desconto {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .invoice-details th, .invoice-details td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .subtotal, .total, .valor-poupado, .metodo-pagamento {
            font-size: 18px;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="invoice-header">
    <div class="brand">
        <img src="<?= Yii::getAlias('@frontend/web') ?>/images/Icon_Projeto.png"
             class="brand-image img-circle elevation-3" style="opacity: .8">
    </div>
    <div class="fatura-info"><h3>Fatura Nº: <?= $fatura->id ?></h3>
        <p>Data: <?= date('d-m-Y', strtotime($fatura->dataEmissao)) ?></p>
        <p>Cliente: <?= $fatura->profile->user->username ?></p>
        <?php if ($fatura->nif != null): ?>
            <p>Nif: <?= $fatura->nif ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="invoice-details">
    <table style="width: 100%;">
        <thead>
        <tr>
            <th>Nome Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Valor Iva</th>
            <th>Valor c/ Iva</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->nomeProduto ?></td>
                <td><?= $item->quantidade ?></td>
                <td><?= number_format($item->precoUnit, 2) ?> €</td>
                <td><?= $item->valorIva ?> %</td>
                <td><?= number_format($item->valorComIva, 2) ?> €</td>
                <td><?= number_format($item->subtotal, 2) ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <table style="width: 100%;">
        <thead>
        <tr>
            <th>Método Entrega</th>
            <th>Dias Entrega</th>
            <th>Preço</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?= $fatura->metodoentrega->descricao ?></td>
            <td><?= $fatura->metodoentrega->diasEntrega ?></td>
            <td><?= number_format($fatura->metodoentrega->preco, 2) ?> €</td>
        </tr>
        </tbody>
    </table>
    <?php if ($Cupao != null): ?>
        <div class="cupao-desconto">
            <p>Cupão de desconto utilizado: <?= $Cupao->codigo ?></p>
            <p>Desconto: <?= number_format($Cupao->desconto, 2) ?> %</p>
        </div>
    <?php endif; ?>
</div>

<div class="metodo-pagamento">
    <p>Pagamento: <?= $fatura->metodopagamento->metodoPagamento ?> </p>
</div>

<?php if ($Cupao != null): ?>
    <div class="valor-poupado">
        <p>Valor Poupado: <?= $ValorPoupado ?> €</p>
    </div>
<?php endif; ?>

<?php if ($Cupao != null): ?>
    <div class="subtotal">
        <p>Subtotal: <?= number_format($subtotalDesconto, 2) ?> €</p>
    </div>
<?php else: ?>
    <div class="subtotal">
        <p>Subtotal: <?= number_format($fatura->valorTotal, 2) ?> €</p>
    </div>
<?php endif; ?>

<div class="total">
    <p>Total: <?= number_format($fatura->valorTotal, 2) ?> €</p>
</div>

</body>
</html>