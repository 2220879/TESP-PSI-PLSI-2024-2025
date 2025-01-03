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

    public function testCategoriaLimiteCaracteres(){

        $categoria = new Categoria();
        $categoria->nomeCategoria = str_repeat('A', 46);
        $this->assertFalse($categoria->validate(['nomeCategoria']));
    }

    public function testCategoriaDuplicado()
    {
        $categoria1 = new Categoria();
        $categoria1->nomeCategoria = 'Calças';
        $categoria1->save();

        $categoria2 = new Categoria();
        $categoria2->nomeCategoria = 'Calças';

        $this->assertFalse($categoria2->validate(['nomeCategoria']));
    }

    public function testCategoriaAtualizar()
    {
        $categoria = new Categoria();
        $categoria->nomeCategoria = 'Acessórios';

        $this->assertTrue($categoria->validate());
        $this->assertTrue($categoria->save());

        $categoriaId = $categoria->id;
        $categoriaRecemCriada = Categoria::findOne($categoriaId);

        // Verificar se a categoria foi salva corretamente
        $this->assertNotNull($categoriaRecemCriada);
        $this->assertEquals('Acessórios', $categoriaRecemCriada->nomeCategoria);

        $categoriaRecemCriada->nomeCategoria = 'Hoddies';

        $this->assertTrue($categoriaRecemCriada->save());

        $categoriaAtualizada = Categoria::findOne($categoriaId);
        $this->assertEquals('Hoddies', $categoriaAtualizada->nomeCategoria);
    }


    public function testCategoriaApagar()
    {
        $categoriaExistente = Categoria::findOne(['nomeCategoria' => 'Camisola']);
        $this->assertNotNull($categoriaExistente);

        $this->assertTrue($categoriaExistente->delete() !== false, 'A categoria não foi apagada corretamente.');

        $categoriaExcluida = Categoria::findOne(['nomeCategoria' => 'Camisola']);
        $this->assertNull($categoriaExcluida, 'A categoria ainda existe na base de dados após a exclusão.');
    }

    //TESTES DOS GENEROS

    public function testGeneroValido()
    {
        $genero = new Genero();
        $genero->referencia = "Masculino";
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
        $genero1 = new Genero();
        $genero1->referencia = "Feminino";
        $this->assertTrue($genero1->save());

        $genero2 = new Genero();
        $genero2->referencia = "Feminino";
        $this->assertFalse($genero2->save());
    }

    public function testGeneroLimiteCaracteres()
    {
        $genero = new Genero();
        $genero->referencia = str_repeat('A', 46);
        $this->assertFalse($genero->validate(['referencia']));
    }

    public function testGeneroEditar()
    {
        $genero = new Genero();
        $genero->referencia = "Masculinos";
        $this->assertTrue($genero->save());

        $genero->referencia = "Masculino Atualizado";
        $this->assertTrue($genero->save());
    }

    public function testGeneroApagar()
    {
        $generoExistente = Genero::findOne(['referencia' => 'Masculino Atualizado']);
        $this->assertNotNull($generoExistente);

        $this->assertTrue($generoExistente->delete() !== false, "O género não foi excluído corretamente.");

        $generoExcluido = Genero::findOne(['referencia' => 'Masculino Atualizado']);
        $this->assertNull($generoExcluido);
    }

    //TESTES DO IVA
    public function testIvaValido()
    {
        $iva = new Iva();
        $iva->percentagem = 13.0;
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
        $iva1 = new Iva();
        $iva1->percentagem = 10.0;
        $iva1->vigor = 1;
        $this->assertTrue($iva1->save());


        $iva2 = new Iva();
        $iva2->percentagem = 10.0;
        $iva2->vigor = 1;

        $this->assertFalse($iva2->save(), 'O IVA com a mesma percentagem foi guardado, o que não é permitido.');

    }

    public function testIvaAtualizar()
    {

        $ivaExistente = Iva::findOne(['percentagem' => 13.0]);

        $this->assertNotNull($ivaExistente, 'O IVA especificado não foi encontrado na base de dados.');

        $ivaExistente->percentagem = 25.0;

        $this->assertTrue($ivaExistente->save());

        $ivaAtualizado = Iva::findOne(['percentagem' => 25.0]);
        $this->assertNotNull($ivaAtualizado, 'O IVA atualizado não foi encontrado na base de dados.');
    }

    public function testIvaApagar()
    {
        $ivaExistente = Iva::findOne(['percentagem' => 13.0]);

        $this->assertNotNull($ivaExistente, 'O IVA especificado não foi encontrado no base de dados.');

        $this->assertTrue($ivaExistente->delete() !== false, 'O IVA não foi apagado corretamente.');

        $ivaExcluido = Iva::findOne(['percentagem' => 13.0]);
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
        $marca->nomeMarca = 'Adidas';

        $this->assertTrue($marca->save());

        $marcaDuplicada = new Marca();
        $marcaDuplicada->nomeMarca = 'Adidas';

        $this->assertFalse($marcaDuplicada->save());
    }

    public function testMarcaAtualizacao()
    {
        $marca = new Marca();
        $marca->nomeMarca = 'Zumub';
        $this->assertTrue($marca->save(), 'A marca não foi criada corretamente.');

        // Atualizar o nome da marca
        $marca->nomeMarca = 'Prozis';
        $this->assertTrue($marca->save());

        // Verificar se a marca foi atualizada
        $marcaAtualizada = Marca::findOne(['nomeMarca' => 'Prozis']);
        $this->assertNotNull($marcaAtualizada, 'A marca não foi atualizada corretamente.');
    }

    public function testMarcaApagar()
    {
        $marcaExistente = Marca::findOne(['nomeMarca' => 'Nike']);

        $this->assertNotNull($marcaExistente, 'A marca especificada não foi encontrada no base de dados.');

        $this->assertTrue($marcaExistente->delete() !== false, 'A marca não foi apagada corretamente.');

        //Verificar e a marca foi excluida
        $marcaExcluida = Marca::findOne(['nomeMarca' => 'Nike']);
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
        $tamanho = new Tamanho();
        $tamanho->referencia = 'XS';
        $this->assertTrue($tamanho->save());


        $tamanho->referencia = 'M';
        $this->assertTrue($tamanho->save());


        $tamanhoAtualizado = Tamanho::findOne(['referencia' => 'M']);
        $this->assertNotNull($tamanhoAtualizado, 'O tamanho não foi atualizado corretamente.');
    }


    public function testTamanhoDuplicado()
    {
        $tamanho = new Tamanho();
        $tamanho->referencia = 'XS';

        $this->assertTrue($tamanho->save());

        $tamanhoDuplicado = new Tamanho();
        $tamanhoDuplicado->referencia = 'XS';

        $this->assertFalse($tamanhoDuplicado->save());
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
        $produto->marca_id = Marca::find()->one()->id; // Vai Assumie que pelo menos uma marca existe
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;

        $this->assertTrue($produto->save());
    }

    public function testProdutoSemNome()
    {
        $produto = new Produto();
        $produto->nomeProduto='';
        $produto->descricaoProduto = 'Produto sem nome';
        $produto->preco = 50.00;
        $produto->quantidade = 10;
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;

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
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;

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
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;

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
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;

        $resultado = $produto->save();
        $this->assertFalse($resultado, "O produto com preço negativo foi guardado, o que é inválido.");
    }

    public function testProdutoAtualizarPreco()
    {
        $produto = new Produto();
        $produto->nomeProduto = 'Produto Atualizar Preço';
        $produto->descricaoProduto = 'Produto com atualização de preço';
        $produto->preco = 50.00;
        $produto->quantidade = 10;
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;
        $produto->save();


        $produto->preco = 60.00;
        $produto->save();

        // Verifica se o preço foi atualizado
        $this->assertEquals(60.00, $produto->preco);
    }

    public function testProdutoAtualizarQuantidade()
    {
        $produto = new Produto();
        $produto->nomeProduto = 'Produto Atualizar Quantidade';
        $produto->descricaoProduto = 'Produto com atualização de quantidade';
        $produto->preco = 50.00;
        $produto->quantidade = 10;
        $produto->marca_id = Marca::find()->one()->id;
        $produto->categoria_id = Categoria::find()->one()->id;
        $produto->iva_id = Iva::find()->one()->id;
        $produto->genero_id = Genero::find()->one()->id;
        $produto->save();

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
