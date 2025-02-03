<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Carrinhocompra;
use common\models\Cupaodesconto;
use common\models\Encomenda;
use common\models\Fatura;
use common\models\Linhacarrinho;
use common\models\Linhafatura;
use common\models\Metodoentrega;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Profile;
use common\models\Usocupao;
use Mpdf\Mpdf;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class CarrinhocompraController extends ActiveController
{
    public $modelClass = 'common\models\Carrinhocompra';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCarrinho()
    {
        // Vai obter o ID do user autenticado
        $userId = Yii::$app->params['id'];

        // Vai buscar o perfil associado ao usuário
        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        // Vai buscar o carrinho associado ao perfil
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        $baseUrl = 'http://172.22.21.204' . Yii::getAlias('@web/uploads/');

        // Estruturar os dados para resposta
        $linhasCarrinho = [];
        foreach ($carrinho->linhascarrinhos as $linha) {
            $produto = $linha->produto;
            $tamanho = $linha->tamanho;

            $linhaDados = [
                'id' => $linha->id,
                'quantidade' => $linha->quantidade,
                'precoUnit' => $linha->precoUnit,
                'valorIva' => $linha->valorIva,
                'valorComIva' => $linha->valorComIva,
                'subtotal' => $linha->subtotal,
                'carrinhocompras_id' => $carrinho->id,
                'produto_nome' => $produto ? $produto->nomeProduto : 'Produto não encontrado',
                'tamanho_nome' => $tamanho ? $tamanho->referencia : 'N/A',
                'imagem' => null
            ];

            // Verifica se o produto tem imagens associadas
            if (!empty($produto->imagens)) {
                // Vai buscar a primeira imagem
                $primeiraImagem = $produto->imagens[0];

                // Monta a URL completa da imagem
                $linhaDados['imagem'] = $baseUrl . $primeiraImagem->filename;
            }

            $linhasCarrinho[] = $linhaDados;
        }

        return [
            'linhasCarrinho' => $linhasCarrinho,
        ];
    }

    public function actionDetalhescarrinho()
    {
        // Vai obter o ID do user autenticado
        $userId = Yii::$app->params['id'];

        // Vai buscar o perfil associado ao usuário
        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        // Vai buscar o carrinho associado ao perfil
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        return [
            'quantidade_total' => $carrinho->quantidade,
            'valorTotal' => $carrinho->valorTotal,
        ];
    }

    public function actionAdicionarprodutocarrinho()
    {
        $userId = Yii::$app->params['id'];

        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        $request = Yii::$app->request;
        $produtoId = $request->getBodyParam('produto');
        $tamanhoId = $request->getBodyParam('tamanho');

        // Verifica se o produto tem tamanhos associados
        $produtoHasTamanhos = ProdutosHasTamanho::find()->where(['produto_id' => $produtoId])->all();
        $produto = Produto::find()->where(['id' => $produtoId])->one();

        if ($produto == null) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Produto não encontrado.'];
        }

        if ($produtoHasTamanhos) {
            if ($tamanhoId != null) {
                $produtoTamanho = ProdutosHasTamanho::find()->where(['produto_id' => $produtoId, 'tamanho_id' => $tamanhoId])->one();
                if (!$produtoTamanho || $produtoTamanho->quantidade <= 0) {
                    Yii::$app->response->statusCode = 400;
                    return ['message' => 'Stock insuficiente.'];
                }
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Tamanho não especificado ou não encontrado para este produto.'];
            }
        } else {
            if ($produto->quantidade <= 0) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Stock insuficiente.'];
            }
        }

        // Atualiza a quantidade de produto ou produto com tamanho
        if ($produtoHasTamanhos) {
            $produtoTamanho->quantidade -= 1;
            $produtoTamanho->save();

            // Atualiza a quantidade total do produto na tabela Produtos
            $produto->quantidade -= 1;
            $produto->save();
        } else {
            $produto->quantidade -= 1;
            $produto->save();
        }

        $linhaCarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id, 'produto_id' => $produto->id])->one();

        if ($linhaCarrinho) {
            $linhaCarrinho->quantidade += 1;
        } else {
            $linhaCarrinho = new Linhacarrinho();
            $linhaCarrinho->quantidade = 1;
            $linhaCarrinho->carrinhocompras_id = $carrinho->id;
            $linhaCarrinho->produto_id = $produto->id;
            if ($produtoHasTamanhos) {
                $linhaCarrinho->tamanho_id = $produtoTamanho->tamanho_id;
            }
        }

        $linhaCarrinho->precoUnit = $produto->preco;
        $linhaCarrinho->valorIva = $produto->iva->percentagem;

        $percentualIva = $produto->iva->percentagem * 100;
        $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
        $valorIvaAplicado = $subtotalSemIva * ($percentualIva / 100);
        $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

        $linhaCarrinho->valorComIva = number_format($subtotalComIva, 2);
        $linhaCarrinho->subtotal = number_format($subtotalComIva, 2);

        if ($linhaCarrinho->save()) {
            $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);
        }

        return ['linhaCarrinho' => $linhaCarrinho];
    }

    public function actionAumentarquantidade()
    {
        $request = Yii::$app->request;
        $linha = $request->getBodyParam('linhaCarrinho');

        //encontrar a linha do carrinho
        $linhaCarrinho = Linhacarrinho::findOne($linha);

        if (!$linhaCarrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Produto não encontrado no carrinho.'];
        }

        //verifica se o produto tem tamanho associado
        $temTamanho = ProdutosHasTamanho::find()
            ->where(['produto_id' => $linhaCarrinho->produto_id])
            ->andWhere(['tamanho_id' => $linhaCarrinho->tamanho_id])
            ->exists();

        if ($temTamanho) {
            //produtos com tamanho associado
            $produtostamanho = ProdutosHasTamanho::findOne([
                'produto_id' => $linhaCarrinho->produto_id,
                'tamanho_id' => $linhaCarrinho->tamanho_id,
            ]);

            if ($produtostamanho && $produtostamanho->quantidade > 0) {
                $produtostamanho->quantidade -= 1;

                //atualiza a quantidade total na tabela Produtos
                $produto = Produto::findOne($linhaCarrinho->produto_id);
                $produto->quantidade -= 1;

                $produtostamanho->save();
                $produto->save();
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Stock insuficiente para aumentar a quantidade.'];
            }
        } else {
            //produtos sem tamanho associado
            $produto = Produto::findOne($linhaCarrinho->produto_id);

            if ($produto->quantidade > 0) {
                $produto->quantidade -= 1;
                $produto->save();
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Stock insuficiente para aumentar a quantidade.'];
            }
        }

        //aumentar quantidade do produto no carrinho
        $linhaCarrinho->quantidade += 1;

        $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
        $percentualIva = $linhaCarrinho->produto->iva->percentagem * 100;
        $valorIvaAplicado = $subtotalSemIva * ($percentualIva / 100);
        $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

        $linhaCarrinho->subtotal = number_format($subtotalComIva, 2);
        $linhaCarrinho->valorComIva = number_format($subtotalComIva, 2);

        if ($linhaCarrinho->save()) {
            $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);

            return [
                'status' => 'success',
                'message' => 'Quantidade aumentada com sucesso.',
            ];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao atualizar a linha do carrinho.'];
        }
    }

    public function actionDiminuirquantidade()
    {
        $request = Yii::$app->request;
        $linha = $request->getBodyParam('linhaCarrinho');

        $linhaCarrinho = Linhacarrinho::findOne($linha);

        if (!$linhaCarrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Produto não encontrado no carrinho.'];
        }

        //verificar se a quantidade é maior que 1
        if ($linhaCarrinho->quantidade <= 1) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'A quantidade não pode ser menor que 1.'];
        }

        //verifica se o produto tem tamanho associado
        $temTamanho = ProdutosHasTamanho::find()
            ->where(['produto_id' => $linhaCarrinho->produto_id])
            ->andWhere(['tamanho_id' => $linhaCarrinho->tamanho_id])
            ->exists();

        if ($temTamanho) {
            //produtos com tamanho associado
            $produtostamanho = ProdutosHasTamanho::findOne([
                'produto_id' => $linhaCarrinho->produto_id,
                'tamanho_id' => $linhaCarrinho->tamanho_id,
            ]);

            if ($produtostamanho) {
                $produtostamanho->quantidade += 1;

                //atualiza a quantidade total na tabela Produtos
                $produto = Produto::findOne($linhaCarrinho->produto_id);
                $produto->quantidade += 1;

                if ($produtostamanho->save() && $produto->save()) {
                    // Continuação do cálculo do carrinho
                } else {
                    Yii::$app->response->statusCode = 400;
                    return ['message' => 'Erro ao atualizar o stock.'];
                }
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Tamanho não encontrado no stock para este produto.'];
            }
        } else {
            //produtos sem tamanho associado
            $produto = Produto::findOne($linhaCarrinho->produto_id);

            if ($produto) {
                $produto->quantidade += 1;

                if (!$produto->save()) {
                    Yii::$app->response->statusCode = 400;
                    return ['message' => 'Erro ao atualizar o stock do produto.'];
                }
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Produto não encontrado no stock.'];
            }
        }

        //diminuir a quantidade do produto no carrinho
        $linhaCarrinho->quantidade -= 1;

        $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
        $percentualIva = $linhaCarrinho->produto->iva->percentagem;
        $valorIvaAplicado = $subtotalSemIva * ($percentualIva / 100);
        $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

        $linhaCarrinho->subtotal = number_format($subtotalComIva, 2);
        $linhaCarrinho->valorComIva = number_format($subtotalComIva, 2);

        if ($linhaCarrinho->save()) {
            $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);

            return [
                'status' => 'success',
                'message' => 'Quantidade diminuída com sucesso.',
            ];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao atualizar a linha do carrinho.'];
        }
    }

    public function actionApagarprodutocarrinho()
    {
        $userId = Yii::$app->params['id'];

        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        $request = Yii::$app->request;
        $linha = $request->getBodyParam('linhaCarrinho');

        $linhaCarrinho = Linhacarrinho::find()->where(['id' => $linha, 'carrinhocompras_id' => $carrinho->id])->one();
        if ($linhaCarrinho == null) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Não foi possivel obter a linha do carrinho.'];
        }

        //verifica se o produto tem tamanho associado
        $temTamanho = ProdutosHasTamanho::find()
            ->where(['produto_id' => $linhaCarrinho->produto_id])
            ->andWhere(['tamanho_id' => $linhaCarrinho->tamanho_id])
            ->exists();

        if ($temTamanho) {
            //produtos com tamanho associado
            $produtostamanho = ProdutosHasTamanho::findOne([
                'produto_id' => $linhaCarrinho->produto_id,
                'tamanho_id' => $linhaCarrinho->tamanho_id,
            ]);

            if ($produtostamanho) {
                $produtostamanho->quantidade += $linhaCarrinho->quantidade;

                //atualiza a quantidade total na tabela Produtos
                $produto = Produto::findOne($linhaCarrinho->produto_id);
                $produto->quantidade += $linhaCarrinho->quantidade;

                $produtostamanho->save();
                $produto->save();
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Tamanho não encontrado no stock para este produto.'];
            }
        } else {
            //produtos sem tamanho associado
            $produto = Produto::findOne($linhaCarrinho->produto_id);

            if ($produto) {
                $produto->quantidade += $linhaCarrinho->quantidade;

                if (!$produto->save()) {
                    Yii::$app->response->statusCode = 400;
                    return ['message' => 'Erro ao atualizar o stock do produto.'];
                }
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Produto não encontrado no stock.'];
            }
        }

        if ($linhaCarrinho->delete()) {
            $this->updateCarrinhoTotal($carrinho->id);

            return [
                'status' => 'success',
                'message' => 'Produto removido com sucesso!',
            ];
        } else {
            Yii::$app->response->statusCode = 500;
            return ['message' => 'Erro ao remover o produto do carrinho.'];
        }
    }

    public function actionAplicarcupao()
    {
        $request = Yii::$app->request;
        $userId = Yii::$app->params['id'];
        $profile = Profile::findOne(['user_id' => $userId]);
        $codigo = $request->getBodyParam('cupao');

        // Buscar o cupão na base de dados
        $cupao = Cupaodesconto::findOne(['codigo' => $codigo]);

        // Verifica se o cupão é válido e não expirou
        if ($cupao && strtotime($cupao->dataFim) >= time()) {
            if (Usocupao::find()->where(['cupaodesconto_id' => $cupao->id, 'profile_id' => $profile->id])->exists()) {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'O Cupão já foi utilizado.'];
            } else {

                return ['codigoCupao' => $cupao->codigo];
            }
        } else {
            // Se o cupão for inválido, exibe mensagem de erro
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Cupão inválido.'];
        }

        Yii::$app->response->statusCode = 500;
        return ['message' => 'Não foi possivel aplicar o cupão.'];
    }

    public function actionDetalhesfinalizarcompra()
    {
        $userId = Yii::$app->params['id'];

        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        $subTotal = $carrinho->valorTotal;
        $custoEnvio = 0;
        $desconto = 0;
        $valorPoupado = 0;

        $request = Yii::$app->request;

        $cupao = $request->getBodyParam('codigo');
        if (!empty($cupao)) {
            $cupao = Cupaodesconto::find()->where(['codigo' => $cupao])->one();

            if ($cupao && strtotime($cupao->dataFim) >= time()) {
                $valorPoupado = $cupao->desconto * $subTotal;
                $desconto = $cupao->desconto;
            }
        }

        $metodoEntregaId = $request->getBodyParam('metodo_entrega');
        if ($metodoEntregaId != null) {
            $metodoEntrega = Metodoentrega::findOne($metodoEntregaId);
            if ($metodoEntrega) {
                $custoEnvio = $metodoEntrega->preco;
            }
        }

        $valorTotal = ($subTotal - $valorPoupado) + $custoEnvio;

        return [
            'subtotal' => number_format($subTotal, 2, ',', '.'),
            'desconto' => number_format($desconto * 100, 0, ',', '.'),
            'custoEnvio' => number_format($custoEnvio, 2, ',', '.'),
            'valorPoupado' => number_format($valorPoupado, 2, ',', '.'),
            'valorTotal' => number_format($valorTotal, 2, ',', '.')
        ];
    }

    public function actionConcluircompra()
    {
        $userId = Yii::$app->params['id'];

        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        $produtosCarrinho = Linhacarrinho::find()->where([
            'carrinhocompras_id' => $carrinho->id,  // ID do carrinho
        ])->all();

        $request = Yii::$app->request;

        $metodoPagamentoId = $request->getBodyParam('metodo_pagamento');
        $metodoEntregaId = $request->getBodyParam('metodo_entrega');
        $cupaoCodigo = $request->getBodyParam('codigo_cupao');
        $email = $request->getBodyParam('email');
        $nif = $request->getBodyParam('nif');
        $morada = Yii::$app->request->post('morada');
        $telefone = Yii::$app->request->post('telefone');

        // Verifica se os campos obrigatórios estão presentes
        if (empty($metodoPagamentoId) || empty($metodoEntregaId) || empty($email) || empty($morada) || empty($telefone)) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Todos os campos devem ser preenchidos.'];
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
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao criar a encomenda.'];
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
            if (!empty($cupaoCodigo)) {
                $cupao = Cupaodesconto::findOne(['codigo' => $cupaoCodigo]);

                if ($cupao) {
                    // Calcular o valor do desconto como percentagem do subtotal com IVA
                    $ValorPoupado = ($cupao->desconto * $carrinho->valorTotal);

                    // Aplica o desconto no valor total, mas sem considerar o custo de envio
                    $fatura->valorTotal -= number_format($ValorPoupado, 2);
                }

                // Registra o uso do cupão
                $Usocupao = new UsoCupao();
                $Usocupao->cupaodesconto_id = $cupao->id;
                $Usocupao->profile_id = $profile->id;
                $Usocupao->dataUso = date('Y-m-d');
                $Usocupao->save();
            }
            $fatura->save();


            $this->actionGeneratePdf($fatura->id, $cupao ?? null, number_format($ValorPoupado, 2) ?? 0.00);
        }

        //Apaga as linhas carrinho do cliente auntenticado
        Linhacarrinho::deleteAll([
            'carrinhocompras_id' => $carrinho->id,  // ID do carrinho
        ]);

        $this->updateCarrinhoTotal($carrinho->id);

        return 'Compra efetuada com sucesso!';

    }

    public function actionGeneratePdf($faturaID, $Cupao, $ValorPoupado)
    {
        //procurar a fatura na base dados
        $fatura = Fatura::find()->where(['id' => $faturaID])->one();

        if ($fatura === null) {
            Yii::$app->response->statusCode = 500;
            return ['message' => 'Fatura não encontrada.'];
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
        $content = $this->renderPartial('@backend/views/fatura/pdf', $data);

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
                Yii::$app->response->statusCode = 500;
                return ['message' => 'Falha ao criar a pasta de uploads.'];
            }
        }

        //guardar o PDF
        $pdf->Output($diretorioFaturas . $nomeFicheiro, 'F');
    }

    //-------------------------------------------------SIS------------------------------------------------------------------------------

    public function actionAdicionarcarrinho($produto_id, $tamanho_id)
    {
        // Verificar se o produto existe
        $produto = Produto::findOne($produto_id);
        if (!$produto) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Produto não encontrado.'];
        }

        // Verifica se o tamanho existe
        $tamanho = \common\models\Tamanho::findOne($tamanho_id);
        if (!$tamanho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Tamanho não encontrado.'];
        }

        // vai buscar a quantidade do produto no tamanho selecionado na tabela ProdutosHasTamanho
        $produtostamanho = \common\models\ProdutosHasTamanho::findOne([
            'produto_id' => $produto_id,
            'tamanho_id' => $tamanho_id,
        ]);

        if (!$produtostamanho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Stock do produto para o tamanho selecionado não encontrado.'];
        }

        // Vai obter o user autenticado
        $userId = Yii::$app->params['id'];

        // Vai obter o perfil associado ao user
        $profile = Profile::findOne(['user_id' => $userId]);
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil não encontrado.'];
        }

        // Vai obter o carrinho do perfil
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho não encontrado.'];
        }

        // Verifica se o produto já está no carrinho com o tamanho especificado
        $linhaCarrinho = Linhacarrinho::findOne([
            'carrinhocompras_id' => $carrinho->id,
            'produto_id' => $produto_id,
            'tamanho_id' => $tamanho_id,
        ]);

        // Capturar os parâmetros da requisição
        $request = Yii::$app->request;
        $quantidade = $request->getBodyParam('quantidade');
        //$tamanhoId = $request->getBodyParam('tamanho_id');

        if ($linhaCarrinho) {
            // Produto já está no carrinho
            Yii::$app->response->statusCode = 400;
            return ['message' => 'O produto já está adicionado ao carrinho.',];
        }

        // Verifica se há estoque suficiente antes de adicionar ao carrinho
        if ($produtostamanho->quantidade < $quantidade) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Quantidade insuficiente para o tamanho selecionado.'];
        }

        // Atualiza a quantidade do produto no tamanho selecionado
        $produtostamanho->quantidade -= $quantidade;
        if (!$produtostamanho->save()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao atualizar o estoque do produto no tamanho selecionado.'];
        }

        // Adiciona o produto como nova linha no carrinho
        $linhaCarrinho = new Linhacarrinho();
        $linhaCarrinho->carrinhocompras_id = $carrinho->id;
        $linhaCarrinho->produto_id = $produto_id;
        $linhaCarrinho->tamanho_id = $tamanho_id;
        $linhaCarrinho->quantidade = $quantidade;
        $linhaCarrinho->precoUnit = $produto->preco;

        // Calcula os valores com IVA
        $percentualIva = $produto->iva->percentagem * 100;
        $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
        $valorIvaAplicado = $subtotalSemIva * ($percentualIva / 100);
        $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

        $linhaCarrinho->valorIva = number_format($valorIvaAplicado, 2);
        $linhaCarrinho->valorComIva = number_format($subtotalComIva, 2);
        $linhaCarrinho->subtotal = number_format($subtotalComIva, 2);

        if (!$linhaCarrinho->save()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao adicionar o produto ao carrinho.'];
        }

        // Atualiza o total do carrinho
        $carrinho->quantidade += $quantidade;
        $carrinho->valorTotal += $linhaCarrinho->subtotal;

        if (!$carrinho->save()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao atualizar o carrinho.'];
        }

        // Retorna a resposta de sucesso
        return [
            'status' => 'success',
            'message' => 'Produto adicionado ao carrinho com sucesso.',
        ];
    }

    public function actionApagarlinhacarrinho($id)
    {
        // Verifica se a linha do carrinho existe
        $linhaCarrinho = Linhacarrinho::findOne($id);
        if (!$linhaCarrinho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Linha do carrinho não encontrada.'];
        }

        // Vai obter o carrinho relacionado à linha do carrinho
        $carrinho = Carrinhocompra::findOne($linhaCarrinho->carrinhocompras_id);
        if (!$carrinho) {
            return ['message' => 'Carrinho não encontrado.'];
        }

        // Atualiza a quantidade do tamanho do produto correspondente
        $produtostamanho = \common\models\ProdutosHasTamanho::findOne([
            'produto_id' => $linhaCarrinho->produto_id,
            'tamanho_id' => $linhaCarrinho->tamanho_id,
        ]);

        if ($produtostamanho) {
            $produtostamanho->quantidade += $linhaCarrinho->quantidade;
            if (!$produtostamanho->save()) {
                return ['message' => 'Erro ao atualizar o estoque do produto.'];
            }
        }

        // Atualiza o total do carrinho antes de remover a linha
        $carrinho->quantidade -= $linhaCarrinho->quantidade;
        $carrinho->valorTotal -= $linhaCarrinho->subtotal;

        if (!$carrinho->save()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao atualizar os totais do carrinho.'];
        }

        if (!$linhaCarrinho->delete()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao apagar a linha do carrinho.'];
        }

        return [
            'status' => 'success',
            'message' => 'Linha do carrinho apagada com sucesso.',
        ];
    }

    public function actionDiminuir($id)
    {
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            return ['message' => 'Produto não encontrado no carrinho.'];
        }

        $produtostamanho = ProdutosHasTamanho::findOne([
            'produto_id' => $linhaCarrinho->produto_id,
            'tamanho_id' => $linhaCarrinho->tamanho_id,
        ]);

        if (!$produtostamanho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Tamanho não encontrado no estoque para este produto.'];
        }

        if ($linhaCarrinho->quantidade > 1) {
            $linhaCarrinho->quantidade -= 1;

            $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
            $percentualIva = $linhaCarrinho->produto->iva->percentagem;
            $valorIvaAplicado = $subtotalSemIva * $percentualIva;
            $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

            $linhaCarrinho->subtotal = number_format($subtotalComIva, 2);
            $linhaCarrinho->valorComIva = number_format($subtotalComIva, 2);

            if ($linhaCarrinho->save()) {
                $produtostamanho->quantidade += 1;
                $produtostamanho->save();

                $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);

                return [
                    'status' => 'success',
                    'message' => 'Quantidade diminuída com sucesso.',
                ];
            } else {
                return ['message' => 'Erro ao atualizar a linha do carrinho.'];
            }
        } else {
            return ['message' => 'A quantidade não pode ser menor que 1. Use a rota de remoção para excluir o produto.'];
        }
    }


    public function actionAumentar($id)
    {
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            return ['message' => 'Produto não encontrado no carrinho.'];
        }

        $produtostamanho = ProdutosHasTamanho::findOne([
            'produto_id' => $linhaCarrinho->produto_id,
            'tamanho_id' => $linhaCarrinho->tamanho_id,
        ]);

        if (!$produtostamanho) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Tamanho não encontrado no estoque para este produto.'];
        }

        if ($produtostamanho->quantidade > 0) {
            $linhaCarrinho->quantidade += 1;

            $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
            $percentualIva = $linhaCarrinho->produto->iva->percentagem;
            $valorIvaAplicado = $subtotalSemIva * $percentualIva;
            $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

            $linhaCarrinho->subtotal = number_format($subtotalComIva, 2);
            $linhaCarrinho->valorComIva = number_format($subtotalComIva, 2);

            if ($linhaCarrinho->save()) {
                $produtostamanho->quantidade -= 1;
                $produtostamanho->save();

                $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);

                return [
                    'status' => 'success',
                    'message' => 'Quantidade aumentada com sucesso.',
                ];
            } else {
                return ['message' => 'Erro ao atualizar a linha do carrinho.'];
            }
        } else {
            return ['message' => 'Stock insuficiente para aumentar a quantidade.'];
        }
    }


    public function updateCarrinhoTotal($carrinhocompras_id)
    {
        $carrinho = Carrinhocompra::findOne($carrinhocompras_id);

        if ($carrinho) {
            $carrinho->quantidade = 0;
            $carrinho->valorTotal = 0;

            foreach ($carrinho->linhascarrinhos as $linha) {
                $carrinho->quantidade += $linha->quantidade;
                $carrinho->valorTotal += $linha->subtotal;
            }

            if (!$carrinho->save()) {
                return ['message' => 'Erro ao atualizar o total do carrinho.'];
            }
        }
    }

}