<?php

namespace frontend\controllers;

use common\models\Carrinhocompra;
use common\models\Cupaodesconto;
use common\models\Encomenda;
use common\models\Fatura;
use common\models\Linhacarrinho;
use common\models\Linhafatura;
use common\models\Metodoentrega;
use common\models\Metodopagamento;
use common\models\Profile;
use common\models\Usocupao;
use Mpdf\Mpdf;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FinalizarcompraController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex($carrinho_id)
    {
        // Verificar se o carrinho existe
        $carrinho = Carrinhocompra::findOne($carrinho_id);
        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho não encontrado.');
        }

        // Obter os métodos de pagamento e de entrega (em vigor)
        $metodosPagamento = Metodopagamento::find()->all();
        $metodosEntrega = Metodoentrega::find()->where(['vigor' => 1])->all();

        // Calcular valores do carrinho
        $valorProdutos = $carrinho->valorTotal;
        $custoEnvio = 0.00;
        $desconto = 0.00;
        $ValorPoupado = 0.00;

        $cupao = null;
        if (Yii::$app->request->isPost) {
            // Verificar se o cupão foi enviado
            $cupao = Yii::$app->request->post('cupao');
            if ($cupao) {
                // Buscar o cupão na base de dados
                $cupao = Cupaodesconto::findOne(['codigo' => $cupao]);

                // Verifica se o cupão é válido e não expirou
                if ($cupao == null) {
                    //Se o cupão for inválido
                    Yii::$app->session->setFlash('error', "Cupão inválido");
                    //remover o cupão da sessão se inválido
                    Yii::$app->session->remove('cupao');
                    $cupao = null;
                } else if ($cupao && strtotime($cupao->dataFim) < time()) {
                    //Mensagem de erro, caso o cupão tenha expirado
                    Yii::$app->session->setFlash('error', "Este cupão está expirado.");
                    $cupao = null;
                } else if (Usocupao::find()->where(['cupaodesconto_id' => $cupao, 'profile_id' => $carrinho->profile_id])->exists()) {
                    //se o cupão for inválido, mostra mensagem de erro
                    Yii::$app->session->setFlash('error', "Cupão já utilizado!");
                    $cupao = null;
                } else if ($cupao && strtotime($cupao->dataFim) >= time()) {
                    //calcular o valor poupado com base do desconto do cupão
                    $ValorPoupado = ($cupao->desconto * $valorProdutos);
                    $desconto = $cupao->desconto;

                    //guarda o cupão na sessão
                    Yii::$app->session->set('cupao', $cupao);

                    //se o cupão for aplicado, exibe mensagem de sucesso
                    Yii::$app->session->setFlash('success', "Cupão aplicado!");
                }
            }
        }

        // Capturar o método de entrega selecionado e calcular o custo de envio
        if ($metodoEntregaId = Yii::$app->request->post('metodo_entrega')) {
            $metodoEntrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoEntrega) {
                $custoEnvio = $metodoEntrega->preco;
            }
        }


        // Calcular o valor final
        $valorFinal = ($valorProdutos - $ValorPoupado) + $custoEnvio;

        $cliente = Profile::find()->where(['id' => $carrinho->profile_id])->one();
        $dadosCliente = [
            'email' => $cliente->user->email,
            'nif' => $cliente->nif,
            'morada' => $cliente->morada,
            'telefone' => $cliente->telefone,
        ];

        // Renderizar a página de finalização de compra
        return $this->render('index', [
            'carrinho' => $carrinho,
            'metodosPagamento' => $metodosPagamento,
            'metodosEntrega' => $metodosEntrega,
            'valorProdutos' => $valorProdutos,
            'desconto' => $desconto,
            'custoEnvio' => $custoEnvio,
            'valorFinal' => $valorFinal,
            'cupao' => $cupao,
            'ValorPoupado' => $ValorPoupado,
            'dadosCliente' => $dadosCliente,
        ]);
    }

    public function actionConcluircompra($carrinho_id)
    {
        // Obtém a sessão
        $session = Yii::$app->session;

        // Carrega o carrinho usando o carrinho_id
        $carrinho = Carrinhocompra::findOne($carrinho_id);


        if (!$carrinho) {
            Yii::$app->session->setFlash('error', 'Carrinho não encontrado.');
            return $this->redirect(['site/index']);
        }

        // Obtém o usuário logado e o perfil associado
        $user_id = Yii::$app->user->identity->id;
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }
        //$produtosCarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho_id])->all();
        $produtosCarrinho = Linhacarrinho::find()->where([
            'carrinhocompras_id' => $carrinho_id,  // ID do carrinho
        ])->all();


        // Obtém o cupão inserido, se houver
        $cupaoCodigo = $session->get('cupao');

        // Obtém os dados do formulário
        $metodoPagamentoId = Yii::$app->request->post('metodo_pagamento');
        $metodoEntregaId = Yii::$app->request->post('metodo_entrega');
        $email = Yii::$app->request->post('email');
        $nif = Yii::$app->request->post('nif');
        $morada = Yii::$app->request->post('morada');
        $telefone = Yii::$app->request->post('telefone');

        $metodosPagamento = Metodopagamento::find()->all();
        $metodosEntrega = Metodoentrega::find()->where(['vigor' => 1])->all();
        $cliente = Profile::find()->where(['id' => $carrinho->profile_id])->one();
        $dadosCliente = [
            'email' => $cliente->user->email,
            'nif' => $cliente->nif,
            'morada' => $cliente->morada,
            'telefone' => $cliente->telefone,
        ];

        $valorProdutos = $carrinho->valorTotal;
        $custoEnvio = 0.00;
        $desconto = 0.00;
        $ValorPoupado = 0.00;

        if ($cupaoCodigo) {
            // Buscar o cupão na base de dados
            $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);

            // Verifica se o cupão é válido e não expirou
            if ($cupao && strtotime($cupao->dataFim) >= time()) {
                // Calcular o valor poupado com base do desconto do cupão
                $ValorPoupado = ($cupao->desconto * $valorProdutos);
                $desconto = $cupao->desconto;
            }
        }

        if ($metodoEntregaId = Yii::$app->request->post('metodo_entrega')) {
            $metodoEntrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoEntrega) {
                $custoEnvio = $metodoEntrega->preco;
            }
        }

        // Calcular o valor final
        $valorFinal = ($valorProdutos - $ValorPoupado) + $custoEnvio;

        // Verifica se os campos obrigatórios estão presentes
        if (empty($metodoEntregaId)) {
            Yii::$app->session->setFlash('error', 'Selecione o método de entrega.');
            return $this->render('index', ['carrinho_id' => $carrinho->id, 'cupao' => $cupaoCodigo, 'carrinho' => $carrinho, 'metodosEntrega' => $metodosEntrega, 'metodosPagamento' => $metodosPagamento, 'dadosCliente' => $dadosCliente, 'valorProdutos' => $valorProdutos, 'desconto' => $desconto, 'custoEnvio' => $custoEnvio, 'ValorPoupado' => $ValorPoupado, 'valorFinal' => $valorFinal]);
        }
        if (empty($metodoPagamentoId)) {
            Yii::$app->session->setFlash('error', 'Selecione o método de pagamento.');
            return $this->render('index', ['carrinho_id' => $carrinho->id, 'cupao' => $cupaoCodigo, 'carrinho' => $carrinho, 'metodosEntrega' => $metodosEntrega, 'metodosPagamento' => $metodosPagamento, 'dadosCliente' => $dadosCliente, 'valorProdutos' => $valorProdutos, 'desconto' => $desconto, 'custoEnvio' => $custoEnvio, 'ValorPoupado' => $ValorPoupado, 'valorFinal' => $valorFinal]);
        }
        if (empty($email)) {
            Yii::$app->session->setFlash('error', 'O campo de email deve ser preenchido.');
            return $this->render('index', ['carrinho_id' => $carrinho->id, 'cupao' => $cupaoCodigo, 'carrinho' => $carrinho, 'metodosEntrega' => $metodosEntrega, 'metodosPagamento' => $metodosPagamento, 'dadosCliente' => $dadosCliente, 'valorProdutos' => $valorProdutos, 'desconto' => $desconto, 'custoEnvio' => $custoEnvio, 'ValorPoupado' => $ValorPoupado, 'valorFinal' => $valorFinal]);
        }
        if (empty($morada)) {
            Yii::$app->session->setFlash('error', 'O campo de morada deve ser preenchido.');
            return $this->render('index', ['carrinho_id' => $carrinho->id, 'cupao' => $cupaoCodigo, 'carrinho' => $carrinho, 'metodosEntrega' => $metodosEntrega, 'metodosPagamento' => $metodosPagamento, 'dadosCliente' => $dadosCliente, 'valorProdutos' => $valorProdutos, 'desconto' => $desconto, 'custoEnvio' => $custoEnvio, 'ValorPoupado' => $ValorPoupado, 'valorFinal' => $valorFinal]);
        }
        if (empty($telefone)) {
            Yii::$app->session->setFlash('error', 'O campo de telefone deve ser preenchido.');
            return $this->render('index', ['carrinho_id' => $carrinho->id, 'cupao' => $cupaoCodigo, 'carrinho' => $carrinho, 'metodosEntrega' => $metodosEntrega, 'metodosPagamento' => $metodosPagamento, 'dadosCliente' => $dadosCliente, 'valorProdutos' => $valorProdutos, 'desconto' => $desconto, 'custoEnvio' => $custoEnvio, 'ValorPoupado' => $ValorPoupado, 'valorFinal' => $valorFinal]);
        }
        // Cria a encomenda
        $encomenda = new Encomenda();
        $encomenda->data = date('Y-m-d');
        $encomenda->hora = date('H:i:s');
        $encomenda->morada = $morada;
        $encomenda->telefone = $telefone;
        $encomenda->email = $email;
        $encomenda->estadoEncomenda = "Em processamento";
        $encomenda->profile_id = $profile->id;
        if (!$encomenda->save()) {
            Yii::$app->session->setFlash('error', 'Erro ao criar a encomenda.');
            return $this->redirect(['site/index']);
        }

        // Cria a base da fatura
        $fatura = new Fatura();
        $fatura->dataEmissao = date('Y-m-d');
        $fatura->horaEmissao = date('H:i:s');
        $fatura->valorTotal = 0.00;
        $fatura->ivaTotal = 0.00;
        if ($nif != null) {
            $fatura->nif = $nif;
        }
        $fatura->metodopagamento_id = $metodoPagamentoId;
        $fatura->metodoentrega_id = $metodoEntregaId;
        $fatura->encomenda_id = $encomenda->id;
        $fatura->profile_id = $profile->id;

        //se a base da fatura for criada com sucesso
        if ($fatura->save()) {
            // Percorre todos os produtos associados ao carrinho, obtidos pela relação Linhacarrinho
            foreach ($produtosCarrinho as $linhaCarrinho) {
                //aceder ao produto através da relação 'produto' definida no modelo Linhacarrinho
                $produto = $linhaCarrinho->produto;

                // Cria a linha da fatura
                $linhaFatura = new LinhaFatura();
                $linhaFatura->dataVenda = date('Y-m-d');
                if ($linhaCarrinho->tamanho != null) {
                    // Se o produto tiver tamanho, adiciona o tamanho ao nome
                    $linhaFatura->nomeProduto = $produto->nomeProduto . " - " . ($linhaCarrinho->tamanho ? $linhaCarrinho->tamanho->referencia : 'Sem tamanho');
                } else {
                    // Caso contrário, usa apenas o nome do produto
                    $linhaFatura->nomeProduto = $produto->nomeProduto;
                }

                $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
                $percentualIva = $produto->iva->percentagem * 100;
                $valorIvaAplicado = $subtotalSemIva * ($percentualIva / 100);
                $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

                $linhaFatura->quantidade = $linhaCarrinho->quantidade;
                $linhaFatura->precoUnit = $produto->preco;
                $linhaFatura->valorIva = $produto->iva->percentagem;
                $linhaFatura->valorComIva = number_format($subtotalComIva, 2);
                $linhaFatura->subtotal = number_format($subtotalComIva, 2);
                $linhaFatura->fatura_id = $fatura->id;
                $linhaFatura->produto_id = $produto->id;

                if ($linhaFatura->save()) {
                    $fatura->valorTotal += number_format($subtotalComIva, 2); // Adiciona o subtotal com IVA ao total da fatura
                    $fatura->ivaTotal += number_format($produto->iva->percentagem, 2); // Acumula o valor do IVA total
                }

            }

            // Calcula o custo da entrega
            $metodoentrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoentrega) {
                // Adiciona o custo do método de entrega ao valor total
                $fatura->valorTotal += number_format($metodoentrega->preco, 2);

            }

            // Se o cupão for atualizado
            if ($cupaoCodigo != null && $cupaoCodigo !== '') {
                $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);

                if ($cupao) {
                    // Calcular o valor do desconto como percentagem do subtotal com IVA
                    $ValorPoupado = ($cupao->desconto * $valorProdutos);

                    // Aplica o desconto no valor total, mas sem considerar o custo de envio
                    $fatura->valorTotal -= number_format($ValorPoupado, 2);
                }

                //regista o uso do cupão
                $Usocupao = new UsoCupao();
                $Usocupao->cupaodesconto_id = $cupao->id;
                $Usocupao->profile_id = $profile->id;
                $Usocupao->dataUso = date('Y-m-d');
                $Usocupao->save();
            }
            $fatura->save();


            $this->actionGeneratePdf($fatura->id, $cupao ?? null, number_format($ValorPoupado, 2) ?? 0.00);
        }

        // Verifica se a sessão do carrinho existe
        if (Yii::$app->session->has('carrinho')) {
            // Apaga a variável de sessão 'carrinho'
            Yii::$app->session->remove('carrinho');
        }

        // Verifica se a sessão do cupão existe
        if (Yii::$app->session->has('cupao')) {
            // Apaga a variável de sessão 'cupao'
            Yii::$app->session->remove('cupao');
        }

        //Apaga as linhas carrinho do cliente auntenticado
        Linhacarrinho::deleteAll([
            'carrinhocompras_id' => $carrinho_id,  // ID do carrinho
        ]);

        $this->updateCarrinhoTotal($carrinho_id);

        Yii::$app->session->set('carrinho', []);

        // Redireciona para a página principal após concluir a compra
        Yii::$app->session->setFlash('success', 'Compra realizada com sucesso!');
        return $this->redirect(['carrinhocompra/index']);

    }

    public function updateCarrinhoTotal($carrinhocompras_id)
    {
        $carrinho = Carrinhocompra::findOne($carrinhocompras_id);

        if ($carrinho) {
            // Inicializa os totais do carrinho
            $carrinho->quantidade = 0;
            $carrinho->valorTotal = 0;

            // Percorre as linhas do carrinho para recalcular os totais
            foreach ($carrinho->linhascarrinhos as $linha) {
                $carrinho->quantidade += $linha->quantidade;
                $carrinho->valorTotal += $linha->subtotal; // Subtotal já inclui IVA
            }

            // Salva os novos totais do carrinho
            if (!$carrinho->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o carrinho.');
            }
        }
    }

    public function actionGeneratePdf($faturaID, $Cupao, $ValorPoupado)
    {
        //procurar a fatura na base dados
        $fatura = Fatura::find()->where(['id' => $faturaID])->one();

        if ($fatura === null) {
            throw new NotFoundHttpException("Fatura não encontrada.");
        }

        $subtotalDesconto = $fatura->valorTotal + $ValorPoupado;

        //armazenar os dados da fatura
        $data = [
            'fatura' => $fatura,
            'items' => $fatura->linhasfaturas,
            'Cupao' => $Cupao,
            'ValorPoupado' => $ValorPoupado,
            'subtotalDesconto' => $subtotalDesconto,
        ];

        //gerar o conteúdo da fatura (HTML)
        $content = $this->renderPartial('../fatura/pdf', $data);

        //criar o PDF
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        //escrever o conteúdo da fatura no PDF
        $pdf->WriteHTML($content);

        //pasta faturas
        $diretorioFaturas = Yii::getAlias('@common/faturas/');
        $nomeFicheiro = 'fatura_' . $fatura->id . '.pdf';

        //verificar se a pasta existe, caso contrário criar a pasta
        if (!is_dir($diretorioFaturas)) {
            if (!mkdir($diretorioFaturas, 0777, true) && !is_dir($diretorioFaturas)) {
                throw new \Exception('Falha ao criar a pasta de uploads: ' . $diretorioFaturas);
            }
        }

        //guardar o PDF
        $pdf->Output($diretorioFaturas . $nomeFicheiro, 'F');
    }

}