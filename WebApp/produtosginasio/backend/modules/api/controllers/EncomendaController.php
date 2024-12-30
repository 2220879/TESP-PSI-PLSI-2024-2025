<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Carrinhocompra;
use common\models\Encomenda;
use common\models\Profile;
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
            throw new NotFoundHttpException('Perfil do cliente não encontrado.');
        }

        // Agora, buscamos o carrinho do cliente
        $carrinho = Carrinhocompra::findOne(['profile_id' => $profile->id]);
        if (!$carrinho || empty($carrinho->linhascarrinhos)) {
            throw new NotFoundHttpException('Carrinho vazio ou não encontrado.');
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
            throw new ServerErrorHttpException('Erro ao guardar a encomenda.');
        }

        // Retornar a resposta de sucesso
        return [
            'status' => 'success',
            'message' => 'Encomenda realizada com sucesso.',
            'encomenda_id' => $encomenda->id,
        ];
    }

}