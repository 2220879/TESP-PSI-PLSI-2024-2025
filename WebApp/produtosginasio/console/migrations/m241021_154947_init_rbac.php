<?php

use yii\db\Migration;

/**
 * Class m241021_154947_init_rbac
 */
class m241021_154947_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241021_154947_init_rbac cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $auth = Yii::$app->authManager;

        // view "Users" permission
        $viewUser = $auth->createPermission('viewUsers');
        $viewUser->description = 'View users';
        $auth->add($viewUser);

        // add "createUser" permission
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Create a user';
        $auth->add($createUser);

        // edit "updateUser" permission
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update user';
        $auth->add($updateUser);

        // delete "deleteUser" permission
        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'delete user';
        $auth->add($deleteUser);


        // view "Profiles" permission
        $viewProfile = $auth->createPermission('viewProfiles');
        $viewProfile->description = 'View Profiles';
        $auth->add($viewProfile);

        // add "createProfile" permission
        $createProfile = $auth->createPermission('createProfile');
        $createProfile->description = 'Create a profile';
        $auth->add($createProfile);

        // edit "updateProfile" permission
        $updateProfile = $auth->createPermission('updateProfile');
        $updateProfile->description = 'Update profile';
        $auth->add($updateProfile);

        // delete "deleteProfile" permission
        $deleteProfile = $auth->createPermission('deleteProfile');
        $deleteProfile->description = 'delete profile';
        $auth->add($deleteProfile);

        // view "Uso Cupoes" permission
        $viewUsoCupoes = $auth->createPermission('viewUsoCupoes');
        $viewUsoCupoes->description = 'View uso cupoes';
        $auth->add($viewUsoCupoes);

        // view "View Cupoes Desconto" permission
        $viewCupoes = $auth->createPermission('viewCupoes');
        $viewCupoes->description = 'View Cupoes Descontos';
        $auth->add($viewCupoes);

        // add "createCupao" permission
        $createCupao = $auth->createPermission('createCupao');
        $createCupao->description = 'Create a cupao';
        $auth->add($createCupao);

        // edit "updateCupao" permission
        $updateCupao = $auth->createPermission('updateCupao');
        $updateCupao->description = 'Update Cupao';
        $auth->add($updateCupao);

        // delete "deleteCupao" permission
        $deleteCupao = $auth->createPermission('deleteCupao');
        $deleteCupao->description = 'delete cupao';
        $auth->add($deleteCupao);

        // view "View Compras" permission
        $viewCompras = $auth->createPermission('viewCompras');
        $viewCompras->description = 'View Compras';
        $auth->add($viewCompras);

        // add "createCompra" permission
        $createCompra = $auth->createPermission('createCompra');
        $createCompra->description = 'Create a compra';
        $auth->add($createCompra);

        // edit "updateCompra" permission
        $updateCompra = $auth->createPermission('updateCompra');
        $updateCompra->description = 'Update Compra';
        $auth->add($updateCompra);

        // delete "deleteCompra" permission
        $deleteCompra = $auth->createPermission('deleteCompra');
        $deleteCompra->description = 'delete compra';
        $auth->add($deleteCompra);

        // view "View Linhas Compras" permission
        $viewLinhasCompras = $auth->createPermission('viewLinhasCompras');
        $viewLinhasCompras->description = 'View Linhas Compras';
        $auth->add($viewLinhasCompras);

        // add "createLinhaCompra" permission
        $createLinhaCompra = $auth->createPermission('createLinhaCompra');
        $createLinhaCompra->description = 'Create a linha compra';
        $auth->add($createLinhaCompra);

        // edit "updateLinhaCompra" permission
        $updateLinhaCompra = $auth->createPermission('updateLinhaCompra');
        $updateLinhaCompra->description = 'Update Linha Compra';
        $auth->add($updateLinhaCompra);

        // delete "deleteLinhaCompra" permission
        $deleteLinhaCompra = $auth->createPermission('deleteLinhaCompra');
        $deleteLinhaCompra->description = 'delete linha compra';
        $auth->add($deleteLinhaCompra);

        // view "View Fornecedores" permission
        $viewFornecedores = $auth->createPermission('viewFornecedores');
        $viewFornecedores->description = 'View Fornecedores';
        $auth->add($viewFornecedores);

        // add "createFornecedor" permission
        $createFornecedor = $auth->createPermission('createFornecedor');
        $createFornecedor->description = 'Create a fornecedor';
        $auth->add($createFornecedor);

        // edit "updateFornecedor" permission
        $updateFornecedor = $auth->createPermission('updateFornecedor');
        $updateFornecedor->description = 'Update Fornecedor';
        $auth->add($updateFornecedor);

        // delete "deleteFornecedor" permission
        $deleteFornecedor = $auth->createPermission('deleteFornecedor');
        $deleteFornecedor->description = 'delete fornecedor';
        $auth->add($deleteFornecedor);

        // view "View Produtos" permission
        $viewProdutos = $auth->createPermission('viewProdutos');
        $viewProdutos->description = 'View Produtos';
        $auth->add($viewProdutos);

        // add "createProduto" permission
        $createProduto = $auth->createPermission('createProduto');
        $createProduto->description = 'Create a produto';
        $auth->add($createProduto);

        // edit "updateProduto" permission
        $updateProduto = $auth->createPermission('updateProduto');
        $updateProduto->description = 'Update Produto';
        $auth->add($updateProduto);

        // delete "deleteProduto" permission
        $deleteProduto = $auth->createPermission('deleteProduto');
        $deleteProduto->description = 'delete produto';
        $auth->add($deleteProduto);

        // view "View Tamanhos" permission
        $viewTamanhos = $auth->createPermission('viewTamanhos');
        $viewTamanhos->description = 'View Tamanhos';
        $auth->add($viewTamanhos);

        // add "createTamanho" permission
        $createTamanho = $auth->createPermission('createTamanho');
        $createTamanho->description = 'Create a tamanho';
        $auth->add($createTamanho);

        // edit "updateTamanho" permission
        $updateTamanho = $auth->createPermission('updateTamanho');
        $updateTamanho->description = 'Update Tamanho';
        $auth->add($updateTamanho);

        // delete "deleteTamanho" permission
        $deleteTamanho = $auth->createPermission('deleteTamanho');
        $deleteTamanho->description = 'delete tamanho';
        $auth->add($deleteTamanho);

        // view "View Marcas" permission
        $viewMarcas = $auth->createPermission('viewMarcas');
        $viewMarcas->description = 'View Marcas';
        $auth->add($viewMarcas);

        // add "createMarca" permission
        $createMarca = $auth->createPermission('createMarca');
        $createMarca->description = 'Create a marca';
        $auth->add($createMarca);

        // edit "updateMarca" permission
        $updateMarca = $auth->createPermission('updateMarca');
        $updateMarca->description = 'Update Marca';
        $auth->add($updateMarca);

        // delete "deleteMarca" permission
        $deleteMarca = $auth->createPermission('deleteMarca');
        $deleteMarca->description = 'delete Marca';
        $auth->add($deleteMarca);

        // view "View Categorias" permission
        $viewCategorias = $auth->createPermission('viewCategorias');
        $viewCategorias->description = 'View Categorias';
        $auth->add($viewCategorias);

        // add "createCategoria" permission
        $createCategoria = $auth->createPermission('createCategoria');
        $createCategoria->description = 'Create a categoria';
        $auth->add($createCategoria);

        // edit "updateCategoria" permission
        $updateCategoria = $auth->createPermission('updateCategoria');
        $updateCategoria->description = 'Update Categoria';
        $auth->add($updateCategoria);

        // delete "deleteCategoria" permission
        $deleteCategoria = $auth->createPermission('deleteCategoria');
        $deleteCategoria->description = 'delete Categoria';
        $auth->add($deleteCategoria);

        // view "View Ivas" permission
        $viewIvas = $auth->createPermission('viewIvas');
        $viewIvas->description = 'View Ivas';
        $auth->add($viewIvas);

        // add "createIva" permission
        $createIva = $auth->createPermission('createIva');
        $createIva->description = 'Create a Iva';
        $auth->add($createIva);

        // edit "updateIva" permission
        $updateIva = $auth->createPermission('updateIva');
        $updateIva->description = 'Update Iva';
        $auth->add($updateIva);

        // delete "deleteIva" permission
        $deleteIva = $auth->createPermission('deleteIva');
        $deleteIva->description = 'delete Iva';
        $auth->add($deleteIva);

        // view "View Generos" permission
        $viewGeneros = $auth->createPermission('viewGeneros');
        $viewGeneros->description = 'View Generos';
        $auth->add($viewGeneros);

        // add "createGenero" permission
        $createGenero = $auth->createPermission('createGenero');
        $createGenero->description = 'Create a Genero';
        $auth->add($createGenero);

        // edit "updateGenero" permission
        $updateGenero = $auth->createPermission('updateGenero');
        $updateGenero->description = 'Update Genero';
        $auth->add($updateGenero);

        // delete "deleteGenero" permission
        $deleteGenero = $auth->createPermission('deleteGenero');
        $deleteGenero->description = 'delete Genero';
        $auth->add($deleteGenero);

        // view "View Imagens" permission
        $viewImagens = $auth->createPermission('viewImagens');
        $viewImagens->description = 'View Imagens';
        $auth->add($viewImagens);

        // add "createImagem" permission
        $createImagem = $auth->createPermission('createImagem');
        $createImagem->description = 'Create a Imagem';
        $auth->add($createImagem);

        // edit "updateImagem" permission
        $updateImagem = $auth->createPermission('updateImagem');
        $updateImagem->description = 'Update Imagem';
        $auth->add($updateImagem);

        // delete "deleteImagem" permission
        $deleteImagem = $auth->createPermission('deleteImagem');
        $deleteImagem->description = 'delete Imagem';
        $auth->add($deleteImagem);

        // view "ViewCarrinhoCompras” permission
        $viewCarrinhoCompras = $auth->createPermission('viewCarrinhoCompras');
        $viewCarrinhoCompras->description = 'View CarrinhoCompras';
        $auth->add($viewCarrinhoCompras);

        // view "ViewLinhaCarrinhoCompras” permission
        $viewLinhaCarrinhoCompras = $auth->createPermission('viewLinhaCarrinhoCompras');
        $viewLinhaCarrinhoCompras->description = 'View LinhaCarrinhoCompras';
        $auth->add($viewLinhaCarrinhoCompras);

        // add “CreateLinhaCarrinhoCompra” permission
        $createLinhaCarrinhoCompra = $auth->createPermission('createLinhaCarrinhoCompra');
        $createLinhaCarrinhoCompra->description = 'Create LinhaCarrinhoCompra';
        $auth->add($createLinhaCarrinhoCompra);

        // edit “UpdateLinhaCarrinhoCompra” permission
        $updateLinhaCarrinhoCompra = $auth->createPermission('updateLInhaCarrinhoCompra');
        $updateLinhaCarrinhoCompra->description = 'Update LinhaCarrinhoCompra';
        $auth->add($updateLinhaCarrinhoCompra);

        // delete “DeleteLinhaCarrinhoCompra” permission
        $deleteLinhaCarrinhoCompra = $auth->createPermission('deleteLinhaCarrinhoCompra');
        $deleteLinhaCarrinhoCompra->description = 'Delete LinhaCarrinhoCompra';
        $auth->add($deleteLinhaCarrinhoCompra);

        // view "View Faturas" permission
        $viewFaturas = $auth->createPermission('viewFaturas');
        $viewFaturas->description = 'View Faturas';
        $auth->add($viewFaturas);

        // view "View Linhas Faturas" permission
        $viewLinhasFaturas = $auth->createPermission('viewLinhasFaturas');
        $viewLinhasFaturas->description = 'View Linhas Faturas';
        $auth->add($viewLinhasFaturas);

        // view "View Metodos Entregas" permission
        $viewMetodosEntregas = $auth->createPermission('viewMetodosEntregas');
        $viewMetodosEntregas->description = 'View Metodos Entregas';
        $auth->add($viewMetodosEntregas);

        // add "createMetodoEntrega" permission
        $createMetodoEntrega = $auth->createPermission('createMetodoEntrega');
        $createMetodoEntrega->description = 'Create a Metodo Entrega';
        $auth->add($createMetodoEntrega);

        // edit "updateMetodoEntrega" permission
        $updateMetodoEntrega = $auth->createPermission('updateMetodoEntrega');
        $updateMetodoEntrega->description = 'Update Metodo Entrega';
        $auth->add($updateMetodoEntrega);

        // delete "deleteMetodoEntrega" permission
        $deleteMetodoEntrega = $auth->createPermission('deleteMetodoEntrega');
        $deleteMetodoEntrega->description = 'delete Metodo Entrega';
        $auth->add($deleteMetodoEntrega);

        // view "View Metodos Pagamentos" permission
        $viewMetodosPagamentos = $auth->createPermission('viewMetodosPagamentos');
        $viewMetodosPagamentos->description = 'View Metodos Pagamentos';
        $auth->add($viewMetodosPagamentos);

        // add "createMetodoPagamento" permission
        $createMetodoPagamento = $auth->createPermission('createMetodoPagamento');
        $createMetodoPagamento->description = 'Create a Metodo Pagamento';
        $auth->add($createMetodoPagamento);

        // edit "updateMetodoMetodoPagamento" permission
        $updateMetodoPagamento = $auth->createPermission('updateMetodoPagamento');
        $updateMetodoPagamento->description = 'Update Metodo Pagamento';
        $auth->add($updateMetodoPagamento);

        // delete "deleteMetodoPagamento" permission
        $deleteMetodoPagamento = $auth->createPermission('deleteMetodoPagamento');
        $deleteMetodoPagamento->description = 'delete Metodo Pagamento';
        $auth->add($deleteMetodoPagamento);

        // view "View Encomendas" permission
        $viewEncomendas = $auth->createPermission('viewEncomendas');
        $viewEncomendas->description = 'View Encomendas';
        $auth->add($viewEncomendas);

        // edit "updateEncomenda" permission
        $updateEncomenda = $auth->createPermission('updateEncomenda');
        $updateEncomenda->description = 'Update Encomenda';
        $auth->add($updateEncomenda);

        // view "ViewFavoritos” permission
        $viewFavoritos = $auth->createPermission('viewFavoritos');
        $viewFavoritos->description = 'View Favoritos';
        $auth->add($viewFavoritos);

        // add “CreateFavorito” permission
        $createFavorito = $auth->createPermission('createFavorito');
        $createFavorito->description = 'Create Favorito';
        $auth->add($createFavorito);

        // edit “UpdateFavorito” permission
        $updateFavorito = $auth->createPermission('updateFavorito');
        $updateFavorito->description = 'Update Favorito';
        $auth->add($updateFavorito);

        // delete “DeleteFavorito” permission
        $deleteFavorito = $auth->createPermission('deleteFavorito');
        $deleteFavorito->description = 'Delete Favorito';
        $auth->add($deleteFavorito);

        // view "ViewAvaliacoes" permission
        $viewAvaliacoes = $auth->createPermission('viewAvaliacoes');
        $viewAvaliacoes->description = 'View Avaliacoes';
        $auth->add($viewAvaliacoes);

        // add “CreateAvaliacao” permission
        $createAvaliacao = $auth->createPermission('createAvaliacao');
        $createAvaliacao->description = 'Create Avaliacao';
        $auth->add($createAvaliacao);

        // edit “UpdateAvaliacao” permission
        $updateAvaliacao = $auth->createPermission('updateAvaliacao');
        $updateAvaliacao->description = 'Update Avaliacao';
        $auth->add($updateAvaliacao);

        // delete “DeleteAvaliacao” permission
        $deleteAvaliacao = $auth->createPermission('deleteAvaliacao');
        $deleteAvaliacao->description = 'Delete Avaliacao';
        $auth->add($deleteAvaliacao);

        // add "admin" role and permissions
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $viewUser);
        $auth->addChild($admin, $createUser);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $viewProfile);
        $auth->addChild($admin, $createProfile);
        $auth->addChild($admin, $updateProfile);
        $auth->addChild($admin, $deleteProfile);
        $auth->addChild($admin, $viewUsoCupoes);
        $auth->addChild($admin, $viewCupoes);
        $auth->addChild($admin, $createCupao);
        $auth->addChild($admin, $updateCupao);
        $auth->addChild($admin, $deleteCupao);
        $auth->addChild($admin, $viewCompras);
        $auth->addChild($admin, $createCompra);
        $auth->addChild($admin, $updateCompra);
        $auth->addChild($admin, $deleteCompra);
        $auth->addChild($admin, $viewLinhasCompras);
        $auth->addChild($admin, $createLinhaCompra);
        $auth->addChild($admin, $updateLinhaCompra);
        $auth->addChild($admin, $deleteLinhaCompra);
        $auth->addChild($admin, $viewFornecedores);
        $auth->addChild($admin, $createFornecedor);
        $auth->addChild($admin, $updateFornecedor);
        $auth->addChild($admin, $deleteFornecedor);
        $auth->addChild($admin, $viewProdutos);
        $auth->addChild($admin, $createProduto);
        $auth->addChild($admin, $updateProduto);
        $auth->addChild($admin, $deleteProduto);
        $auth->addChild($admin, $viewTamanhos);
        $auth->addChild($admin, $createTamanho);
        $auth->addChild($admin, $updateTamanho);
        $auth->addChild($admin, $deleteTamanho);
        $auth->addChild($admin, $viewMarcas);
        $auth->addChild($admin, $createMarca);
        $auth->addChild($admin, $updateMarca);
        $auth->addChild($admin, $deleteMarca);
        $auth->addChild($admin, $viewCategorias);
        $auth->addChild($admin, $createCategoria);
        $auth->addChild($admin, $updateCategoria);
        $auth->addChild($admin, $deleteCategoria);
        $auth->addChild($admin, $viewIvas);
        $auth->addChild($admin, $createIva);
        $auth->addChild($admin, $updateIva);
        $auth->addChild($admin, $deleteIva);
        $auth->addChild($admin, $viewGeneros);
        $auth->addChild($admin, $createGenero);
        $auth->addChild($admin, $updateGenero);
        $auth->addChild($admin, $deleteGenero);
        $auth->addChild($admin, $viewImagens);
        $auth->addChild($admin, $createImagem);
        $auth->addChild($admin, $updateImagem);
        $auth->addChild($admin, $deleteImagem);
        $auth->addChild($admin, $viewAvaliacoes);
        $auth->addChild($admin, $viewFaturas);
        $auth->addChild($admin, $viewLinhasFaturas);
        $auth->addChild($admin, $viewMetodosEntregas);
        $auth->addChild($admin, $createMetodoEntrega);
        $auth->addChild($admin, $updateMetodoEntrega);
        $auth->addChild($admin, $deleteMetodoEntrega);
        $auth->addChild($admin, $viewMetodosPagamentos);
        $auth->addChild($admin, $createMetodoPagamento);
        $auth->addChild($admin, $updateMetodoPagamento);
        $auth->addChild($admin, $deleteMetodoPagamento);
        $auth->addChild($admin, $viewEncomendas);
        $auth->addChild($admin, $updateEncomenda);

        // add "funcionario" role and permissions
        $funcionario = $auth->createRole('funcionario');
        $auth->add($funcionario);
        $auth->addChild($funcionario, $viewUsoCupoes);
        $auth->addChild($funcionario, $viewCupoes);
        $auth->addChild($funcionario, $createCupao);
        $auth->addChild($funcionario, $updateCupao);
        $auth->addChild($funcionario, $deleteCupao);
        $auth->addChild($funcionario, $viewCompras);
        $auth->addChild($funcionario, $createCompra);
        $auth->addChild($funcionario, $updateCompra);
        $auth->addChild($funcionario, $deleteCompra);
        $auth->addChild($funcionario, $viewLinhasCompras);
        $auth->addChild($funcionario, $createLinhaCompra);
        $auth->addChild($funcionario, $updateLinhaCompra);
        $auth->addChild($funcionario, $deleteLinhaCompra);
        $auth->addChild($funcionario, $viewFornecedores);
        $auth->addChild($funcionario, $createFornecedor);
        $auth->addChild($funcionario, $updateFornecedor);
        $auth->addChild($funcionario, $deleteFornecedor);
        $auth->addChild($funcionario, $viewProdutos);
        $auth->addChild($funcionario, $createProduto);
        $auth->addChild($funcionario, $updateProduto);
        $auth->addChild($funcionario, $deleteProduto);
        $auth->addChild($funcionario, $viewTamanhos);
        $auth->addChild($funcionario, $createTamanho);
        $auth->addChild($funcionario, $updateTamanho);
        $auth->addChild($funcionario, $deleteTamanho);
        $auth->addChild($funcionario, $viewMarcas);
        $auth->addChild($funcionario, $createMarca);
        $auth->addChild($funcionario, $updateMarca);
        $auth->addChild($funcionario, $deleteMarca);
        $auth->addChild($funcionario, $viewCategorias);
        $auth->addChild($funcionario, $createCategoria);
        $auth->addChild($funcionario, $updateCategoria);
        $auth->addChild($funcionario, $deleteCategoria);
        $auth->addChild($funcionario, $viewIvas);
        $auth->addChild($funcionario, $createIva);
        $auth->addChild($funcionario, $updateIva);
        $auth->addChild($funcionario, $deleteIva);
        $auth->addChild($funcionario, $viewGeneros);
        $auth->addChild($funcionario, $createGenero);
        $auth->addChild($funcionario, $updateGenero);
        $auth->addChild($funcionario, $deleteGenero);
        $auth->addChild($funcionario, $viewImagens);
        $auth->addChild($funcionario, $createImagem);
        $auth->addChild($funcionario, $updateImagem);
        $auth->addChild($funcionario, $deleteImagem);
        $auth->addChild($funcionario, $viewAvaliacoes);
        $auth->addChild($funcionario, $viewFaturas);
        $auth->addChild($funcionario, $viewLinhasFaturas);
        $auth->addChild($funcionario, $viewMetodosEntregas);
        $auth->addChild($funcionario, $createMetodoEntrega);
        $auth->addChild($funcionario, $updateMetodoEntrega);
        $auth->addChild($funcionario, $deleteMetodoEntrega);
        $auth->addChild($funcionario, $viewMetodosPagamentos);
        $auth->addChild($funcionario, $createMetodoPagamento);
        $auth->addChild($funcionario, $updateMetodoPagamento);
        $auth->addChild($funcionario, $deleteMetodoPagamento);
        $auth->addChild($funcionario, $viewEncomendas);
        $auth->addChild($funcionario, $updateEncomenda);

        //add "cliente" role and permissions
        $cliente = $auth->createRole('cliente');
        $auth->add($cliente);
        $auth->addChild($cliente, $viewUser);
        $auth->addChild($cliente, $updateUser);
        $auth->addChild($cliente, $viewProfile);
        $auth->addChild($cliente, $updateProfile);
        $auth->addChild($cliente, $viewFavoritos);
        $auth->addChild($cliente, $createFavorito);
        $auth->addChild($cliente, $updateFavorito);
        $auth->addChild($cliente, $deleteFavorito);
        $auth->addChild($cliente, $viewAvaliacoes);
        $auth->addChild($cliente, $createAvaliacao);
        $auth->addChild($cliente, $updateAvaliacao);
        $auth->addChild($cliente, $deleteAvaliacao);
        $auth->addChild($cliente, $viewEncomendas);
        $auth->addChild($cliente, $viewFaturas);
        $auth->addChild($cliente, $viewLinhasFaturas);
        $auth->addChild($cliente, $viewCupoes);
        $auth->addChild($cliente, $viewCarrinhoCompras);
        $auth->addChild($cliente, $viewLinhaCarrinhoCompras);
        $auth->addChild($cliente, $createLinhaCarrinhoCompra);
        $auth->addChild($cliente, $updateLinhaCarrinhoCompra);
        $auth->addChild($cliente, $deleteLinhaCarrinhoCompra);
        $auth->addChild($cliente, $viewMetodosPagamentos);
        $auth->addChild($cliente, $viewMetodosEntregas);
        $auth->addChild($cliente, $viewImagens);
        $auth->addChild($cliente, $viewProdutos);
        $auth->addChild($cliente, $viewTamanhos);
        $auth->addChild($cliente, $viewMarcas);
        $auth->addChild($cliente, $viewCategorias);
        $auth->addChild($cliente, $viewIvas);
        $auth->addChild($cliente, $viewGeneros);
        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
