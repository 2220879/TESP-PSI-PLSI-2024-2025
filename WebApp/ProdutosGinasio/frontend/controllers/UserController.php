<?php

namespace frontend\controllers;

use backend\models\UserForm;
use common\models\Profile;
use common\models\User;
use common\models\UserSearch;
use frontend\models\SignupForm;
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
                    'only' => ['model', 'view', 'update'],
                    'rules' => [
                        [
                            'actions' => ['model', 'view', 'update'],
                            'allow' => true,
                            'roles' => ['cliente'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                    ],
                ],
            ]
        );
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'user' => $this->findModel($id),
            'profile' => Profile::find()->where(['user_id' => $id])->one(),
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
        //seleciona o user pretendido
        $user = User::findOne($id);

        //seleciona o perfil do user pretendido
        $profile = Profile::findOne(['user_id' => $id]);

        //instancia o SignupForm
        $model = new SignupForm();

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
                    ]);
                }
            }
        }

        //faz render da página de update e manda os dados principais para o user e perfil, e os respetivos status
        return $this->render('update', [
            'model' => $model,
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
