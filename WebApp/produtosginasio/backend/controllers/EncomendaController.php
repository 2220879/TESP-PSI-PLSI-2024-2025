<?php

namespace backend\controllers;

use common\models\Encomenda;
use common\models\EncomendaSearch;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EncomendaController implements the CRUD actions for Encomenda model.
 */
class EncomendaController extends Controller
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
                    'only' => ['index', 'view', 'update', 'detalhes', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'update', 'detalhes', 'model'],
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
     * Lists all Encomenda models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EncomendaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Encomenda model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $fatura = Fatura::find()->where(['encomenda_id' => $id])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'metodoEntrega' => $fatura->metodoentrega->descricao]);
    }

    /**
     * Updates an existing Encomenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $status = ['Em processamento' => 'Em processamento', 'Enviado' => 'Enviado', 'Entregue' => 'Entregue', 'Cancelado' => 'Cancelado'];

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'status' => $status,
        ]);
    }

    public function actionDetalhes($id)
    {
        $fatura = Fatura::find()->where(['encomenda_id' => $id])->one();
        $linhas = Linhafatura::find()->where(['fatura_id' => $fatura->id])->all();

        return $this->render('detalhes', [
            'produtos' => $linhas,
        ]);
    }

    /**
     * Finds the Encomenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Encomenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Encomenda::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
