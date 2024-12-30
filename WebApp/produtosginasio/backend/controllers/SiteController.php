<?php

namespace backend\controllers;

use common\models\Cupaodesconto;
use common\models\Fatura;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['admin', 'funcionario'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $contagemVendas = Fatura::find()->count();
        $contagemCupoes = Cupaodesconto::find()->count();

        $contagemCupoesValidos = Cupaodesconto::find()
            ->where(['>=', 'dataFim', date('Y-m-d')])  // Verifica se a data de validade é maior ou igual à data atual
            ->count();

        // Obtém os códigos dos cupões válidos
        $cupoesValidos = Cupaodesconto::find()
            ->where(['>=', 'dataFim', date('Y-m-d')])  // Verifica se a data de validade é maior ou igual à data atual
            ->all();

// Recupera os códigos dos cupões válidos
        $codigosCupoes = [];
        foreach ($cupoesValidos as $cupao) {
            $codigosCupoes[] = $cupao->codigo;  // Supondo que o campo que armazena o código do cupão seja 'codigo'
        }
        return $this->render('index', ['contagemVendas' => $contagemVendas, 'contagemCupoes' => $contagemCupoes, 'contagemCupoesValidos' => $contagemCupoesValidos, 'codigosCupoes' => $codigosCupoes]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Verifica diretamente se o usuário tem os papéis de 'admin' ou 'funcionario'
            if (Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'admin') ||
                Yii::$app->authManager->checkAccess(Yii::$app->user->id, 'funcionario')) {
                return $this->goBack();
            }

            // Caso o usuário não tenha permissão, faz logout e redireciona com uma mensagem de erro
            Yii::$app->user->logout();
            Yii::$app->session->setFlash('error', 'Acesso negado. Não tem permissão para aceder o backend.');
            return $this->redirect(['login']);
        }
        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
