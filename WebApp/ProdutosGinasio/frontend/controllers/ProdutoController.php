<?php

namespace frontend\controllers;

use common\models\Categoria;
use common\models\Genero;
use common\models\Imagem;
use common\models\Marca;
use common\models\Produto;
use common\models\ProdutoSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        return $this->render('detalhes', [
            'model' => $this->findModel($id),
            'imagens' => Imagem::find()->where(['produto_id' => $id])->all(),
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
