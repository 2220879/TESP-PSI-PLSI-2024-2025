<?php

namespace backend\controllers;

use common\models\Avaliacao;
use common\models\AvaliacaoSearch;
use common\models\Imagem;
use common\models\ProdutosHasTamanho;
use common\models\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AvaliacaoController implements the CRUD actions for Avaliacao model.
 */
class AvaliacaoController extends Controller
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
                    'only' => ['model', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['model', 'delete'],
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

    public function actionDelete($id)
    {
        //vai buscar o produto onde vai eliminar a avaliação
        $produto = $this->findModel($id)->produto_id;

        //apagar a avaliação
        $this->findModel($id)->delete();

        //redireciona para a página de detalhes do produto
        return $this->redirect(['produto/view', 'id' => $produto,
            'model' => $produto,
            'tamanhos' => ProdutosHasTamanho::find()->where(['produto_id' => $id])->all(),
            'avaliacoes' => Avaliacao::find()->where(['produto_id' => $id])->all(),
        ]);
    }

    /**
     * Finds the Avaliacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Avaliacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Avaliacao::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
