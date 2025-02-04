<?php

namespace frontend\controllers;

use common\models\Avaliacao;
use common\models\Categoria;
use common\models\Favorito;
use common\models\Genero;
use common\models\Imagem;
use common\models\Marca;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProdutoController implements the CRUD actions for Produto model.
 */
class ProdutoController extends Controller
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
                    'only' => ['index', 'view', 'filtrar', 'detalhes', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'filtrar', 'detalhes', 'model'],
                            'allow' => true,
                            'roles' => ['?', 'cliente'],
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
     * Lists all Produto models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Buscar todos os produtos disponíveis
        $produtos = Produto::find()->all();

        //buscar todas as categorias
        $categorias = Categoria::find()->all();
        //buscar todas as marcas
        $marcas = Marca::find()->all();
        //buscar todos os generos
        $generos = Genero::find()->all();

        return $this->render('index', [
            'produtos' => $produtos,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'generos' => $generos,
        ]);
    }

    public function actionView($id)
    {
        // Busca o produto pelo ID
        $model = Produto::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Produto não encontrado.');
        }

        // Verifica se o usuário está logado
        $isFavorited = false;
        if (!Yii::$app->user->isGuest) {
            $user_id = Yii::$app->user->identity->id;
            $profile = Profile::findOne(['user_id' => $user_id]);

            if ($profile) {
                $isFavorited = Favorito::find()->where([
                    'produto_id' => $model->id,
                    'profile_id' => $profile->id,
                ])->exists();
            }
        }

        // Renderiza a view do produto e passa a variável $isFavorited
        return $this->render('view', [
            'model' => $model,
            'isFavorited' => $isFavorited,
        ]);
    }

    public function actionFiltrar($categoria, $marca, $genero, $pesquisar)
    {
        // Buscar todos os produtos disponíveis
        $produtos = Produto::find();

        //buscar todas as categorias
        $categorias = Categoria::find()->all();
        //buscar todas as marcas
        $marcas = Marca::find()->all();
        //buscar todos os generos
        $generos = Genero::find()->all();

        // Aplicar o filtro por categoria se existir
        if ($categoria != null) {
            $produtos->andWhere(['categoria_id' => $categoria]);
        }

        // Aplicar o filtro por marca se existir
        if ($marca != null) {
            $produtos->andWhere(['marca_id' => $marca]);
        }

        // Aplicar o filtro por genero se existir
        if ($genero != null) {
            $produtos->andWhere(['genero_id' => $genero]);
        }

        // Aplicar o filtro pelo nome se existir
        if ($pesquisar != null) {
            $produtos->andWhere(['like', 'nomeProduto', "%$pesquisar%", false]);
        }

        return $this->render('index', [
            'produtos' => $produtos->all(),
            'categorias' => $categorias,
            'marcas' => $marcas,
            'generos' => $generos,
            'categoriaSelecionada' => $categoria,
            'marcaSelecionada' => $marca,
            'generoSelecionado' => $genero,
        ]);
    }

    /**
     * Displays a single Produto model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDetalhes($id)
    {
        // Verifica se o produto existe
        $produto = $this->findModel($id);
        if (!$produto) {
            throw new NotFoundHttpException('Produto não encontrado.');
        }

        // Busca as avaliações do produto
        $avaliacoes = Avaliacao::find()->where(['produto_id' => $id])->all();

        // Busca os tamanhos disponíveis para o produto
        $tamanhos = ProdutosHasTamanho::find()
            ->where(['produto_id' => $id])
            ->andWhere(['>', 'quantidade', 0])
            ->all();

        // Verifica se há qualquer tamanho com quantidade disponível
        $temTamanhoDisponivel = array_filter($tamanhos, fn($produtoHasTamanho) => $produtoHasTamanho->quantidade > 0);

        // Verifica se o produto está indisponível
        $produtoIndisponivel = empty($tamanhos) && $produto->quantidade == 0 || empty($tamanhos) && $produto->quantidade > 0 && $produto->quantidade == 0;

        // Renderiza a página de detalhes, passando os dados necessários
        return $this->render('detalhes', [
            'model' => $produto,
            'imagens' => Imagem::find()->where(['produto_id' => $id])->all(),
            'tamanhos' => $tamanhos,
            'avaliacao' => new Avaliacao(),
            'avaliacoes' => $avaliacoes,
            'produtoIndisponivel' => $produtoIndisponivel,
        ]);
    }


    /**
     * Finds the Produto model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Produto the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Produto::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
