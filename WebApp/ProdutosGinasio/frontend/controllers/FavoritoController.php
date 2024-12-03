<?php

namespace frontend\controllers;

use frontend\models\Favorito;
use frontend\models\FavoritoSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FavoritoController implements the CRUD actions for Favorito model.
 */
class FavoritoController extends Controller
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
                    'only' => ['index', 'create', 'model', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'model', 'delete'],
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
     * Lists all Favorito models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Verifica se o usuário está logado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        // Obtém o ID do usuário logado
        $user_id = Yii::$app->user->identity->id;

        // Busca o perfil do usuário logado
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Perfil do usuário não encontrado.');
        }

        $profile_id = $profile->id;

        // Vai buscar todos os favoritos do perfil
        $favoritos = Favorito::find()->where(['profile_id' => $profile_id])->all();

        return $this->render('index', [
            'favoritos' => $favoritos,
        ]);
    }

    /**
     * Creates a new Favorito model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($produto_id)
    {
        //Verifica de o user está autênticado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //Obtém o ID do user logado
        $user_id = Yii::$app->user->identity->id;


        //Vai buscar o perfil do user
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Perfil do user não encontrado.');
        }

        $profile_id = $profile->id;

        // Vai buscar todos os IDs dos produtos que já estão nos favoritos do user
        $produtosFavoritos = Favorito::find()
            ->select('produto_id')
            ->where(['profile_id' => $profile_id])
            ->column(); // Retorna uma lista de IDs

        // Verifica se o produto já está nos favoritos do user
        if (!in_array($produto_id, $produtosFavoritos)) {
            // Se o produto não está nos favoritos, cria um novo registro nos favoritos
            $model = new Favorito();
            $model->produto_id = $produto_id;
            $model->profile_id = $profile_id;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produto adicionado aos favoritos.');
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao adicionar o produto aos favoritos.');
            }

        } else {
            Yii::$app->session->setFlash('info', 'Este produto já está nos favoritos.');
            return $this->redirect(['produto/detalhes', 'id' => $produto_id]);
        }

        return $this->redirect(['produto/detalhes', 'id' => $produto_id]);

    }

    /**
     * Deletes an existing Favorito model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($produto_id)
    {
        // Verifica se o user está autenticado
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // Obtém o ID do usuário logado
        $user_id = Yii::$app->user->identity->id;

        // Busca o perfil do usuário
        $profile = \common\models\Profile::findOne(['user_id' => $user_id]);
        if (!$profile) {
            throw new NotFoundHttpException('Perfil do usuário não encontrado.');
        }

        $profile_id = $profile->id;

        // Busca o favorito correspondente ao produto e perfil
        $favorito = Favorito::findOne(['produto_id' => $produto_id, 'profile_id' => $profile_id]);

        if ($favorito) {
            if ($favorito->delete() !== false) {
                Yii::$app->session->setFlash('success', 'Produto removido dos favoritos.');
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao tentar remover o produto dos favoritos.');
            }
        } else {
            Yii::$app->session->setFlash('info', 'Produto não encontrado nos favoritos.');
        }

        // Redireciona para a página de detalhes do produto
        return $this->redirect(['produto/detalhes', 'id' => $produto_id]);
    }


    /**
     * Finds the Favorito model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Favorito the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Favorito::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
