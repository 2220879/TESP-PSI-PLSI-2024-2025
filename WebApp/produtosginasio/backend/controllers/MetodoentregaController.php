<?php

namespace backend\controllers;

use common\models\Metodoentrega;
use common\models\MedodoentregaSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MetodoentregaController implements the CRUD actions for Metodoentrega model.
 */
class MetodoentregaController extends Controller
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
     * Lists all Metodoentrega models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MedodoentregaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Metodoentrega model.
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
     * Creates a new Metodoentrega model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Metodoentrega();
        $vigor = [0=>'não vigor', 1=>'vigor'];

        if ($this->request->isPost) {
            // Carregar os dados do formulário no modelo
            $model->load($this->request->post());

            // Verifica se já existe um método de entrega com a mesma descrição
            $metodoExistente = Metodoentrega::find()->where(['descricao' => $model->descricao])->exists();

            if ($metodoExistente) {
                Yii::$app->session->setFlash('error', 'Este método de entrega já existe.');
            } else {
                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
            'vigor' => $vigor,
        ]);
    }

    /**
     * Updates an existing Metodoentrega model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Metodoentrega::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Método de entrega não encontrado.');
        }

        $vigor = [0 => 'Não vigor', 1 => 'Vigor'];

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Verifica se outro método de entrega tem a mesma descrição
            $metodoExistente = Metodoentrega::find()
                ->where(['descricao' => $model->descricao])
                ->andWhere(['!=', 'id', $model->id]) // Exclui o próprio modelo da verificação
                ->exists();

            if ($metodoExistente) {
                Yii::$app->session->setFlash('error', 'Outro método de entrega com esta descrição já existe.');
            } else {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Método de entrega atualizado com sucesso.');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'vigor' => $vigor,
        ]);
    }

    /**
     * Deletes an existing Metodoentrega model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Metodoentrega model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Metodoentrega the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Metodoentrega::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
