<?php

namespace frontend\controllers;

use common\models\Encomenda;
use common\models\EncomendaSearch;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Profile;
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
                    'only' => ['index', 'detalhes', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'detalhes', 'model'],
                            'allow' => true,
                            'roles' => ['cliente'],
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

    /**
     * Lists all Encomenda models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $profile = Profile::find()->where(['user_id' => $id])->one();
        $encomendas = Encomenda::find()->where(['profile_id' => $profile->id])->all();

        return $this->render('index', [
            'encomendas' => $encomendas,
        ]);
    }

    /**
     * Displays a single Encomenda model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetalhes($id)
    {
        $fatura = Fatura::find()->where(['encomenda_id' => $id])->one();
        return $this->render('detalhes', [
            'encomenda' => $this->findModel($id),
            'Linhasfatura' => Linhafatura::find()->where(['fatura_id' => $fatura->id])->all(),
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
