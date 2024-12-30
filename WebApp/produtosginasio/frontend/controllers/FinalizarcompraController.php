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
                if ($cupao && strtotime($cupao->dataFim) >= time()) {
                    // Calcular o valor poupado com base do desconto do cupão
                    $ValorPoupado = ($cupao->desconto * $valorProdutos);
                    $desconto = $cupao->desconto;
                    // Guarda cupão na sessão
                    Yii::$app->session->set('cupao', $cupao);
                } else {
                    // Se o cupão for inválido, exibe mensagem de erro
                    Yii::$app->session->setFlash('error', "Cupão inválido");
                    // Remover cupão da sessão se inválido
                    Yii::$app->session->remove('cupao');
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

        // Verifica se os campos obrigatórios estão presentes
        if (empty($metodoPagamentoId) || empty($metodoEntregaId) || empty($email) || empty($morada) || empty($telefone)) {
            Yii::$app->session->setFlash('error', 'Todos os campos obrigatórios devem ser preenchidos.');
            return $this->redirect(['site/index']);
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
            Yii::$app->session->setFlash('error', 'Erro ao salvar a encomenda.');
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

        // Se a fatura for salva com sucesso
        if ($fatura->save()) {
            // Percorre todos os produtos associados ao carrinho, obtidos pela relação Linhacarrinho
            foreach ($produtosCarrinho as $linhaCarrinho) {
                $produto = $linhaCarrinho->produto; // Aqui, você acessa o produto através da relação 'produto' definida no modelo Linhacarrinho

                // Cria a linha da fatura
                $linhaFatura = new LinhaFatura();
                $linhaFatura->dataVenda = date('Y-m-d');
                if ($linhaCarrinho->tamanho != null) {
                    // Se o produto tiver tamanho, adiciona o tamanho ao nome
                    //$linhaFatura->nomeProduto = $produto->nomeProduto . " - " . $linhaCarrinho->tamanho;
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
                $linhaFatura->valorIva = $percentualIva;
                $linhaFatura->valorComIva = round($subtotalComIva, 2);
                $linhaFatura->subtotal = round($subtotalComIva, 2);
                $linhaFatura->fatura_id = $fatura->id;
                $linhaFatura->produto_id = $produto->id;

                if ($linhaFatura->save()) {
                    $fatura->valorTotal += round($subtotalComIva, 2); // Adiciona o subtotal com IVA ao total da fatura
                    $fatura->ivaTotal += round($valorIvaAplicado, 2); // Acumula o valor do IVA total
                }

            }

            // Calcula o custo da entrega
            $metodoentrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoentrega) {
                // Adiciona o custo do método de entrega ao valor total
                $fatura->valorTotal += round($metodoentrega->preco, 2);

            }

            // Se o cupão for atualizado
            if ($cupaoCodigo != null && $cupaoCodigo !== '') {
                $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);

                if ($cupao) {
                    // Calcular o valor do desconto como percentagem do subtotal com IVA
                    $ValorPoupado = ($cupao->desconto) * $subtotalComIva;

                    // Aplica o desconto no valor total, mas sem considerar o custo de envio
                    $fatura->valorTotal -= round($ValorPoupado, 2);
                }

                // Registra o uso do cupão
                $Usocupao = new UsoCupao();
                $Usocupao->cupaodesconto_id = $cupao->id;
                $Usocupao->profile_id = $profile->id;
                $Usocupao->dataUso = date('Y-m-d');
                $Usocupao->save();
            }
            $fatura->save();


            $this->actionGeneratePdf($fatura->id, $cupao ?? null, $ValorPoupado ?? 0.00);
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

        Yii::$app->session->set('carrinho', []);


        // Redireciona para a página principal após concluir a compra
        return $this->redirect(['site/index']);

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

