<?php

namespace backend\tests\unit;

use common\models\Genero;
use common\models\Iva;
use common\models\Marca;
use common\models\Produto;
use common\models\Tamanho;
use Yii;
use common\models\Categoria;

class ProdutoTest extends \Codeception\Test\Unit
{

    //TESTE PARA CATEGORIAS
    public function testCategoriaValida()
    {
        $categoria = new Categoria();
        $categoria->nomeCategoria = "Camisola";

        $this->assertTrue($categoria->save());
    }

    public function testCategoriaNomeVazio()
    {
        $categoria = new Categoria();
        $categoria->nomeCategoria = '';
        $this->assertFalse($categoria->validate(['nomeCategoria']));
    }

    public function testCategoriaLimiteCaracteres()
    {

        $categoria = new Categoria();
        $categoria->nomeCategoria = str_repeat('A', 46);
        $this->assertFalse($categoria->validate(['nomeCategoria']));
    }

    public function testCategoriaDuplicado()
    {
        $categoria1 = new Categoria();
        $categoria1->nomeCategoria = 'Camisola';
        $categoria1->save();

        $categoria2 = new Categoria();
        $categoria2->nomeCategoria = 'Camisola';

        $this->assertFalse($categoria2->validate(['nomeCategoria']));
    }

    public function testCategoriaAtualizar()
    {
        $categoria = Categoria::findOne(['nomeCategoria' => 'Camisola']);

        //verificar se a categoria foi encontrada
        $this->assertNotNull($categoria, "Categoria não encontrada");

        // Atualizar o nome da categoria para "Acessórios"
        $novoNome = 'Acessórios';

        // Verificar se o novo nome já existe no banco de dados
        $categoriaExistente = Categoria::findOne(['nomeCategoria' => $novoNome]);

        // Se a categoria com o novo nome já existir, falhar o teste
        $this->assertNull($categoriaExistente, "Já existe uma categoria com esse nome");

        // Atualizar o nome da categoria
        $categoria->nomeCategoria = $novoNome;

        // Validar e salvar a categoria atualizada
        $this->assertTrue($categoria->validate(), "Falha na validação");
        $this->assertTrue($categoria->save(), "Falha ao guardar a categoria");

        // Buscar a categoria após a atualização
        $categoriaAtualizada = Categoria::findOne($categoria->id);

        // Verificar se o nome foi atualizado corretamente
        $this->assertEquals($novoNome, $categoriaAtualizada->nomeCategoria, "Nome da categoria não foi atualizado corretamente");
    }


    public function testCategoriaApagar()
    {
        $categoriaExistente = Categoria::findOne(['nomeCategoria' => 'Acessórios']);
        $this->assertNotNull($categoriaExistente);

        $this->assertTrue($categoriaExistente->delete() !== false, 'A categoria não foi apagada corretamente.');

        $categoriaExcluida = Categoria::findOne(['nomeCategoria' => $categoriaExistente]);
        $this->assertNull($categoriaExcluida, 'A categoria ainda existe na base de dados após a exclusão.');
    }

    //TESTES DOS GENEROS

    public function testGeneroValido()
    {
        $genero = new Genero();
        $genero->referencia = "Feminino";
        $this->assertTrue($genero->save());
    }

    public function testGeneroNomeVazio()
    {
        $genero = new Genero();
        $genero->referencia = '';
        $this->assertFalse($genero->validate(['referencia']));
    }

    public function testGeneroDuplicado()
    {
        $genero = new Genero();
        $genero->referencia = "Feminino";
        $this->assertFalse($genero->save());
    }

    public function testGeneroLimiteCaracteres()
    {
        $genero = new Genero();
        $genero->referencia = str_repeat('A', 46);
        $this->assertFalse($genero->validate(['referencia']));
    }

    public function testGeneroEditar()
    {
        $genero = Genero::findOne(['referencia' => 'Masculino']);
        $genero->referencia = "Masculino2";
        $this->assertTrue($genero->save());
    }

    public function testGeneroApagar()
    {
        $generoExistente = Genero::findOne(['referencia' => 'Feminino']);
        $this->assertNotNull($generoExistente);

        $this->assertTrue($generoExistente->delete() !== false, "O género não foi excluído corretamente.");

        $generoExcluido = Genero::findOne(['referencia' => 'Masculino Atualizado']);
        $this->assertNull($generoExcluido);
    }

