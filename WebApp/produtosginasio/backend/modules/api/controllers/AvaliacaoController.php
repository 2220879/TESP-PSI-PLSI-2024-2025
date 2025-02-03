<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Avaliacao;
use common\models\Fatura;
use common\models\Profile;
use common\models\User;
use Yii;
use yii\rest\ActiveController;

class AvaliacaoController extends ActiveController
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

    public function actionAvaliacoes()
    {
        // Obtém o ID do usuário autenticado
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $profile = Profile::find()->where(['user_id' => $user->id])->one();

            if ($profile !== null) {
                $avaliacoes = Avaliacao::find()->where(['profile_id' => $profile->id])->all();

                $baseUrl = 'http://172.22.21.204' . Yii::getAlias('@web/uploads/');
                $resultado = [];

                foreach ($avaliacoes as $avaliacao) {
                    //array das avaliações
                    $avaliacaoData = [
                        'id' => $avaliacao->id,
                        'descricao' => $avaliacao->descricao,
                        'nomeProduto' => $avaliacao->produto->nomeProduto,
                        'imagemProduto' => null,
                        'produto_id' => $avaliacao->produto->id,
                        'profile_id' => $avaliacao->profile_id,
                    ];

                    // Verifica se o produto avaliado tem imagens associadas
                    if (!empty($avaliacao->produto->imagens)) {
                        // Vai buscar a primeira imagem
                        $primeiraImagem = $avaliacao->produto->imagens[0];

                        // Monta a URL completa da imagem
                        $avaliacaoData['imagemProduto'] = $baseUrl . $primeiraImagem->filename;
                    }

                    //adiciona os dados da avaliação ao array de resultados
                    $resultado[] = $avaliacaoData;
                }

                return $resultado;
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Perfil não encontrado.'];
            }
        }

        Yii::$app->response->statusCode = 500;
        return ['message' => 'Utilizador não encontrado ou não autorizado.'];
    }

    public function actionCriaravaliacao()
    {
        $request = Yii::$app->request;
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $profile = Profile::find()->where(['user_id' => $user->id])->one();

            $avaliacao = new Avaliacao();

            $descricao = $request->getBodyParam('descricao');
            $produtoId = $request->getBodyParam('produto');

            $avaliacao->descricao = $descricao;
            $avaliacao->produto_id = $produtoId;
            $avaliacao->profile_id = $profile->id;
            $avaliacao->save();

            return $avaliacao;
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível criar a Avaliação ao produto pretendido.'];
    }

    public function actionAlteraravaliacao()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $request = Yii::$app->request;
            $avaliacaoId = $request->getBodyParam('avaliacao');
            $descricao = $request->getBodyParam('descricao');


            $profile = Profile::find()->where(['user_id' => $user->id])->one();
            $avaliacao = Avaliacao::find()->where(['id' => $avaliacaoId, 'profile_id' => $profile->id])->one();

            if ($avaliacao != null) {
                $avaliacao->descricao = $descricao;
                $avaliacao->update();
                return 'Avaliação alterada com sucesso!';
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Avaliação não encontrada.'];
            }
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível alterar a avaliação do produto pretendido.'];
    }

    public function actionApagaravaliacao()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            $request = Yii::$app->request;
            $avaliacaoId = $request->getBodyParam('avaliacao');

            $profile = Profile::find()->where(['user_id' => $user->id])->one();
            $avaliacao = Avaliacao::find()->where(['id' => $avaliacaoId, 'profile_id' => $profile->id])->one();

            if ($avaliacao != null) {
                $avaliacao->delete();
                return 'Avaliação apagada com sucesso!';
            } else {
                Yii::$app->response->statusCode = 400;
                return ['message' => 'Avaliação não encontrada.'];
            }
        }

        Yii::$app->response->statusCode = 400;
        return ['message' => 'Não foi possível apagar a avaliação do produto pretendido.'];
    }
}