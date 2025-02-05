<?php


namespace backend\tests\functional;

use backend\tests\FunctionalTester;

class ProdutoCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('Entrar');

        $I->dontSeeLink('Entrar');
    }

    public function testCategoriaCampoVazio(FunctionalTester $I)
    {
        $I->amOnRoute('/categoria/create');
        $I->click('Guardar');
        $I->see('Nome Categoria cannot be blank.');
    }

    public function testCriarcategoria(FunctionalTester $I)
    {
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Categorias');
        $I->click('Categorias');

        $I->amOnRoute('categoria/index');

        $I->seeInCurrentUrl('/categoria/index');


        $I->see('Criar Categoria');
        $I->click('Criar Categoria');

        $I->amOnRoute('/categoria/create');

        $I->fillField('Categoria[nomeCategoria]', 'Camisola');

        $I->click('Guardar');

        $I->dontSeeLink('/categoria/create');
    }

    public function testAtualizarcategoria(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Categorias');
        $I->click('Categorias');

        $I->amOnRoute('categoria/index');

        // Verifica se a URL está correta e se a página das categorias foi carregada
        $I->seeInCurrentUrl('/categoria/index');

        $I->click(['xpath' => '//tr[td[normalize-space()="Camisola"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/categoria/view');

        $I->click('Atualizar');

        $I->seeInCurrentUrl('/categoria/update');

        // Preenche os campos do formulário de atualização da Categoria
        $I->fillField('Categoria[nomeCategoria]', 'Camisola3');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        $I->dontSeeLink('/categoria/update');
    }

    public function testMarcaCampoVazio(FunctionalTester $I)
    {
        $I->amOnRoute('/marca/create');
        $I->click('Guardar');
        $I->see('Nome Marca cannot be blank.');
    }

    public function testCriarmarca(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Marcas');
        $I->click('Marcas');

        $I->amOnRoute('marca/index');

        // Verifica se a URL está correta e se a página de produtos foi carregada
        $I->seeInCurrentUrl('/marca/index');

        $I->see('Criar Marca');
        $I->click('Criar Marca');

        // Vai para a página de criação de produto
        $I->amOnRoute('/marca/create');

        // Preenche os campos do formulário de criação de produto
        $I->fillField('Marca[nomeMarca]', 'Nike');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        // Verifica que o link de "Create Produto" não está mais disponível (indicando que o produto foi salvo)
        $I->dontSeeLink('/marca/create');
    }

    public function testAtualizarmarca(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Marcas');
        $I->click('Marcas');

        $I->amOnRoute('marca/index');

        // Verifica se a URL está correta e se a página das categorias foi carregada
        $I->seeInCurrentUrl('/marca/index');

        $I->click(['xpath' => '//tr[td[normalize-space()="Nike"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/marca/view');

        $I->click('Atualizar');

        $I->seeInCurrentUrl('/marca/update');

        // Preenche os campos do formulário de atualização da Categoria
        $I->fillField('Marca[nomeMarca]', 'Nike2');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        $I->dontSeeLink('/marca/update');
    }

    public function testGeneroCampoVazio(FunctionalTester $I)
    {
        $I->amOnRoute('/genero/create');
        $I->click('Guardar');
        $I->see('Referencia cannot be blank.');
    }

    public function testCriargenero(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Géneros');
        $I->click('Géneros');

        $I->amOnRoute('genero/index');

        // Verifica se a URL está correta e se a página de produtos foi carregada
        $I->seeInCurrentUrl('/genero/index');


        $I->see('Criar Género');
        $I->click('Criar Género');

        // Vai para a página de criação de produto
        $I->amOnRoute('/genero/create');

        $I->fillField('Genero[referencia]', 'Masculinos');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        $I->dontSeeLink('/genero/create');
    }

    public function testAtualizargenero(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Géneros');
        $I->click('Géneros');

        $I->amOnRoute('genero/index');

        // Verifica se a URL está correta e se a página das categorias foi carregada
        $I->seeInCurrentUrl('/genero/index');

        $I->click(['xpath' => '//tr[td[normalize-space()="Masculinos"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/genero/view');

        $I->click('Atualizar');

        $I->seeInCurrentUrl('/genero/update');

        // Preenche os campos do formulário de atualização da Categoria
        $I->fillField('Genero[referencia]', 'Masculino2');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        $I->dontSeeLink('/genero/update');
    }

    public function testIvaCamposVazios(FunctionalTester $I)
    {
        $I->amOnRoute('/iva/create');
        $I->click('Guardar');
        $I->see('Percentagem cannot be blank.');
        $I->see('Vigor cannot be blank.');
    }

    public function testCriariva(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Ivas');
        $I->click('Ivas');

        $I->amOnRoute('iva/index');

        // Verifica se a URL está correta e se a página de produtos foi carregada
        $I->seeInCurrentUrl('/iva/index');

        $I->see('Criar Iva');
        $I->click('Criar Iva');

        // Vai para a página de criação de produto
        $I->amOnRoute('/iva/create');

        // Preenche os campos do formulário de criação de produto
        $I->fillField('Iva[percentagem]', '0.29');
        $I->selectOption('Iva[vigor]', '0');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        // Verifica que o link de "Create Produto" não está mais disponível (indicando que o produto foi salvo)
        $I->dontSeeLink('/iva/create');
    }

    public function testAtualizariva(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Ivas');
        $I->click('Ivas');

        $I->amOnRoute('iva/index');

        // Verifica se a URL está correta e se a página das categorias foi carregada
        $I->seeInCurrentUrl('/iva/index');

        $I->click(['xpath' => '//tr[td[normalize-space()="0.29"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/iva/view');

        $I->click('Atualizar');

        $I->seeInCurrentUrl('/iva/update');

        // Preenche os campos do formulário de atualização da Categoria
        $I->fillField('Iva[percentagem]', '0.27');
        $I->selectOption('Iva[vigor]', '1');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        $I->dontSeeLink('/iva/update');
    }

    public function testTamanhoCampoVazio(FunctionalTester $I)
    {
        $I->amOnRoute('/tamanho/create');
        $I->click('Guardar');
        $I->see('Referencia cannot be blank.');
    }

    public function testCriartamanho(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Tamanhos');
        $I->click('Tamanhos');

        $I->amOnRoute('tamanho/index');

        // Verifica se a URL está correta e se a página de produtos foi carregada
        $I->seeInCurrentUrl('/tamanho/index');

        $I->see('Criar Tamanho');
        $I->click('Criar Tamanho');

        // Vai para a página de criação de produto
        $I->amOnRoute('/tamanho/create');

        // Preenche os campos do formulário de criação de produto
        $I->fillField('Tamanho[referencia]', 'XS');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        // Verifica que o link de "Create Produto" não está mais disponível (indicando que o produto foi salvo)
        $I->dontSeeLink('/tamanho/create');
    }

    public function testAtualizartamanho(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Gestão');
        $I->click('Gestão');

        $I->see('Tamanhos');
        $I->click('Tamanhos');

        $I->amOnRoute('tamanho/index');

        // Verifica se a URL está correta e se a página das categorias foi carregada
        $I->seeInCurrentUrl('/tamanho/index');

        $I->click(['xpath' => '//tr[td[normalize-space()="XS"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/tamanho/view');

        $I->click('Atualizar');

        $I->seeInCurrentUrl('/tamanho/update');

        // Preenche os campos do formulário de atualização da Categoria
        $I->fillField('Tamanho[referencia]', 'L');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        $I->dontSeeLink('/tamanho/update');
    }

    public function testProdutoCamposVazios(FunctionalTester $I)
    {
        $I->amOnRoute('/produto/create');
        $I->click('Guardar');
        $I->see('Nome Produto cannot be blank.');
        $I->see('Preco cannot be blank.');
        $I->see('Descricao Produto cannot be blank.');
        $I->see('Marca ID cannot be blank.');
        $I->see('Categoria ID cannot be blank.');
        $I->see('Iva ID cannot be blank.');
        $I->see('Genero ID cannot be blank.');
    }

    public function testCriarproduto(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        // Verifica que a seção "Mercadoria" está visível
        $I->see('Mercadoria');
        $I->click('Mercadoria');  // Clica em "Mercadoria"

        // Verifica se "Produtos" está visível e clica nela
        $I->see('Produtos');
        $I->click('Produtos');  // Clica em "Produtos"

        $I->amOnRoute('produto/index');

        // Verifica se a URL está correta e se a página de produtos foi carregada
        $I->seeInCurrentUrl('/produto/index'); // Verifica que estamos na página de produtos


        // Agora, verifica se o botão "Create Produto" está presente e visível
        $I->see('Criar Produto'); // Verifica o botão na página
        $I->click('Criar Produto'); // Clica no botão para criar um novo produto

        // Vai para a página de criação de produto
        $I->amOnRoute('/produto/create');

        // Preenche os campos do formulário de criação de produto
        $I->fillField('Produto[nomeProduto]', 'Calção Adidas');
        $I->fillField('Produto[preco]', 22.30);
        $I->fillField('Produto[descricaoProduto]', 'Calção elegante!!!');

        // Seleciona opções nos campos de dropdown
        $I->selectOption('Produto[marca_id]', '10');
        $I->selectOption('Produto[categoria_id]', '10');
        $I->selectOption('Produto[iva_id]', '9');
        $I->selectOption('Produto[genero_id]', '10');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        // Verifica que o link de "Create Produto" não está mais disponível (indicando que o produto foi salvo)
        $I->dontSeeLink('/produto/create');
    }

    public function testAtualizarproduto(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        $I->see('Mercadoria');
        $I->click('Mercadoria');

        $I->see('Produtos');
        $I->click('Produtos');

        $I->amOnRoute('produto/index');

        $I->seeInCurrentUrl('/produto/index');

        $I->click(['xpath' => '//tr[td[normalize-space()="Calção Adidas"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/produto/view');

        $I->click('Atualizar');

        $I->seeInCurrentUrl('/produto/update');

        // Preenche os campos do formulário de criação de produto
        $I->fillField('Produto[nomeProduto]', 'Calção Adidas v1');
        $I->fillField('Produto[preco]', 22.40);
        $I->fillField('Produto[descricaoProduto]', 'Calção elegante!!');

        // Seleciona opções nos campos de dropdown
        $I->selectOption('Produto[marca_id]', '10');
        $I->selectOption('Produto[categoria_id]', '10');
        $I->selectOption('Produto[iva_id]', '9');
        $I->selectOption('Produto[genero_id]', '10');

        // Clica no botão de salvar para enviar o formulário
        $I->click('Guardar');

        // Verifica que o link de "Create Produto" não está mais disponível (indicando que o produto foi salvo)
        $I->dontSeeLink('/produto/update');
    }

    public function testApagarproduto(FunctionalTester $I)
    {
        // Vai para a página inicial
        $I->amOnRoute('/web/');

        // Verifica que a seção "Mercadoria" está visível
        $I->see('Mercadoria');
        $I->click('Mercadoria');  // Clica em "Mercadoria"

        // Verifica se "Produtos" está visível e clica nela
        $I->see('Produtos');
        $I->click('Produtos');  // Clica em "Produtos"

        $I->amOnRoute('produto/index');

        // Verifica se a URL está correta e se a página de produtos foi carregada
        $I->seeInCurrentUrl('/produto/index'); // Verifica que estamos na página de produtos

        $I->click(['xpath' => '//tr[td[normalize-space()="Calção Adidas v1"]]/td/a[@title="View"]']);

        $I->seeInCurrentUrl('/produto/view');

        $I->click('Apagar');

        //$I->executeJS("window.confirm = function() { return true; };");

        // Verifica que o link de "Create Produto" não está mais disponível (indicando que o produto foi salvo)
        $I->dontSeeLink('/produto/view');
    }
}