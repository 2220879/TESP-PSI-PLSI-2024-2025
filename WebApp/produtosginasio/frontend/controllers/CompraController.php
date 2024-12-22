<?php

namespace frontend\controllers;

use common\models\Fatura;
use common\models\FaturaSearch;
use common\models\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaturaController implements the CRUD actions for Fatura model.
 */
class CompraController extends Controller
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
                    'only' => ['index', 'view', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'model'],
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
     * Lists all Fatura models.
     *
     * @return string
     */
    public function actionIndex($id)
    {
        $profile = Profile::find()->where(['user_id' => $id])->one();
        $faturas = Fatura::find()->where(['profile_id' => $profile->id])->all();

        return $this->render('index', [
            'faturas' => $faturas,
        ]);
    }

    /**
     * Displays a single Fatura model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $fatura = Fatura::findOne($id);

        if (!$fatura) {
            throw new NotFoundHttpException('Fatura não encontrada.');
        }

        //caminho para o PDF da fatura
        $ficheiro = Yii::getAlias('@common/faturas/') . 'fatura_' . $fatura->id . '.pdf';

        return Yii::$app->response->sendFile($ficheiro, 'fatura_' . $fatura->id . '.pdf', [
            'forceDownload' => true,
            'mimeType' => 'application/pdf',
        ]);
    }

    /**
     * Finds the Fatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Fatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fatura::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
