<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\Cupaodesconto;
use common\models\Profile;
use common\models\User;
use common\models\Usocupao;
use Yii;
use yii\rest\ActiveController;

class UsocupaoController extends ActiveController
{
    public $modelClass = 'common\models\Usocupao';

    public function behaviors()
    {
        Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::className(),
        ];
        return $behaviors;
    }

    public function actionAtribuircupaocliente()
    {
        $userID = Yii::$app->params['id'];

        if ($user = User::find()->where(['id' => $userID])->one()) {
            if (!Yii::$app->authManager->checkAccess($user->id, 'cliente')) {
                return 'O Utilizador introduzido não tem permissões de cliente';
            } else {
                $request = Yii::$app->request;
                $cupaoCodigo = $request->post('cupao');
                if ($cupaoCodigo != null) {
                    // Buscar o cupão e o profile na base de dados
                    $cupao = Cupaodesconto::find()->where(['codigo' => $cupaoCodigo])->one();
                    $profile = Profile::find()->where(['user_id' => $user->id])->one();
                    // Verifica se o cupão é válido e não expirou
                    if ($cupao == null) {
                        // Se o cupão for inválido, exibe mensagem de erro
                        return 'Cupão inexistente.';
                    } else if ($cupao && strtotime($cupao->dataFim) < time()) {
                        //Mensagem de erro, caso o cupão tenha expirado
                        return 'Cupão expirado.';
                    } else if (Usocupao::find()->where(['cupaodesconto_id' => $cupao->id, 'profile_id' => $profile->id])->one()) {
                        // Se o cupão for inválido, exibe mensagem de erro
                        return 'Cupão inválido.';
                    } else if ($cupao && strtotime($cupao->dataFim) >= time()) {
                        $usocupao = new Usocupao();
                        $usocupao->cupaodesconto_id = $cupao->id;
                        $usocupao->profile_id = $profile->id;
                        $usocupao->dataUso = date('Y-m-d');
                        if ($usocupao->save()) {
                            return 'Dados guardados com sucesso.';
                        }
                    }
                }
            }
        }
        return 'Não foi possível registar o cupão.';
    }
}