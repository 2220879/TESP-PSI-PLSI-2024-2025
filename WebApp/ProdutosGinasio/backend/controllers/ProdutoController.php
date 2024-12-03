<?php

namespace backend\controllers;

use backend\models\ImagemForm;
use common\models\Avaliacao;
use common\models\Imagem;
use common\models\Produto;
use common\models\ProdutoSearch;
use common\models\Marca;
use common\models\Categoria;
use common\models\Genero;
use common\models\ProdutosHasTamanho;
use common\models\Tamanho;
use common\models\Iva;
use frontend\models\Favorito;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
                    'only' => ['index', 'create', 'view', 'update', 'updateimagem', 'delete', 'deleteimagem', 'upload', 'model'],
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'view', 'update', 'updateimagem', 'delete', 'deleteimagem', 'upload', 'model'],
                            'allow' => true,
                            'roles' => ['admin', 'funcionario'],
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
     * Lists all Produto models.
     *
     * @return string
     */
    public function actionIndex()
    {
        //criar a instância do Produto
        $searchModel = new ProdutoSearch();
        //seleciona todos os dados da tabela de produtos
        $dataProvider = $searchModel->search($this->request->queryParams);

        //faz render da página index com todos os produtos armazenados na base dados
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Produto model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        //faz render da página view com os dados do produto selecionado
        return $this->render('view', [
            'model' => $this->findModel($id),
            'tamanhos' => ProdutosHasTamanho::find()->where(['produto_id' => $id])->all(),
            'avaliacoes' => Avaliacao::find()->where(['produto_id' => $id])->all(),
        ]);
    }

    /**
     * Creates a new Produto model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        //iniciliaza a variavel para a criação de um user
        $model = new Produto();
        //iniciliaza a variavel do modelo ImagemForm
        $imagemForm = new ImagemForm();

        //buscar todos os dados destas categorias de dados
        $marcas = Marca::find()->select(['nomeMarca', 'id'])->indexBy('id')->column();
        $categorias = Categoria::find()->select(['nomeCategoria', 'id'])->indexBy('id')->column();
        $tamanhos = Tamanho::find()->select(['referencia', 'id'])->indexBy('id')->column();
        $generos = Genero::find()->select(['referencia', 'id'])->indexBy('id')->column();
        $ivas = Iva::find()->where(['vigor' => 1])->select(['percentagem', 'id'])->indexBy('id')->column();

        //se o pedido for POST
        if ($this->request->isPost) {
            //guardar na base dados o novo produto
            if ($model->load($this->request->post()) && $model->save()) {

                //selecionar as quantidades dos tamanhos
                $quantidades = Yii::$app->request->post('quantidade_tamanho');

                //se existir tamanhos associados ao novo produto
                if (is_array($quantidades) && !empty($quantidades) && array_filter($quantidades)) {
                    //atribui 0 à quantidade da tabela de produtos
                    $model->quantidade = 0;

                    //percorre todos os campos preenchidos na vista
                    foreach ($quantidades as $tamanho_id => $quantidade) {
                        //criar o registo na base dados
                        $tamanho = new ProdutosHasTamanho();
                        $tamanho->produto_id = $model->id;
                        $tamanho->tamanho_id = $tamanho_id;
                        $tamanho->quantidade = $quantidade;
                        //atribui a respetiva quantidade do produto na tabela de Produtos (tira o 0 e coloca a quantidade correta)
                        $model->quantidade += (int)$quantidade;

                        //guardar o registo na tabela de ProdutosHasTamanho
                        $tamanho->save();
                    }
                    //guardar o registo na tabela de Produtos
                    $model->save();
                }

                //chamar o metodo para tatar do upload das imagens
                $this->actionUpload($model->id, $imagemForm);

                //redireciona para a página index
                return $this->redirect(['index']);
            }
        } else {
            //se ocorrer algo de errado carrega os dados por default
            $model->loadDefaultValues();
        }

        //faz o render da pagina create com os respetivos dados
        return $this->render('create', [
            'model' => $model,
            'marcas' => $marcas,
            'tamanhos' => $tamanhos,
            'categorias' => $categorias,
            'ivas' => $ivas,
            'generos' => $generos,
            'imagemForm' => $imagemForm,
        ]);
    }

    /**
     * Updates an existing Produto model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        //seleciona o produto a pretendido
        $model = $this->findModel($id);

        //iniciliaza a variavel do modelo ImagemForm
        $imagemForm = new ImagemForm();

        //buscar todos os dados destas categorias de dados
        $marcas = Marca::find()->select(['nomeMarca', 'id'])->indexBy('id')->column();
        $categorias = Categoria::find()->select(['nomeCategoria', 'id'])->indexBy('id')->column();
        $tamanhos = Tamanho::find()->select(['referencia', 'id'])->indexBy('id')->column();
        $generos = Genero::find()->select(['referencia', 'id'])->indexBy('id')->column();
        $ivas = Iva::find()->where(['vigor' => 1])->select(['percentagem', 'id'])->indexBy('id')->column();

        //se o pedido for POST e carregar os dados do formulário com sucesso, e armazenar
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            //verifica se o produto tem tamanhos associados
            $tamanhosExistem = ProdutosHasTamanho::find()->where(['produto_id' => $id])->exists();

            //se existir tamanhos associados ao produto selecionado
            if ($tamanhosExistem) {

                //selecionar as quantidades dos tamanhos
                $quantidades = Yii::$app->request->post('quantidade_tamanho');

                //se existirem quantidades inseridas nos respetivos tamanhos
                if (is_array($quantidades)) {
                    //atribui 0 à quantidade da tabela de produtos
                    $model->quantidade = 0;

                    //percorre todos os campos preenchidos na vista
                    foreach ($quantidades as $tamanho_id => $quantidade) {
                        if ($produto = ProdutosHasTamanho::find()->where(['produto_id' => $id, 'tamanho_id' => $tamanho_id])->one()) {
                            //editar a qunatidade na base dados
                            $produto->quantidade = $quantidade;

                            //guardar o registo editado na tabela de ProdutosHasTamanho
                            $produto->save();
                        } else {
                            //criar o registo na base dados
                            $tamanho = new ProdutosHasTamanho();
                            $tamanho->produto_id = $model->id;
                            $tamanho->tamanho_id = $tamanho_id;
                            $tamanho->quantidade = $quantidade;

                            //guardar o registo na tabela de ProdutosHasTamanho
                            $tamanho->save();
                        }
                        //atribui a respetiva quantidade do produto na tabela de Produtos (tira o 0 e coloca a quantidade correta)
                        $model->quantidade += (int)$quantidade;
                    }
                    //recalcular e atualizar a quantidade total do produto
                    //obter todas as quantidades do produto selecionado relacionado aos tamanhos
                    $total_quantidade = ProdutosHasTamanho::find()
                        ->where(['produto_id' => $model->id])
                        ->sum('quantidade');  //soma as quantidades de todos os tamanhos

                    //atualiza a quantidade total do produto
                    $model->quantidade = (int)$total_quantidade;

                    //guardar o registo na tabela de Produtos
                    $model->save();
                }
            }

            //chamar o metodo para tatar do upload das imagens
            $this->actionUpload($model->id, $imagemForm);

            //redireciona para a página de index
            return $this->redirect(['index']);
        }

        //faz o render da página de update com os respetivos dados
        return $this->render('update', [
            'model' => $model,
            'marcas' => $marcas,
            'tamanhos' => $tamanhos,
            'categorias' => $categorias,
            'ivas' => $ivas,
            'generos' => $generos,
            'imagemForm' => $imagemForm,
        ]);
    }

    public function actionUpdateimagem($id)
    {
        //iniciliaza a variavel do modelo ImagemForm
        $imagemForm = new ImagemForm();

        //seleciona a imagem pretendida
        $imagem = Imagem::findOne($id);

        //seleciona o produto cuja a imagem está a ser editada
        $model = Produto::find()->where(['id' => $imagem->produto_id])->one();

        //buscar todos os dados destas categorias de dados
        $marcas = Marca::find()->select(['nomeMarca', 'id'])->indexBy('id')->column();
        $categorias = Categoria::find()->select(['nomeCategoria', 'id'])->indexBy('id')->column();
        $tamanhos = Tamanho::find()->select(['referencia', 'id'])->indexBy('id')->column();
        $generos = Genero::find()->select(['referencia', 'id'])->indexBy('id')->column();
        $ivas = Iva::find()->where(['vigor' => 1])->select(['percentagem', 'id'])->indexBy('id')->column();

        //se o pedido for POST
        if ($this->request->isPost) {

            //carrega as imagens
            $imagemForm->imagens = UploadedFile::getInstances($imagemForm, 'imagens');

            //se existir imagens carregadas
            if ($imagemForm->update($id)) {
                //redireciona para a página de index
                return $this->redirect(['update', 'model' => $model,
                    'marcas' => $marcas,
                    'tamanhos' => $tamanhos,
                    'categorias' => $categorias,
                    'ivas' => $ivas,
                    'generos' => $generos,
                    'imagemForm' => $imagemForm]);
            }
        }

        //faz o render da página de update com os respetivos dados
        return $this->render('update', [
            'model' => $model,
            'marcas' => $marcas,
            'tamanhos' => $tamanhos,
            'categorias' => $categorias,
            'ivas' => $ivas,
            'generos' => $generos,
            'imagemForm' => $imagemForm,
        ]);
    }

    /**
     * Deletes an existing Produto model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //criar a instância do modelo
        $imagemForm = new ImagemForm();

        //chamar o metodo do modelo
        $imagemForm->deleteAll($id);

        //apagar todos os favoritos na tabela Favoritos que correspondem ao produto selecionado
        Favorito::deleteAll(['produto_id' => $id]);

        //apagar todos as avaliações na tabela Avalicoes que correspondem ao produto selecionado
        Avaliacao::deleteAll(['produto_id' => $id]);

        //apagar todos os tamanhos na tabela ProdutosHasTamanho que correspondem ao produto selecionado
        ProdutosHasTamanho::deleteAll(['produto_id' => $id]);

        //apagar na base dados o produto selecionado
        $this->findModel($id)->delete();

        //redireciona para a página de index
        return $this->redirect(['index']);
    }

    public function actionUpload($id, $imagemForm)
    {
        //se o pedido for POST
        if (Yii::$app->request->isPost) {
            //carrega as imagens
            $imagemForm->imagens = UploadedFile::getInstances($imagemForm, 'imagens');

            //se existir imagens carregadas
            if ($imagemForm->upload($id)) {
                //se correu tudo bem
                return true;
            }
        }
        return false;
    }

    public function actionDeleteimagem($id)
    {
        //criar a instância do modelo
        $imagemForm = new ImagemForm();

        //seleciona a imagem pretendida
        $imagem = Imagem::findOne($id);
        //armazenar o id do produto referente à imagem a eliminar
        $produto = $imagem->produto_id;

        //chamar o metodo do modelo
        $imagemForm->delete($id);

        //redireciona para a página de index
        return $this->redirect(['update', 'id' => $produto]);
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
        //se econtrar o modelo de dados do Produto selecionado
        if (($model = Produto::findOne(['id' => $id])) !== null) {
            //devolve o modelo de dados
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}