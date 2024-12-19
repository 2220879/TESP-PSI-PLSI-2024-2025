<?php
namespace frontend\controllers;
use common\models\Cupaodesconto;
use common\models\Metodoentrega;
use frontend\models\Carrinhocompra;
use common\models\Metodopagamento;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FinalizarcompraController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex($carrinho_id)
    {
        // Verificar se o carrinho existe
        $carrinho = Carrinhocompra::findOne($carrinho_id);
        if (!$carrinho) {
            throw new NotFoundHttpException('Carrinho não encontrado.');
        }

        // Obter os métodos de pagamento e de entrega (em vigor)
        $metodosPagamento = Metodopagamento::find()->all();
        $metodosEntrega = Metodoentrega::find()->where(['vigor' => 1])->all();

        // Calcular valores do carrinho
        $valorProdutos = $carrinho->valorTotal;
        $custoEnvio = 0.00;
        $desconto = 0.00;
        $ValorPoupado = 0.00;

        $cupao = null;
        if (Yii::$app->request->isPost) {
            // Verificar se o cupão foi enviado
            $cupao = Yii::$app->request->post('cupao');
            if ($cupao) {
                // Buscar o cupão na base de dados
                $cupao = Cupaodesconto::findOne(['codigo' => $cupao]);

                // Verifica se o cupão é válido e não expirou
                if ($cupao && strtotime($cupao->dataFim) >= time()) {
                    // Calcular o valor poupado com base do desconto do cupão
                    $ValorPoupado = ($cupao->desconto * $valorProdutos);
                    $desconto = $cupao->desconto;
                    // Guarda cupão na sessão
                    Yii::$app->session->set('cupao', $cupao);
                } else {
                    // Se o cupão for inválido, exibe mensagem de erro
                    Yii::$app->session->setFlash('error', "Cupão inválido");
                    // Remover cupão da sessão se inválido
                    Yii::$app->session->remove('cupao');
                }
            }
        }

            // Capturar o método de entrega selecionado e calcular o custo de envio
            if ($metodoEntregaId = Yii::$app->request->post('metodo_entrega')) {
                $metodoEntrega = Metodoentrega::findOne($metodoEntregaId);
                if ($metodoEntrega) {
                    $custoEnvio = $metodoEntrega->preco;
                }
            }


        // Calcular o valor final
        $valorFinal = ($valorProdutos - $ValorPoupado) + $custoEnvio;

        // Renderizar a página de finalização de compra
        return $this->render('index', [
            'carrinho' => $carrinho,
            'metodosPagamento' => $metodosPagamento,
            'metodosEntrega' => $metodosEntrega,
            'valorProdutos' => $valorProdutos,
            'desconto' => $desconto,
            'custoEnvio' => $custoEnvio,
            'valorFinal' => $valorFinal,
            'cupao' => $cupao,
            'ValorPoupado' => $ValorPoupado,
        ]);
    }

}