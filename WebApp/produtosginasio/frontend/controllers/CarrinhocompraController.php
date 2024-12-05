<?php

namespace frontend\controllers;

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
        // Obter o perfil do usuário (supondo que o perfil esteja vinculado ao usuário logado)
        $user_id = Yii::$app->user->identity->id;
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);

        if (!$profile) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }

        // Buscar o carrinho de compras associado ao perfil
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);

        if (!$carrinho) {
            $carrinho = new Carrinhocompra();
            $carrinho->profile_id = $profile->id;
            $carrinho->quantidade = 0;
            $carrinho->valorTotal = 0.00;
            $carrinho->save();
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
    public function actionCreate($produto_id)
    {
        if (!$produto_id) {
            throw new NotFoundHttpException('Produto não especificado.');
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $user_id = Yii::$app->user->identity->id;
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);

        if (!$profile) {
            throw new NotFoundHttpException('Perfil não encontrado.');
        }

        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);

        if (!$carrinho) {
            $carrinho = new Carrinhocompra();
            $carrinho->profile_id = $profile->id;
            $carrinho->quantidade = 0;
            $carrinho->valorTotal = 0.00;
            $carrinho->save();
        }

        // Busca o produto a ser adicionado
        $produto = \common\models\Produto::findOne($produto_id);
        if (!$produto) {
            throw new NotFoundHttpException('Produto não encontrado.');
        }

        $linhaCarrinho = Linhacarrinho::findOne([
            'carrinhocompras_id' => $carrinho->id,
            'produto_id' => $produto_id,
        ]);

        /*if ($linhaCarrinho) {
            // Se o produto já estiver no carrinho, apenas aumenta a quantidade
            $linhaCarrinho->quantidade += 1;
            $linhaCarrinho->subtotal += $linhaCarrinho->precoUnit;
            $linhaCarrinho->save();
        } else {
            // Adiciona um novo item ao carrinho
            $linhaCarrinho = new Linhacarrinho();
            $linhaCarrinho->carrinhocompras_id = $carrinho->id;
            $linhaCarrinho->produto_id = $produto_id;
            $linhaCarrinho->quantidade = 1;
            $linhaCarrinho->precoUnit = $produto->preco;
            $linhaCarrinho->valorIva = $produto->preco * ($produto->iva->percentagem / 100);
            $linhaCarrinho->valorComIva = $linhaCarrinho->precoUnit + ($linhaCarrinho->precoUnit * $linhaCarrinho->valorIva);
            $linhaCarrinho->subtotal = $linhaCarrinho->precoUnit + ($linhaCarrinho->precoUnit * $linhaCarrinho->valorIva);
            $linhaCarrinho->save();
        }*/
        if ($linhaCarrinho) {
            // Se o produto já estiver no carrinho, redireciona com mensagem de aviso
            Yii::$app->session->setFlash('warning', 'Este produto já está no seu carrinho.');
            return $this->redirect(['index']);
        }

// Adiciona um novo item ao carrinho
        $linhaCarrinho = new Linhacarrinho();
        $linhaCarrinho->carrinhocompras_id = $carrinho->id;
        $linhaCarrinho->produto_id = $produto_id;
        $linhaCarrinho->quantidade = 1;
        $linhaCarrinho->precoUnit = $produto->preco;
        $linhaCarrinho->valorIva = $produto->preco * ($produto->iva->percentagem / 100);
        $linhaCarrinho->valorComIva = $linhaCarrinho->precoUnit + ($linhaCarrinho->precoUnit * $linhaCarrinho->valorIva);
        $linhaCarrinho->subtotal = $linhaCarrinho->precoUnit + ($linhaCarrinho->precoUnit * $linhaCarrinho->valorIva);
        $linhaCarrinho->save();


        // Atualiza os totais do carrinho
        $carrinho->quantidade += 1;
        $carrinho->valorTotal += $linhaCarrinho->subtotal;
        $carrinho->save();

        return $this->redirect(['index']);
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
        //Encontra a linha do carrinho com base no id
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if(!$linhaCarrinho){
            throw new NotFoundHttpException('Produtdo não encontrado no carrinho');
        }

        //Encontra o carrinho de compra associado á linha
        $carrinho = $linhaCarrinho->carrinhocompras;

        //atualiza o total do carrinho (subtrai o subtotal do produto removido)
        $carrinho->quantidade -= $linhaCarrinho->quantidade;
        $carrinho->valorTotal -= $linhaCarrinho->subtotal;

        //Guarda as alterações do carrinho
        $carrinho->save();

        //Remove a linha do carrinho
        $linhaCarrinho->delete();


        return $this->redirect(['index']);

    }

    public function actionDiminuir($id)
    {
        // Encontra a linha do carrinho pelo ID
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            throw new NotFoundHttpException('Produto não encontrado no carrinho.');
        }

        // Verifica se a quantidade é maior que 1 antes de diminuir
        if ($linhaCarrinho->quantidade > 1) {
            $valorItemComIva = $linhaCarrinho->precoUnit + ($linhaCarrinho->precoUnit * $linhaCarrinho->valorIva);

            $linhaCarrinho->quantidade -= 1; // Decrementa a quantidade
            $linhaCarrinho->subtotal -= $valorItemComIva;
            $linhaCarrinho->save();
        } else {
            // Se a quantidade for 1, você pode optar por remover o produto ou impedir de diminuir mais
            Yii::$app->session->setFlash('info', 'A quantidade não pode ser menor que 1. Para remover o produto, use o botão de remover.');
        }

        $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);

        // Redireciona de volta para o carrinho
        return $this->redirect(['index']);
    }

    public function actionAumentar($id)
    {
        // Encontra a linha do carrinho pelo ID
        $linhaCarrinho = Linhacarrinho::findOne($id);

        if (!$linhaCarrinho) {
            throw new NotFoundHttpException('Produto não encontrado no carrinho.');
        }

        // Aumenta a quantidade
        $linhaCarrinho->quantidade += 1;

        // Recalcula o subtotal
        $linhaCarrinho->subtotal += $linhaCarrinho->subtotal / ($linhaCarrinho->quantidade - 1);


        // Salva as alterações da linha do carrinho
        if ($linhaCarrinho->save()) {
            // Sucesso ao salvar a linha, mas não atualiza o total do carrinho
            $this->updateCarrinhoTotal($linhaCarrinho->carrinhocompras_id);
        }

        // Redireciona de volta para o carrinho
        return $this->redirect(['index']);
    }

    public function updateCarrinhoTotal($carrinhocompras_id)
    {
        $carrinho = Carrinhocompra::findOne($carrinhocompras_id);

        if($carrinho){
            $carrinho->quantidade=0;
            $carrinho->valorTotal=0;

            foreach ($carrinho->linhascarrinhos as $linha){
                $carrinho->quantidade += $linha->quantidade;
                $carrinho->valorTotal += $linha->subtotal;
            }

            $carrinho->save();
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
