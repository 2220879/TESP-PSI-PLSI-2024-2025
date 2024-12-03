<?php

namespace frontend\controllers;

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
                    'only' => ['create', 'update', 'model', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'model', 'delete'],
                            'allow' => true,
                            'roles' => ['cliente'],
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
     * Creates a new Avaliacao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        //cria uma nova instância da Avaliacao
        $avaliacao = new Avaliacao();

        //buscar todas as avaliações do produto
        $avaliacoes = Avaliacao::find()->where(['produto_id' => $id])->all();

        if ($avaliacao->load(Yii::$app->request->post())) {
            $avaliacao->produto_id = $id;

            $user_id = Yii::$app->user->identity->id;
            $profile = Profile::findOne(['user_id' => $user_id]);
            $avaliacao->profile_id = $profile->id;

            if ($avaliacao->save()) {
                Yii::$app->session->setFlash('success', 'Avaliação adicionada com sucesso!');
                return $this->redirect(['produto/detalhes', 'id' => $id,
                    'imagens' => Imagem::find()->where(['produto_id' => $id])->all(),
                    'tamanhos' => ProdutosHasTamanho::find()->where(['produto_id' => $id])->andWhere(['>', 'quantidade', 0])->all(),
                    'avaliacao' => $avaliacao,
                    'avaliacoes' => $avaliacoes,
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'A avaliação não foi gravada!');
            }
        }

        //faz render da página de Detalhes do produto
        return $this->render('produto/detalhes', ['model' => $this->findModel($id),
            'imagens' => Imagem::find()->where(['produto_id' => $id])->all(),
            'tamanhos' => ProdutosHasTamanho::find()->where(['produto_id' => $id])->andWhere(['>', 'quantidade', 0])->all(),
            'avaliacao' => $avaliacao,
            'avaliacoes' => $avaliacoes,]);

    }

    /**
     * Updates an existing Avaliacao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        //cria uma nova instância da Avaliacao
        $avaliacao = new Avaliacao();

        //vai buscar o produto onde vai eliminar a avaliação
        $produto = $this->findModel($id)->produto_id;

        //buscar todas as avaliações do produto
        $avaliacoes = Avaliacao::find()->where(['produto_id' => $produto])->all();

        //edita a descrição da avaliação
        $model = $this->findModel($id);

        //se o pedido for POST e gravou com sucesso
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['produto/detalhes', 'id' => $produto,
                'imagens' => Imagem::find()->where(['produto_id' => $produto])->all(),
                'tamanhos' => ProdutosHasTamanho::find()->where(['produto_id' => $produto])->andWhere(['>', 'quantidade', 0])->all(),
                'avaliacao' => $avaliacao,
                'avaliacoes' => $avaliacoes,
            ]);
        }

        //faz render da página
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Avaliacao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //cria uma nova instância da Avaliacao
        $avaliacao = new Avaliacao();

        //vai buscar o produto onde vai eliminar a avaliação
        $produto = $this->findModel($id)->produto_id;

        //buscar todas as avaliações do produto
        $avaliacoes = Avaliacao::find()->where(['produto_id' => $produto])->all();

        //apagar a avaliação
        $this->findModel($id)->delete();

        //redireciona para a página de detalhes do produto
        return $this->redirect(['produto/detalhes', 'id' => $produto,
            'imagens' => Imagem::find()->where(['produto_id' => $produto])->all(),
            'tamanhos' => ProdutosHasTamanho::find()->where(['produto_id' => $produto])->andWhere(['>', 'quantidade', 0])->all(),
            'avaliacao' => $avaliacao,
            'avaliacoes' => $avaliacoes,
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
