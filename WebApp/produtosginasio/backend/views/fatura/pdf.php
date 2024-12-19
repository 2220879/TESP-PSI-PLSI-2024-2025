<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
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

        .invoice-details th, .invoice-details td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .total {
            font-size: 18px;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="invoice-header">
    <h1>Fatura</h1>
    <p>Fatura Nº: <?= $fatura->id ?></p>
    <p>Data: <?= $fatura->dataEmissao ?></p>
    <p>Cliente: <?= $fatura->profile->user->username ?></p>
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
                <td><?= $item->precoUnit ?> €</td>
                <td><?= $item->valorIva ?> %</td>
                <td><?= $item->valorComIva ?> €</td>
                <td><?= $item->subtotal ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="total">
    <p>Total: <?= $fatura->valorTotal ?> €</p>
</div>

</body>
</html>