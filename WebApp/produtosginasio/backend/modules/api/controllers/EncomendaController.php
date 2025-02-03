<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Carrinhocompra;
use common\models\Encomenda;
use common\models\Fatura;
use common\models\Linhafatura;
use common\models\Produto;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class EncomendaController extends ActiveController
{
    public $modelClass = 'common\models\Avaliacao';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function actionCriarencomenda()
    {

        // Vai obter o user autenticado
        $userId = Yii::$app->params['id'];

        // Vai buscar o perfil associado ao user
        $profile = Profile::findOne(['user_id' => $userId]); // Vai buscar o perfil pelo user_id associado ao user autenticado
        if (!$profile) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Perfil do cliente não encontrado.'];
        }

        // Agora, buscamos o carrinho do cliente
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho || empty($carrinho->linhascarrinhos)) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Carrinho vazio ou não encontrado.'];
        }

        // Obtém os dados da requisição usando getBodyParam
        $email = Yii::$app->request->getBodyParam('email');
        $morada = Yii::$app->request->getBodyParam('morada');
        $telefone = Yii::$app->request->getBodyParam('telefone');


        // Criação da encomenda
        $encomenda = new Encomenda();
        $encomenda->data = date('Y-m-d');
        $encomenda->hora = date('H:i:s');
        $encomenda->morada = $morada;
        $encomenda->telefone = $telefone;
        $encomenda->email = $email;
        $encomenda->estadoEncomenda = "Em processamento";
        $encomenda->profile_id = $profile->id;

        if (!$encomenda->save()) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Erro ao guardar a encomenda.'];
        }

        // Retornar a resposta de sucesso
        return [
            'status' => 'success',
            'message' => 'Encomenda realizada com sucesso.',
            'encomenda_id' => $encomenda->id,
        ];
    }

    public function actionEncomendas()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $profile = Profile::find()->where(['user_id' => $userID])->one();
            $encomendas = Encomenda::find()->where(['profile_id' => $profile->id])->all();

            $resultado = [];

            foreach ($encomendas as $encomenda) {
                //array das encomendas
                $encomendaData = [
                    'encomendaID' => $encomenda->id,
                    'data' => date('d-m-Y', strtotime($encomenda->data)),
                    'hora' => $encomenda->hora,
                    'morada' => $encomenda->morada,
                    'telefone' => $encomenda->telefone,
                    'email' => $encomenda->email,
                    'estadoEncomenda' => $encomenda->estadoEncomenda,
                    'profile_id' => $encomenda->profile_id,
                ];

                //adiciona os dados das encomendas ao array de resultados
                $resultado[] = $encomendaData;
            }
            return $resultado;
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter as encomendas.'];

    }

    public function actionDetalhesencomenda()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $request = Yii::$app->request;
            $encomendaID = $request->getBodyParam('encomenda');

            // Buscar o perfil e a encomenda
            $profile = Profile::find()->where(['user_id' => $userID])->one();
            $encomenda = Encomenda::find()->where(['id' => $encomendaID, 'profile_id' => $profile->id])->one();

            //fatura associada
            $fatura = Fatura::find()->where(['encomenda_id' => $encomendaID, 'profile_id' => $profile->id])->one();

            //linhas da fatura
            $linhasFatura = Linhafatura::find()->where(['fatura_id' => $fatura->id])->all();
            $produtos = [];

            foreach ($linhasFatura as $linha) {
                //guardar os dados do produto no array
                $produtos[] = [
                    'nomeProduto' => $linha->nomeProduto,
                    'preco' => number_format($linha->subtotal, 2, ',', '.'),
                    'quantidade' => $linha->quantidade,
                ];
            }

            //detalhes da encomenda
            $encomendaDetalhes = [
                'encomendaID' => $encomenda->id,
                'data' => date('d-m-Y', strtotime($encomenda->data)),
                'hora' => $encomenda->hora,
                'morada' => $encomenda->morada,
                'telefone' => $encomenda->telefone,
                'email' => $encomenda->email,
                'estadoEncomenda' => $encomenda->estadoEncomenda,
                'profile_id' => $encomenda->profile_id,
            ];

            return [
                'encomenda' => $encomendaDetalhes,
                'produtos' => $produtos
            ];
        }
        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível obter os detalhes da encomenda pretendida.'];
    }

}