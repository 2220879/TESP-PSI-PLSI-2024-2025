<?php

namespace backend\controllers;

use common\models\Cupaodesconto;
use common\models\Encomenda;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Metodoentrega;
use common\models\Metodopagamento;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Profile;
use common\models\Tamanho;
use common\models\User;
use common\models\Usocupao;
use Mpdf\Mpdf;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use function Symfony\Component\String\s;

class RealizarvendaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['index', 'adicionarproduto', 'editarquantidade', 'removerproduto', 'compra', 'fecharcompra', 'generatePdf'],
                    'rules' => [
                        [
                            'actions' => ['index', 'adicionarproduto', 'editarquantidade', 'removerproduto', 'compra', 'fecharcompra', 'generatePdf'],
                            'allow' => true,
                            'roles' => ['admin', 'funcionario'],
                        ],
                    ],
                ],

                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        //'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $session = Yii::$app->session;
        $carrinho = $session->get('carrinho', []);
        $produtos = Produto::find()->all();

        return $this->render('index', [
            'produtos' => $produtos,
            'carrinho' => $carrinho,
        ]);
    }


    public function actionAdicionarproduto()
    {
        //verifica se tem uma sessão ativa
        if (Yii::$app->session->isActive == false) {
            //se não tiver uma sessão ativa, é criada uma nova
            Yii::$app->session->open();
        }

        //verifica se o produto_id foi enviado e se é válido
        if (!isset($_GET['produto_id']) || !is_numeric($_GET['produto_id'])) {
            return 'Produto inválido.';
        }

        $produto_id = $_GET['produto_id'];
        $tamanhoReferencia = isset($_GET['tamanho_referencia']) ? $_GET['tamanho_referencia'] : null;
        $quantidadeSelecionada = isset($_GET['quantidade']) ? intval($_GET['quantidade']) : 1; // Quantidade selecionada na combobox

        $produto = Produto::findOne($produto_id);

        //obter o carrinho
        $carrinho = Yii::$app->session->get('carrinho', []);

        //verifica se o produto já está no carrinho
        $produtoEncontrado = false;

        foreach ($carrinho as $key => $produtoCarrinho) {
            if ($produtoCarrinho['id'] == $produto_id && $produtoCarrinho['tamanho'] == $tamanhoReferencia) {
                //atualiza a quantidade e o preço total
                $carrinho[$key]['quantidade'] += $quantidadeSelecionada;
                $carrinho[$key]['preco'] += $produto->preco * $quantidadeSelecionada;
                $produtoEncontrado = true;
                break;
            }
        }

        //se o produto não foi encontrado no carrinho, adiciona
        if (!$produtoEncontrado) {
            //cria o item para adicionar ao carrinho
            $item = [
                'id' => $produto_id,
                'nomeProduto' => $produto->nomeProduto,
                //preço total inicial baseado na quantidade selecionada
                'preco' => $produto->preco * $quantidadeSelecionada,
                'quantidade' => $quantidadeSelecionada,
                'tamanho' => $tamanhoReferencia
            ];
            $carrinho[] = $item;
        }

        //atualiza o carrinho na sessão
        Yii::$app->session->set('carrinho', $carrinho);

        //atualizar a quantidade na base dados
        if ($tamanhoReferencia) {
            $tamanho = Tamanho::findOne(['referencia' => $tamanhoReferencia]);
            //produto com tamanho associado
            $produtoTamanho = ProdutoshasTamanho::find()
                ->where(['produto_id' => $produto_id, 'tamanho_id' => $tamanho->id])
                ->one();

            //verifica se existe o tamanho associado ao produto
            if ($produtoTamanho) {
                //verifica se há stock suficiente
                if ($produtoTamanho->quantidade >= $quantidadeSelecionada) {
                    //atualiza a quantidade no ProdutoshasTamanho
                    $produtoTamanho->quantidade -= $quantidadeSelecionada;
                    $produtoTamanho->save();

                    //atualiza a quantidade total no produto
                    $produto->quantidade -= $quantidadeSelecionada;
                    $produto->save();
                } else {
                    Yii::$app->session->setFlash('error', 'Quantidade insuficiente para o tamanho selecionado.');
                    return $this->redirect(['realizarvenda/index']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Tamanho selecionado não disponível para este produto.');
                return $this->redirect(['realizarvenda/index']);
            }
        } else {
            //se for um produto sem tamanhos associados
            if ($produto->quantidade >= $quantidadeSelecionada) {
                // Atualiza a quantidade no produto
                $produto->quantidade -= $quantidadeSelecionada;
                $produto->save();
            } else {
                Yii::$app->session->setFlash('error', 'Quantidade insuficiente deste produto.');
                return $this->redirect(['realizarvenda/index']);
            }
        }

        Yii::$app->session->setFlash('success', 'Produto adicionado ao carrinho com sucesso!');
        return $this->redirect(['realizarvenda/index']);
    }

    public function actionEditarquantidade()
    {
        if (Yii::$app->session->isActive == false) {
            Yii::$app->session->open();
        }

        if (!isset($_POST['id']) || !isset($_POST['quantidade']) || !is_numeric($_POST['quantidade'])) {
            Yii::$app->session->setFlash('error', 'Parâmetros inválidos.');
            return $this->redirect(['realizarvenda/index']);
        }

        $produto_id = $_POST['id'];
        $tamanho_referencia = isset($_POST['tamanho']) ? $_POST['tamanho'] : '';
        $quantidadeNova = intval($_POST['quantidade']);

        $carrinho = Yii::$app->session->get('carrinho', []);

        foreach ($carrinho as $key => $produtoCarrinho) {
            if ($produtoCarrinho['id'] == $produto_id && ($produtoCarrinho['tamanho'] == $tamanho_referencia || $tamanho_referencia === '')) {
                $produto = Produto::findOne($produto_id);

                if ($produto === null) {
                    Yii::$app->session->setFlash('error', 'Produto não encontrado.');
                    return $this->redirect(['realizarvenda/index']);
                }

                if ($tamanho_referencia === '') {
                    //vai buscar a quantidade que está guardada
                    $quantidadeCarrinhoAntiga = $produtoCarrinho['quantidade'];

                    if ($quantidadeNova <= $quantidadeCarrinhoAntiga) {
                        //repõe a quantidade no stock do produto
                        $produto->quantidade += ($quantidadeCarrinhoAntiga - $quantidadeNova);
                        $produto->save();
                    } else {
                        //verifica se há stock suficiente para aumentar a quantidade
                        $quantidadeDisponivel = $produto->quantidade;
                        $quantidadeMaximaDisponivel = $quantidadeDisponivel + $quantidadeCarrinhoAntiga;

                        if ($quantidadeNova <= $quantidadeMaximaDisponivel) {
                            //ajusta a quantidade do produto
                            $produto->quantidade = $quantidadeDisponivel - ($quantidadeNova - $quantidadeCarrinhoAntiga);
                            $produto->save();
                        } else {
                            Yii::$app->session->setFlash('error', 'Quantidade indisponível em estoque.');
                            return $this->redirect(['realizarvenda/index']);
                        }
                    }

                    //atualiza o carrinho
                    $carrinho[$key]['quantidade'] = $quantidadeNova;
                    $carrinho[$key]['preco'] = $produto->preco * $quantidadeNova;
                } else {
                    //selecionar o id do tamanho
                    $tamanho = Tamanho::find()->where(['referencia' => $tamanho_referencia])->one();
                    if ($tamanho === null) {
                        Yii::$app->session->setFlash('error', 'Tamanho não encontrado.');
                        return $this->redirect(['realizarvenda/index']);
                    }

                    //guardar o id do tamanho
                    $tamanho_id = $tamanho->id;
                    //vai buscar o tamanho selecionado do produto pretendido
                    $produtoTamanho = ProdutosHasTamanho::find()->where(['produto_id' => $produto_id, 'tamanho_id' => $tamanho_id])->one();
                    if ($produtoTamanho === null) {
                        Yii::$app->session->setFlash('error', 'Produto com tamanho não encontrado.');
                        return $this->redirect(['realizarvenda/index']);
                    }

                    $quantidadeCarrinhoAntiga = $produtoCarrinho['quantidade'];

                    if ($quantidadeNova <= $quantidadeCarrinhoAntiga) {
                        //repõe no stock do tamanho específico
                        $produtoTamanho->quantidade += ($quantidadeCarrinhoAntiga - $quantidadeNova);
                        $produtoTamanho->save();
                    } else {
                        //verifica se há stock suficiente para aumentar a quantidade
                        $quantidadeDisponivel = $produtoTamanho->quantidade;
                        $quantidadeMaximaDisponivel = $quantidadeDisponivel + $quantidadeCarrinhoAntiga;

                        if ($quantidadeNova <= $quantidadeMaximaDisponivel) {
                            //ajusta a quantidade no stock do tamanho
                            $produtoTamanho->quantidade = $quantidadeDisponivel - ($quantidadeNova - $quantidadeCarrinhoAntiga);
                            $produtoTamanho->save();
                        } else {
                            Yii::$app->session->setFlash('error', 'Quantidade indisponível em estoque.');
                            return $this->redirect(['realizarvenda/index']);
                        }
                    }

                    //recalcular a quantidade total do produto na tabela Produto
                    $quantidadeTotal = 0;
                    $produtoTamanhos = ProdutosHasTamanho::find()->where(['produto_id' => $produto_id])->all();
                    foreach ($produtoTamanhos as $produtoTamanho) {
                        $quantidadeTotal += $produtoTamanho->quantidade;
                    }
                    //atualiza a quantidade total na tabela Produto
                    $produto->quantidade = $quantidadeTotal;

                    $produto->save();

                    //atualiza o carrinho
                    $carrinho[$key]['quantidade'] = $quantidadeNova;
                    $carrinho[$key]['preco'] = $produto->preco * $quantidadeNova;
                }

                //atualiza o carrinho na sessão
                Yii::$app->session->set('carrinho', $carrinho);
                return $this->redirect(['realizarvenda/index']);
            }
        }

        Yii::$app->session->setFlash('error', 'Produto não encontrado no carrinho.');
        return $this->redirect(['realizarvenda/index']);
    }

    public function actionRemoverproduto($id)
    {
        // Verifica se tem uma sessão ativa
        if (Yii::$app->session->isActive == false) {
            // Se não tiver uma sessão ativa, é criada uma nova
            Yii::$app->session->open();
        }

        // Obter o carrinho
        $carrinho = Yii::$app->session->get('carrinho', []);

        //procurar o produto a ser removido
        foreach ($carrinho as $key => $produtoCarrinho) {
            if ($produtoCarrinho['id'] == $id) {
                // Guardar a quantidade do produto para uso posterior
                $quantidadeSelecionada = $produtoCarrinho['quantidade'];

                //verificar se o produto tem tamanho associado
                if (isset($produtoCarrinho['tamanho'])) {
                    //produto com tamanho associado
                    $tamanhoReferencia = $produtoCarrinho['tamanho'];
                    $produto_id = $produtoCarrinho['id'];

                    //obter o produto e o tamanho
                    $produto = Produto::findOne($produto_id);
                    $tamanho = Tamanho::findOne(['referencia' => $tamanhoReferencia]);

                    if ($produto && $tamanho) {
                        //encontrar a relação do produto com a tabela ProdutosHasTamanho
                        $produtoTamanho = ProdutoshasTamanho::find()
                            ->where(['produto_id' => $produto_id, 'tamanho_id' => $tamanho->id])
                            ->one();

                        if ($produtoTamanho) {
                            //atualizar a quantidade do produto com o tamanho
                            $produtoTamanho->quantidade += $quantidadeSelecionada;
                            $produtoTamanho->save();

                            //atualizar a quantidade do produto principal
                            $produto->quantidade += $quantidadeSelecionada;
                            $produto->save();
                        } else {
                            Yii::$app->session->setFlash('error', 'Tamanho selecionado não disponível para este produto.');
                            return $this->redirect(['realizarvenda/index']);
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Produto ou Tamanho não encontrados.');
                        return $this->redirect(['realizarvenda/index']);
                    }
                } else {
                    //produto sem tamanho associado
                    $produto_id = $produtoCarrinho['id'];
                    $produto = Produto::findOne($produto_id);

                    if ($produto) {
                        //atualizar a quantidade do produto
                        $produto->quantidade += $quantidadeSelecionada;
                        $produto->save();
                    } else {
                        Yii::$app->session->setFlash('error', 'Produto não encontrado.');
                        return $this->redirect(['realizarvenda/index']);
                    }
                }

                //remove o produto do carrinho
                unset($carrinho[$key]);
                break;
            }
        }

        //atualiza o carrinho na sessão
        Yii::$app->session->set('carrinho', array_values($carrinho));

        Yii::$app->session->setFlash('success', 'Produto removido do carrinho com sucesso!');
        return $this->redirect(['realizarvenda/index']);
    }

    public function actionCompra()
    {
        // Verificar se o carrinho existe na sessão
        $carrinho = Yii::$app->session->get('carrinho');

        //caso não exista a sessão 'carrinho'
        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho não encontrado.');
        }

        // Calcular o valor total dos produtos no carrinho
        $valorProdutos = 0;
        foreach ($carrinho as $item) {
            $valorProdutos += $item['preco'] * $item['quantidade'];
        }

        // Obter os métodos de pagamento e de entrega (em vigor)
        $metodosPagamento = Metodopagamento::find()->all();
        $metodosEntrega = Metodoentrega::find()->where(['vigor' => 1])->all();
        $clientes = User::find()->where(['id' => Yii::$app->authManager->getUserIdsByRole('cliente')])->all();

        // Calcular valores do carrinho
        $custoEnvio = 0.00;
        $desconto = 0.00;
        $ValorPoupado = 0.00;
        $cupao = null;

        if (Yii::$app->request->isPost) {
            //verificar se o cupão foi enviado
            $cupaoCodigo = Yii::$app->request->post('cupao');
            $cliente = Yii::$app->request->post('cliente');

            if (Yii::$app->request->post('cliente') != null && $cliente != null) {
                if ($cupaoCodigo) {
                    //vai buscar o cupão na base de dados
                    $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);
                    $profile = Profile::find()->where(['user_id' => $cliente])->one();

                    // Verifica se o cupão é válido e não expirou
                    if ($cupao == null) {
                        //Se o cupão for inválido, exibe mensagem de erro
                        Yii::$app->session->setFlash('error', "Cupão inválido");
                        //remover o cupão da sessão se inválido
                        Yii::$app->session->remove('cupao');
                        $cupao = null;
                    } else if ($cupao && strtotime($cupao->dataFim) < time()) {
                        //Mensagem de erro, caso o cupão tenha expirado
                        Yii::$app->session->setFlash('error', "Este cupão está expirado.");
                        $cupao = null;
                    } else if (Usocupao::find()->where(['cupaodesconto_id' => $cupao, 'profile_id' => $profile])->exists()) {
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
        }

        //vai buscar o método de entrega selecionado e calcular o custo de envio
        if ($metodoEntregaId = Yii::$app->request->post('metodo_entrega')) {
            $metodoEntrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoEntrega) {
                $custoEnvio = $metodoEntrega->preco;
            }
        }

        // Calcular o valor final
        $valorFinal = ($valorProdutos - $ValorPoupado) + $custoEnvio;

        // Renderizar a página de finalização de compra
        return $this->render('finalizarcompra', [
            'metodosPagamento' => $metodosPagamento,
            'clientes' => $clientes,
            'metodosEntrega' => $metodosEntrega,
            'valorProdutos' => $valorProdutos,
            'desconto' => $desconto,
            'custoEnvio' => $custoEnvio,
            'valorFinal' => $valorFinal,
            'cupao' => $cupao,
            'ValorPoupado' => $ValorPoupado,
        ]);
    }

    public function actionFecharcompra()
    {
        $session = Yii::$app->session;
        $carrinho = $session->get('carrinho', []);
        $cupaoCodigo = $session->get('cupao');
        $clienteId = Yii::$app->request->post('cliente');
        $metodoPagamentoId = Yii::$app->request->post('metodo_pagamento');
        $metodoEntregaId = Yii::$app->request->post('metodo_entrega');
        $email = Yii::$app->request->post('email');
        $nif = Yii::$app->request->post('nif');
        $morada = Yii::$app->request->post('morada');
        $telefone = Yii::$app->request->post('telefone');
        $metodosPagamento = Metodopagamento::find()->all();
        $metodosEntrega = Metodoentrega::find()->where(['vigor' => 1])->all();
        $clientes = User::find()->where(['id' => Yii::$app->authManager->getUserIdsByRole('cliente')])->all();
        $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);
        $desconto = 0.00;
        $ValorPoupado = 0.00;
        $custoEnvio = 0.00;

        // Calcular o valor total dos produtos no carrinho
        $valorProdutos = 0;
        foreach ($carrinho as $item) {
            $valorProdutos += $item['preco'] * $item['quantidade'];
        }

        // Calcular o valor do desconto e do cupão
        if ($cupao && strtotime($cupao->dataFim) >= time()) {
            $desconto = $cupao->desconto;
            $ValorPoupado = ($cupao->desconto * $valorProdutos);
        }

        // Calcular o custo de envio
        if ($metodoEntregaId) {
            $metodoEntrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoEntrega) {
                $custoEnvio = $metodoEntrega->preco;
            }
        }

        // Calcular o valor final
        $valorFinal = ($valorProdutos - $ValorPoupado) + $custoEnvio;

        $dados = [
            'metodosPagamento' => $metodosPagamento,
            'clientes' => $clientes,
            'metodosEntrega' => $metodosEntrega,
            'cupao' => $cupao,
            'desconto' => $desconto,
            'ValorPoupado' => $ValorPoupado,
            'custoEnvio' => $custoEnvio,
            'clienteId' => $clienteId,
            'metodoPagamentoId' => $metodoPagamentoId,
            'metodoEntregaId' => $metodoEntregaId,
            'email' => $email,
            'nif' => $nif,
            'morada' => $morada,
            'telefone' => $telefone,
            'valorProdutos' => $valorProdutos,
            'valorFinal' => $valorFinal,
            'cupaoCodigo' => $cupaoCodigo, // Adiciona o código do cupão
        ];

        if (empty($clienteId)) {
            Yii::$app->session->setFlash('error', 'Não tem o cliente associado à compra.');
            return $this->render('finalizarcompra', $dados);
        } else if (empty($metodoPagamentoId)) {
            Yii::$app->session->setFlash('error', 'Não tem um método pagamento associado à compra.');
            return $this->render('finalizarcompra', $dados);
        } else if (empty($metodoEntregaId)) {
            Yii::$app->session->setFlash('error', 'Não tem um método entrega associado à compra.');
            return $this->render('finalizarcompra', $dados);
        } else if (empty($email)) {
            Yii::$app->session->setFlash('error', 'Não tem um email associado à compra.');
            return $this->render('finalizarcompra', $dados);
        } else if (empty($morada)) {
            Yii::$app->session->setFlash('error', 'Não tem uma morada associada à compra.');
            return $this->render('finalizarcompra', $dados);
        } else if (empty($telefone)) {
            Yii::$app->session->setFlash('error', 'Não tem um telefone associado à compra.');
            return $this->render('finalizarcompra', $dados);
        } else {
            // Vai buscar o perfil do Utilizador
            $profile = Profile::find()->where(['user_id' => $clienteId])->one();
            // Criar a encomenda
            $encomenda = new Encomenda();
            $encomenda->data = date('Y-m-d');
            $encomenda->hora = date('H:i:s');
            $encomenda->morada = $morada;
            $encomenda->telefone = $telefone;
            $encomenda->email = $email;
            $encomenda->estadoEncomenda = "Em processamento";
            $encomenda->profile_id = $profile->id;
            $encomenda->save();

            // Criar a base da fatura
            $fatura = new Fatura();
            $fatura->dataEmissao = date('Y-m-d');
            $fatura->horaEmissao = date('H:i:s');
            $fatura->valorTotal = 0.00;
            $fatura->ivaTotal = 0.00;
            if (!empty($nif)) {
                $fatura->nif = $nif;
            }
            $fatura->metodopagamento_id = $metodoPagamentoId;
            $fatura->metodoentrega_id = $metodoEntregaId;
            $fatura->encomenda_id = $encomenda->id;
            $fatura->profile_id = $profile->id;

            // Se correr tudo bem com a criação da fatura
            if ($fatura->save()) {
                // Percorrer todos os objetos armazenados na sessão 'carrinho'
                foreach ($carrinho as $produto) {
                    $produtoID = Produto::find()->where(['id' => $produto['id']])->one();
                    $linhaFatura = new LinhaFatura();
                    $linhaFatura->dataVenda = date('Y-m-d');
                    $linhaFatura->nomeProduto = !empty($produto['tamanho']) ? $produto['nomeProduto'] . " - " . $produto['tamanho'] : $produto['nomeProduto'];
                    $linhaFatura->quantidade = $produto['quantidade'];
                    $linhaFatura->precoUnit = $produtoID->preco;
                    $linhaFatura->valorIva = $produtoID->iva->percentagem;
                    $linhaFatura->valorComIva = round($produtoID->preco * $produto['quantidade'] + ($produtoID->iva->percentagem / 100), 2);
                    $linhaFatura->subtotal = round($produtoID->preco * $produto['quantidade'] + ($produtoID->iva->percentagem / 100), 2);
                    $linhaFatura->fatura_id = $fatura->id;
                    $linhaFatura->produto_id = $produtoID->id;

                    if ($linhaFatura->save()) {
                        $fatura->valorTotal += round($produtoID->preco * $produto['quantidade'] + ($produtoID->iva->percentagem / 100), 2);
                        $fatura->ivaTotal += $produtoID->iva->percentagem;
                        $fatura->save();
                    }
                }

                $metodoentrega = Metodoentrega::find()->where(['id' => $metodoEntregaId])->one();
                $fatura->valorTotal += $metodoentrega->preco;
                $fatura->save();

                if (!empty($cupaoCodigo)) {
                    $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);
                    $valorCupao = ($fatura->valorTotal * $cupao->desconto) / 100; // cálculo correto do valor do cupão
                    $fatura->valorTotal -= $valorCupao; // aplicar o desconto na fatura
                    $fatura->save();

                    $usoCupao = new UsoCupao();
                    $usoCupao->cupaodesconto_id = $cupao->id;
                    $usoCupao->profile_id = $profile->id;
                    $usoCupao->dataUso = date('Y-m-d');
                    $usoCupao->save();
                }

                $this->actionGeneratePdf($fatura->id, $cupao ?? null, $ValorPoupado ?? 0.00);
            }

            if (Yii::$app->session->has('carrinho')) {
                Yii::$app->session->remove('carrinho');
            }

            if (Yii::$app->session->has('cupao')) {
                Yii::$app->session->remove('cupao');
            }

            return $this->redirect(['index']);
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