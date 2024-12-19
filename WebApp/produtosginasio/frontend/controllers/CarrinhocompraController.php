<?php

namespace frontend\controllers;

use common\models\ProdutosHasTamanho;
use frontend\models\Carrinhocompra;
use frontend\models\CarrinhocompraSearch;
use frontend\models\Linhacarrinho;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CarrinhocompraController implements the CRUD actions for Carrinhocompra model.
 */
class CarrinhocompraController extends Controller
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

    /**
     * Lists all Carrinhocompra models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Obter o ID do usuário logado
        $user_id = Yii::$app->user->identity->id;

        // Obter o perfil associado ao usuário
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);

        if (!$profile) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }

        // Buscar o carrinho de compras associado ao perfil
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);

        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho de compras não encontrado.');
        }

        // Obter as linhas do carrinho
        $linhasCarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id])->all();

        // Passar para a view
        return $this->render('index', [
            'linhasCarrinho' => $linhasCarrinho,
            'carrinho' => $carrinho,
        ]);
    }

    /**
     * Displays a single Carrinhocompra model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Carrinhocompra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($produto_id, $tamanho_id, $quantidade)
    {
        if (!$produto_id || !$tamanho_id || !$quantidade) {
            throw new NotFoundHttpException('Dados inválidos.');
        }

        // Verifica se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $user_id = Yii::$app->user->identity->id;
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }

        // Verifica se o carrinho já existe
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho de compras não encontrado.');
        }

        // Busca o produto
        $produto = \common\models\Produto::findOne($produto_id);
        if (!$produto) {
            throw new NotFoundHttpException('Produto não encontrado.');
        }

        // Busca o tamanho
        $tamanho = \common\models\Tamanho::findOne($tamanho_id);
        if (!$tamanho) {
            throw new NotFoundHttpException('Tamanho não encontrado.');
        }

        // Busca a quantidade do produto no tamanho selecionado na tabela produtoshastamanho
        $produtostamanho = ProdutosHasTamanho::findOne([
            'produto_id' => $produto_id,
            'tamanho_id' => $tamanho_id,
        ]);

        if (!$produtostamanho) {
            throw new NotFoundHttpException('Estoque do produto para o tamanho selecionado não encontrado.');
        }

        // Verifica se o produto já está no carrinho
        $linhaCarrinho = Linhacarrinho::findOne([
            'carrinhocompras_id' => $carrinho->id,
            'produto_id' => $produto_id,
            'tamanho_id' => $tamanho_id,
        ]);

        if ($linhaCarrinho) {
            // Produto já está no carrinho
            Yii::$app->session->setFlash('info', 'O produto já se encontra no carrinho de compras.');
        } else {
            // Verifica se há estoque suficiente antes de adicionar ao carrinho
            if ($produtostamanho->quantidade < $quantidade) {
                Yii::$app->session->setFlash('warning', 'Quantidade insuficiente para o tamanho selecionado.');
                return $this->redirect(['site/produto', 'id' => $produto_id]);
            }

            // Atualiza a quantidade do produto no tamanho selecionado
            $produtostamanho->quantidade -= $quantidade;
            if (!$produtostamanho->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar o estoque do produto no tamanho selecionado.');
                return $this->redirect(['site/produto', 'id' => $produto_id]);
            }

            $linhaCarrinho = new Linhacarrinho();
            $linhaCarrinho->carrinhocompras_id = $carrinho->id;
            $linhaCarrinho->produto_id = $produto_id;
            $linhaCarrinho->tamanho_id = $tamanho_id;
            $linhaCarrinho->quantidade = $quantidade;
            $linhaCarrinho->precoUnit = $produto->preco; // Preço unitário sem IVA

            $percentualIva = $produto->iva->percentagem * 100;
            $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
            $valorIvaAplicado = $subtotalSemIva * ($percentualIva / 100);
            $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

            // Atribuir os valores calculados ao modelo
            $linhaCarrinho->valorIva = round($valorIvaAplicado, 2); // Valor do IVA aplicado
            $linhaCarrinho->valorComIva = round($subtotalComIva, 2); // Total com IVA
            $linhaCarrinho->subtotal = round($subtotalComIva, 2); // Subtotal sem IVA

            //Guardar na base de dados
            if (!$linhaCarrinho->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao adicionar o produto ao carrinho.');
                return $this->redirect(['site/produto', 'id' => $produto_id]);
            }

            // Atualiza os totais do carrinho
            $carrinho->quantidade += $quantidade;
            $carrinho->valorTotal += $linhaCarrinho->subtotal;
            if (!$carrinho->save()) {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar os totais do carrinho.');
                return $this->redirect(['site/produto', 'id' => $produto_id]);
            }
        }

        return $this->redirect(['carrinhocompra/index']);
    }

    /**
     * Updates an existing Carrinhocompra model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Carrinhocompra model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // Encontra a linha do carrinho com base no id
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            throw new NotFoundHttpException('Produto não encontrado no carrinho');
        }

        // Encontra o carrinho de compra associado à linha
        $carrinho = $linhaCarrinho->carrinhocompras;

        // Remove a linha do carrinho
        $linhaCarrinho->delete();

        // Atualiza os totais do carrinho após a remoção
        $this->updateCarrinhoTotal($carrinho->id);

        return $this->redirect(['index']);

    }

    public function actionDiminuir($id)
    {
        // Encontra a linha do carrinho pelo ID
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            throw new NotFoundHttpException('Produto não encontrado no carrinho.');
        }

        // Obtém o tamanho associado ao produto na tabela ProdutosHasTamanho
        $produtostamanho = ProdutosHasTamanho::findOne([
            'produto_id' => $linhaCarrinho->produto_id,
            'tamanho_id' => $linhaCarrinho->tamanho_id,
        ]);

        if (!$produtostamanho) {
            throw new NotFoundHttpException('Tamanho não encontrado no estoque para este produto.');
        }

        // Verifica se a quantidade no carrinho é maior que 1 antes de diminuir
        if ($linhaCarrinho->quantidade > 1) {
            // Decrementa a quantidade do carrinho
            $linhaCarrinho->quantidade -= 1;

            // Recalcular o subtotal com IVA
            $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
            $percentualIva = $linhaCarrinho->produto->iva->percentagem;
            $valorIvaAplicado = $subtotalSemIva * $percentualIva;
            $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

            // Atualizar o subtotal e o valor com IVA
            $linhaCarrinho->subtotal = round($subtotalComIva, 2); // Total com IVA
            $linhaCarrinho->valorComIva = round($subtotalComIva, 2); // Subtotal com IVA

            // Atualiza o carrinho
            if ($linhaCarrinho->save()) {
                // Atualiza o estoque, incrementando a quantidade
                $produtostamanho->quantidade += 1;
                $produtostamanho->save();

                // Atualiza os totais do carrinho
                $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar a quantidade do produto no carrinho.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Não é possível reduzir a quantidade para menos de 1. Para remover o produto, use o botão de remover.');
        }

        return $this->redirect(['index']);
    }

    public function actionAumentar($id)
    {
        // Encontra a linha do carrinho pelo ID
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            throw new NotFoundHttpException('Produto não encontrado no carrinho.');
        }

        // Obtém o tamanho associado ao produto na tabela ProdutosHasTamanho
        $produtostamanho = ProdutosHasTamanho::findOne([
            'produto_id' => $linhaCarrinho->produto_id,
            'tamanho_id' => $linhaCarrinho->tamanho_id,
        ]);

        if (!$produtostamanho) {
            throw new NotFoundHttpException('Tamanho não encontrado no estoque para este produto.');
        }

        // Verifica se há estoque disponível antes de aumentar
        if ($produtostamanho->quantidade > 0) {
            // Incrementa a quantidade no carrinho
            $linhaCarrinho->quantidade += 1;

            // Recalcular o subtotal com IVA
            $subtotalSemIva = $linhaCarrinho->precoUnit * $linhaCarrinho->quantidade;
            $percentualIva = $linhaCarrinho->produto->iva->percentagem;
            $valorIvaAplicado = $subtotalSemIva * $percentualIva;
            $subtotalComIva = $subtotalSemIva + $valorIvaAplicado;

            // Atualizar os valores do carrinho
            $linhaCarrinho->subtotal = round($subtotalComIva, 2);
            $linhaCarrinho->valorComIva = round($subtotalComIva, 2);

            // Atualiza o carrinho
            if ($linhaCarrinho->save()) {
                // Decrementa o estoque
                $produtostamanho->quantidade -= 1;
                $produtostamanho->save();

                // Atualiza os totais do carrinho
                $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar a quantidade do produto no carrinho.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Estoque insuficiente para aumentar a quantidade.');
        }

        return $this->redirect(['index']);
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

    /**
     * Finds the Carrinhocompra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Carrinhocompra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Carrinhocompra::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}