<?php

namespace backend\controllers;

use common\models\Fatura;
use common\models\Metodopagamento;
use common\models\MedodopagamentoSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MetodopagamentoController implements the CRUD actions for Metodopagamento model.
 */
class MetodopagamentoController extends Controller
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
     * Lists all Metodopagamento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MedodopagamentoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Metodopagamento model.
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
     * Creates a new Metodopagamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Metodopagamento();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Verifica se já existe um método de pagamento com o mesmo nome
                $metodoExistente = Metodopagamento::find()->where(['metodoPagamento' => $model->metodoPagamento])->exists();

                if ($metodoExistente) {
                    Yii::$app->session->setFlash('error', 'Este método de pagamento já existe.');
                } else {
                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Metodopagamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Verifica se outro método de pagamento já existe com o mesmo nome
                $metodoExistente = Metodopagamento::find()
                    ->where(['metodoPagamento' => $model->metodoPagamento])
                    ->andWhere(['!=', 'id', $id]) // Exclui o registro atual da verificação
                    ->exists();

                if ($metodoExistente) {
                    Yii::$app->session->setFlash('error', 'Outro método de pagamento com este nome já existe.');
                } else {
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Método de pagamento atualizado com sucesso.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Metodopagamento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($id != 1 && $id != 2) {
            $model = $this->findModel($id);

            //verificar se existem produtos relacionados à marca
            if (Fatura::find()->where(['metodopagamento_id' => $model->id])->exists()) {
                Yii::$app->session->setFlash('error', 'Não é possível apagar este método pagamento, devido a estar a ser utilizado.');
                return $this->redirect(['index']);
            }

            $model->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Metodopagamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Metodopagamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Metodopagamento::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
