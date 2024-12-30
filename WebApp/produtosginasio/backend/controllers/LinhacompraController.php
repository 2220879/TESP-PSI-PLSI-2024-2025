<?php

namespace backend\controllers;

use backend\models\Compra;
use backend\models\Linhacompra;
use backend\models\LinhacompraSearch;
use common\models\Iva;
use common\models\Marca;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Tamanho;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LinhacompraController implements the CRUD actions for Linhacompra model.
 */
class LinhacompraController extends Controller
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
                    'only' => ['index', 'view', 'create', 'update', 'delete', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'model'],
                            'allow' => true,
                            'roles' => ['admin', 'funcionario'],
                        ],
                    ],
                ],
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
     * Lists all Linhacompra models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $searchModel = new LinhacompraSearch();

        //parâmetro de pesquisa para incluir o filtro pela compra desejada
        $params = $this->request->queryParams;
        $params['LinhacompraSearch']['compra_id'] = $id;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
        ]);
    }

    /**
     * Displays a single Linhacompra model.
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
     * Creates a new Linhacompra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new Linhacompra();

        $compra = Compra::findOne($id);
        $produtos = Produto::find()->select(['nomeProduto', 'id'])->indexBy('id')->column();
        $tamanhos = Tamanho::find()->select(['referencia', 'id'])->indexBy('id')->column();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                $quantidades = Yii::$app->request->post('quantidade_tamanho');
                $produtosHasTamanhos = ProdutosHasTamanho::find()
                    ->where(['produto_id' => $model->produto_id])
                    ->all();

                if (!empty($produtosHasTamanhos)) {
                    if (isset($model->quantidade) && $model->quantidade > 0 && (empty($quantidades) || !array_filter($quantidades))) {
                        Yii::$app->session->setFlash('error', 'Por favor, insira as quantidades para os tamanhos associados ao produto.');
                        return $this->render('create', [
                            'model' => $model,
                            'compra' => $compra,
                            'produtos' => $produtos,
                            'tamanhos' => $tamanhos,
                        ]);
                    }

                    if (is_array($quantidades) && !empty($quantidades) && array_filter($quantidades)) {
                        $model->quantidade = 0;

                        foreach ($quantidades as $tamanho_id => $quantidade) {
                            $produtoHasTamanho = ProdutosHasTamanho::find()
                                ->where(['produto_id' => $model->produto_id, 'tamanho_id' => $tamanho_id])
                                ->one();

                            if ($produtoHasTamanho) {
                                $produtoHasTamanho->quantidade += $quantidade;
                                $produtoHasTamanho->save();
                            } else {
                                $produtoHasTamanho = new ProdutosHasTamanho();
                                $produtoHasTamanho->produto_id = $model->produto_id;
                                $produtoHasTamanho->tamanho_id = $tamanho_id;
                                $produtoHasTamanho->quantidade = $quantidade;
                                $produtoHasTamanho->save();
                            }

                            $model->quantidade += $quantidade;
                        }

                        if ($model->save()) {
                            $produto = Produto::find()->where(['id' => $model->produto_id])->one();
                            $produto->quantidade += $model->quantidade;
                            $produto->save();
                        }
                    }
                } else {
                    // Se o produto não tem tamanhos associados, cria os registos de tamanhos
                    if (is_array($quantidades) && !empty($quantidades) && array_filter($quantidades)) {
                        $model->quantidade = 0;

                        foreach ($quantidades as $tamanho_id => $quantidade) {
                            $produtoHasTamanho = new ProdutosHasTamanho();
                            $produtoHasTamanho->produto_id = $model->produto_id;
                            $produtoHasTamanho->tamanho_id = $tamanho_id;
                            $produtoHasTamanho->quantidade = $quantidade;
                            $produtoHasTamanho->save();

                            $model->quantidade += $quantidade;
                        }

                        if ($model->save()) {
                            $produto = Produto::find()->where(['id' => $model->produto_id])->one();
                            $produto->quantidade += $model->quantidade;
                            $produto->save();
                        }
                    } else {
                        $produto = Produto::find()->where(['id' => $model->produto_id])->one();
                        $produto->quantidade += $model->quantidade;
                        $produto->save();
                    }
                }

                return $this->redirect(['index', 'id' => $id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'compra' => $compra,
            'produtos' => $produtos,
            'tamanhos' => $tamanhos,
        ]);
    }

    /**
     * Updates an existing Linhacompra model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $compra = Compra::findOne($model->compra_id);

        $produtos = Produto::find()->select(['nomeProduto', 'id'])->indexBy('id')->column();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->compra_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'compra' => $compra,
            'produtos' => $produtos,
        ]);
    }

    /**
     * Deletes an existing Linhacompra model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $linhaCompra = $this->findModel($id);
        $compraId = $linhaCompra->compra_id;
        $linhaCompra->delete();
        
        return $this->redirect(['index', 'id' => $compraId]);
    }

    /**
     * Finds the Linhacompra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Linhacompra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Linhacompra::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
