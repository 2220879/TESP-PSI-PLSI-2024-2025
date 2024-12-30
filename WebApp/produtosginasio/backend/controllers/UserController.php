<?php

namespace backend\controllers;

use backend\models\Linhacompra;
use backend\models\UserForm;
use common\models\Avaliacao;
use common\models\Carrinhocompra;
use common\models\Encomenda;
use common\models\Fatura;
use common\models\Favorito;
use common\models\Linhacarrinho;
use common\models\Linhafatura;
use common\models\Profile;
use common\models\User;
use common\models\UserSearch;
use common\models\Usocupao;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                    'only' => ['index', 'create', 'view', 'update', 'delete', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'view', 'update', 'delete', 'model'],
                            'allow' => true,
                            'roles' => ['admin'],
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        //criar a instância do User
        $searchModel = new UserSearch();
        //seleciona todos os dados da tabela de utilizadores
        $dataProvider = $searchModel->search($this->request->queryParams);

        //faz render da página index com todos os utilizadores armazenados na base dados
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        //seleciona o utilizador pretendido
        $profile = Profile::find()->where(['user_id' => $id])->one();

        //faz render da página view com os dados do utilizador selecionado
        return $this->render('view', [
            'model' => $this->findModel($id),
            'profile' => $profile,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        //instancia o UserForm
        $model = new UserForm();

        //definir o cenário de criação
        $model->scenario = UserForm::SCENARIO_CREATE;

        //guarda os estados disponíveis para um user
        $status = [User::STATUS_ACTIVE => 'Ativo', User::STATUS_INACTIVE => 'Inativo'];

        //guarda as roles disponíveis para um user
        $roles = ['admin' => 'Adminstrador', 'funcionario' => 'Funcionário', 'cliente' => 'Cliente'];

        //verifica se correu tudo bem com a criação do user e o respetivo perfil
        if ($model->load(Yii::$app->request->post()) && ($userID = $model->create())) {
            //redireciona para a página view do user criado
            return $this->redirect(['view', 'id' => $userID]);
        }

        //faz render da página de create e manda os dados principais para o user e perfil, e os respetivos status
        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
            'status' => $status,
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        //se o utilizador a editar não for o 1
        if ($id != 1) {
            //seleciona o user pretendido
            $user = User::findOne($id);

            //seleciona o perfil do user pretendido
            $profile = Profile::findOne(['user_id' => $id]);

            //instancia o UserForm
            $model = new UserForm();

            //guarda os estados disponíveis para um user
            $status = [User::STATUS_ACTIVE => 'Ativo', User::STATUS_INACTIVE => 'Inativo'];

            //guarda as roles disponíveis para um user
            $roles = ['admin' => 'Adminstrador', 'funcionario' => 'Funcionário', 'cliente' => 'Cliente'];

            //verifica se correu tudo bem com o carregamento dos dados do form
            if ($model->load(Yii::$app->request->post())) {

                //valida toda a informação dos campos definidos como dados únicos na base dados
                //verifica se os dados inseridos não combinam com o registo que existe na base dados
                if (User::find()->where(['username' => $model->username])->andWhere(['!=', 'id', $id])->exists()) {
                    //verifica se os novos dados inseridos existem na base dados
                    if (User::find()->where(['username' => $model->username])->exists()) {
                        $model->addError('username', 'Username já utilizado!');
                    }
                } else if (User::find()->where(['email' => $model->email])->andWhere(['!=', 'id', $id])->exists()) {
                    if (User::find()->where(['email' => $model->email])->exists()) {
                        $model->addError('email', 'Email já utilizado!');
                    }
                } else if (Profile::find()->where(['nif' => $model->nif])->andWhere(['!=', 'user_id', $id])->exists()) {
                    if (Profile::find()->where(['nif' => $model->nif])->exists()) {
                        $model->addError('nif', 'Nif já utilizado!');
                    }
                } else if (Profile::find()->where(['telefone' => $model->telefone])->andWhere(['!=', 'user_id', $id])->exists()) {
                    if (Profile::find()->where(['telefone' => $model->telefone])->exists()) {
                        $model->addError('telefone', 'Telefone já utilizado!');
                    }
                    //se não haver problema nenhum com a informação inserida
                } else {
                    //se update for concluido com sucesso
                    if ($model->update($id)) {
                        //redireciona para a página view do user alterado
                        return $this->redirect(['view', 'id' => $id]);

                        //caso contrário faz render da vista update
                    } else {
                        //faz render da página de update e manda os dados principais para o user e perfil, e os respetivos status
                        return $this->render('update', [
                            'model' => $model,
                            'user' => $user,
                            'profile' => $profile,
                            'roles' => $roles,
                            'status' => $status,
                        ]);
                    }
                }
            }

            //faz render da página de update e manda os dados principais para o user e perfil, e os respetivos status
            return $this->render('update', [
                'model' => $model,
                'user' => $user,
                'profile' => $profile,
                'roles' => $roles,
                'status' => $status,
            ]);
        } else {
            //se for o 1 faz redirect para o index
            return $this->redirect(['index']);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //se o user selecionado não for o 1
        if ($id != 1) {
            //selecionar o perfil do utilizador
            $profile = Profile::findOne(['user_id' => $id]);

            //carrinho de compras
            $carrinho = Carrinhocompra::find()->where(['profile_id' => $profile->id])->one();

            if ($carrinho != null) {
                //linhas carrinho de compras
                $linhasCarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id])->all();
                if ($linhasCarrinho != null) {
                    foreach ($linhasCarrinho as $linha) {
                        $linha->delete();
                    }
                }

                $carrinho->delete();
            }

            //avaliações produtos criados pelo Utilizador
            $avaliacoes = Avaliacao::find()->where(['profile_id' => $profile->id])->all();

            if ($avaliacoes != null) {

                foreach ($avaliacoes as $avaliacao) {
                    $avaliacao->delete();
                }
            }

            //faturas
            $faturas = Fatura::find()->where(['profile_id' => $profile->id])->all();

            if ($faturas != null) {
                foreach ($faturas as $fatura) {
                    $linhasFatura = Linhafatura::find()->where(['fatura_id' => $fatura->id])->all();

                    //verifica se há linhas associadas à fatura
                    if ($linhasFatura != null) {
                        foreach ($linhasFatura as $linhaFatura) {
                            $linhaFatura->delete();
                        }
                    }

                    $fatura->delete();
                }
            }

            //encomendas do Utilizador
            $encomendas = Encomenda::find()->where(['profile_id' => $profile->id])->all();

            if ($encomendas != null) {

                foreach ($encomendas as $encomenda) {
                    $encomenda->delete();
                }
            }

            //favoritos do Utilizador
            $favoritos = Favorito::find()->where(['profile_id' => $profile->id])->all();

            if ($favoritos != null) {

                foreach ($favoritos as $favorito) {
                    $favorito->delete();
                }
            }

            //cupões utilizados pelo Utilizador - (Tabela Uso Cupoes)
            $cupoes = Usocupao::find()->where(['profile_id' => $profile->id])->all();

            if ($cupoes != null) {

                foreach ($cupoes as $cupao) {
                    $cupao->delete();
                }
            }

            //apagar registo de perfil
            $profile->delete();
            //apagar o utilizador na base dados
            $this->findModel($id)->delete();
        }

        //redireciona para a página de index
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        //se econtrar o modelo de dados do Utilizador selecionado
        if (($model = User::findOne(['id' => $id])) !== null) {
            //devolve o modelo de dados
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}