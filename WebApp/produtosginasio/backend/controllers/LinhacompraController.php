<?php

namespace backend\controllers;

use backend\models\Compra;
use backend\models\Linhacompra;
use backend\models\LinhacompraSearch;
use common\models\Iva;
use common\models\Marca;
use common\models\Produto;
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

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'id' => $id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'compra' => $compra,
            'produtos' => $produtos,
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
        $compra = Linhacompra::findOne(['compra_id' => $id]);

        $this->findModel($id)->delete();

        return $this->redirect(['index', 'id' => $compra->id]);
    }

    /**
     * Finds the Linhacompra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Linhacompra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Linhacompra::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