    //TESTES DO IVA
    public function testIvaValido()
    {
        $iva = new Iva();
        $iva->percentagem = 0.13;
        $iva->vigor = 1;

        $this->assertTrue($iva->save());
    }

    public function testIvaPercentagemVazia()
    {
        $iva = new Iva();
        $iva->percentagem = null;
        $iva->vigor = 1;

        $this->assertFalse($iva->validate(['percentagem']));
    }

    public function testIvaVigorVazio()
    {
        $iva = new Iva();
        $iva->percentagem = 0.13;
        $iva->vigor = null;

        $this->assertFalse($iva->validate(['vigor']));
    }

    public function testIvaDuplicado()
    {
        $iva = new Iva();
        $iva->percentagem = 0.13;
        $iva->vigor = 1;
        $this->assertFalse($iva->save());

    }

    public function testIvaAtualizar()
    {
        $idIva = 1;
        $ivaExistente = Iva::findOne(['id' => $idIva]);
        $ivaExistente->percentagem = 0.25;
        $this->assertTrue($ivaExistente->save());
    }

    public function testIvaApagar()
    {
        $ivaExistente = Iva::findOne(['percentagem' => 0.19]);

        $this->assertNotNull($ivaExistente, 'O IVA especificado não foi encontrado no base de dados.');

        $this->assertTrue($ivaExistente->delete() !== false, 'O IVA não foi apagado corretamente.');

        $ivaExcluido = Iva::findOne(['percentagem' => 0.19]);
        $this->assertNull($ivaExcluido, 'O IVA ainda existe na base de dados após a exclusão.');
    }

    //TESTE MARCA
    public function testMarcaValida()
    {
        $marca = new Marca();
        $marca->nomeMarca = 'Nike';

        $this->assertTrue($marca->save());

        //Verificar se guardou na base de dados
        $marcaGuardada = Marca::findOne(['nomeMarca' => 'Nike']);
        $this->assertNotNull($marcaGuardada, 'A marca não foi encontrada no base de dados.');
    }

    public function testMarcaDuplicada()
    {
        $marca = new Marca();
        $marca->nomeMarca = 'Nike';
        $this->assertFalse($marca->save());
    }

    public function testMarcaAtualizacao()
    {
        $marca = Marca::findOne(['nomeMarca' => 'Nike']);
        $marca->nomeMarca = 'Adidas';
        $this->assertTrue($marca->save());

        // Verificar se a marca foi atualizada
        $marcaAtualizada = Marca::findOne(['nomeMarca' => 'Adidas']);
        $this->assertNotNull($marcaAtualizada, 'A marca não foi atualizada corretamente.');
    }

    public function testMarcaApagar()
    {
        $marcaExistente = Marca::findOne(['nomeMarca' => 'Adidas']);

        $this->assertNotNull($marcaExistente, 'A marca especificada não foi encontrada no base de dados.');

        $this->assertTrue($marcaExistente->delete() !== false, 'A marca não foi apagada corretamente.');

        //Verificar e a marca foi excluida
        $marcaExcluida = Marca::findOne(['nomeMarca' => 'Adidas']);
        $this->assertNull($marcaExcluida, 'A marca ainda existe na base de dados após ser apagada.');
    }

    //TESTE TAMANHO

    public function testTamanhoValido()
    {
        $tamanho = new Tamanho();
        $tamanho->referencia = 'S';
        $this->assertTrue($tamanho->save());
    }

    public function testTamanhoAtualizar()
    {
        $idTamanho = 6;
        $tamanho = Tamanho::findOne(['id' => $idTamanho]);
        $tamanho->referencia = 'M';
        $this->assertTrue($tamanho->save());
    }


    public function testTamanhoDuplicado()
    {
        $tamanho = new Tamanho();
        $tamanho->referencia = 'XS';
        $this->assertFalse($tamanho->save());
    }

    public function testTamanhoApagar()
    {
        $tamanhoExistente = Tamanho::findOne(['referencia' => 'XS']);

        $this->assertNotNull($tamanhoExistente, 'O tamanho especifico não foi encontrada na base de dados.');

        $this->assertTrue($tamanhoExistente->delete() !== false, 'O tamanho não foi apagado corretamente.');

        $tamanhoExcluido = Tamanho::findOne(['referencia' => 'XS']);
        $this->assertNull($tamanhoExcluido, 'O tamanho ainda existe na base de dados após ser apagado.');
    }

