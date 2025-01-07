<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Carrinhocompra;
use common\models\Linhacarrinho;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Profile;
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
            Yii::$app->response->statusCode = 404;
            return ['message' => 'Carrinho não encontrado.'];
        }

        // Estruturar os dados para resposta
        $linhasCarrinho = [];
        foreach ($carrinho->linhascarrinhos as $linha) {
            $produto = $linha->produto;
            $tamanho = $linha->tamanho;

            $linhasCarrinho[] = [
                'produto_nome' => $produto ? $produto->nomeProduto : 'Produto não encontrado',
                'tamanho_nome' => $tamanho ? $tamanho->referencia : 'Tamanho não encontrado',
                'quantidade' => $linha->quantidade,
                'precoUnit' => $linha->precoUnit,
                'subtotal' => $linha->subtotal,
                'valorComIva' => $linha->valorComIva,
            ];
        }

        return [
            'quantidade_total' => $carrinho->quantidade,
            'valorTotal' => $carrinho->valorTotal,
            'linhasCarrinho' => $linhasCarrinho,
        ];
    }





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

        $linhaCarrinho->valorIva = round($valorIvaAplicado, 2);
        $linhaCarrinho->valorComIva = round($subtotalComIva, 2);
        $linhaCarrinho->subtotal = round($subtotalComIva, 2);

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

            $linhaCarrinho->subtotal = round($subtotalComIva, 2);
            $linhaCarrinho->valorComIva = round($subtotalComIva, 2);

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

            $linhaCarrinho->subtotal = round($subtotalComIva, 2);
            $linhaCarrinho->valorComIva = round($subtotalComIva, 2);

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