    //TESTE PRODUTO
    public function testProdutoValido()
    {
        $produto = new Produto();
        $produto->nomeProduto = 'Produto Teste';
        $produto->descricaoProduto = 'Descrição do produto teste';
        $produto->preco = 50.00;
        $produto->quantidade = 10;
        $produto->marca_id = 1;
        $produto->categoria_id = 1;
        $produto->iva_id = 1;
        $produto->genero_id = 1;

        $this->assertTrue($produto->save());
    }

    public function testProdutoSemNome()
    {
        $produto = new Produto();
        $produto->nomeProduto = '';
        $produto->descricaoProduto = 'Produto sem nome';
        $produto->preco = 50.00;
        $produto->quantidade = 10;
        $produto->marca_id = 1;
        $produto->categoria_id = 1;
        $produto->iva_id = 1;
        $produto->genero_id = 1;

        $resultado = $produto->save();
        $this->assertFalse($resultado, "O produto foi guardado sem nome, o que é inválido.");
    }

    public function testProdutoSemDescricao()
    {
        $produto = new Produto();
        $produto->nomeProduto = 'Produto sem Descrição';
        $produto->descricaoProduto = '';
        $produto->preco = 50.00;
        $produto->quantidade = 10;
        $produto->marca_id = 1;
        $produto->categoria_id = 1;
        $produto->iva_id = 1;
        $produto->genero_id = 1;

        $resultado = $produto->save();
        $this->assertFalse($resultado, "O produto foi guardado sem descrição, o que é inválido.");
    }

    public function testProdutoSemPreco()
    {

        $produto = new Produto();
        $produto->nomeProduto = 'Produto sem Preço';
        $produto->descricaoProduto = 'Descrição do produto sem preço';
        $produto->preco = null;
        $produto->quantidade = 10;
        $produto->marca_id = 1;
        $produto->categoria_id = 1;
        $produto->iva_id = 1;
        $produto->genero_id = 1;

        $resultado = $produto->save();
        $this->assertFalse($resultado, "O produto foi guardado sem preço, o que é inválido.");
    }

    public function testProdutoSemQuantidade()
    {

        $produto = new Produto();
        $produto->nomeProduto = 'Produto sem Quantidade';
        $produto->descricaoProduto = 'Descrição do produto sem quantidade';
        $produto->preco = 50.00;
        $produto->quantidade = '';
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;

        $resultado = $produto->save();
        $this->assertFalse($resultado, "O produto foi salvo sem quantidade, o que é inválido.");
    }

    public function testProdutoComPrecoNegativo()
    {
        $produto = new Produto();
        $produto->nomeProduto = 'Produto Preço Negativo';
        $produto->descricaoProduto = 'Produto com preço negativo';
        $produto->preco = -10.00;
        $produto->quantidade = 5;
        $produto->marca_id = 1;
        $produto->categoria_id = 1;
        $produto->iva_id = 1;
        $produto->genero_id = 1;

        $resultado = $produto->save();
        $this->assertFalse($resultado, "O produto com preço negativo foi guardado, o que é inválido.");
    }

    public function testProdutoAtualizarPreco()
    {
        $produto = Produto::findOne(['nomeProduto' => 'Produto Teste']);
        $produto->preco = 60.00;
        $produto->save();

        // Verifica se o preço foi atualizado
        $this->assertEquals(60.00, $produto->preco);
    }

    public function testProdutoAtualizarQuantidade()
    {
        $produto = Produto::findOne(['nomeProduto' => 'Produto Teste']);
        $produto->quantidade = 20;
        $produto->save();

        // Verifica se a quantidade foi atualizada
        $this->assertEquals(20, $produto->quantidade);
    }

    public function testProdutoApagar()
    {
        $produtoExistente = Produto::findOne(['nomeProduto' => 'Produto Teste']);
        $this->assertNotNull($produtoExistente, 'O produto especificado não foi encontrado na base de dados.');

        $this->assertTrue($produtoExistente->delete() !== false, 'O produto não foi apagado corretamente.');

        // Verificar se o produto foi apagado
        $produtoExcluido = Produto::findOne(['nomeProduto' => 'Produto Teste']);
        $this->assertNull($produtoExcluido, 'O produto ainda existe na base de dados após ser apagado.');
    }
}
