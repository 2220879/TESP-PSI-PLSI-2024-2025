<?php


namespace backend\tests\unit;

use backend\tests\UnitTester;
use common\models\Produto;
use common\models\ProdutosHasTamanho;
use common\models\Tamanho;

class ProdutosHasTamanhoTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testAdicionarProdutoTamanho()
    {
        $nomeProduto = 'Camisola Polo Adidas';
        $tamanho = 'S';

        $produto = Produto::findOne(['nomeProduto' => $nomeProduto]);
        $tamanho = Tamanho::findOne(['referencia' => $tamanho]);

        $this->assertNotNull($produto, 'Produto não encontrado');
        $this->assertNotNull($tamanho, 'Tamanho não encontrado');

        $produtoTamanhoExistente = ProdutosHasTamanho::findOne([
            'produto_id' => $produto->id,
            'tamanho_id' => $tamanho->id
        ]);

        //se já existe a associação, mostra uma mensagem e não cria
        if ($produtoTamanhoExistente) {
            $this->fail('O produto já está associado ao tamanho ' . $tamanho->referencia);
        }

        $produtohastamanho = new ProdutosHasTamanho();

        $produtohastamanho->produto_id = $produto->id;
        $produtohastamanho->tamanho_id = $tamanho->id;
        $produtohastamanho->quantidade = 10;
        $this->assertTrue($produtohastamanho->save());

        //recalcular a quantidade total do produto
        $quantidadeTotal = ProdutosHasTamanho::find()
            ->where(['produto_id' => $produto->id])
            ->sum('quantidade');

        //atualizar a quantidade total no produto
        $produto->quantidade += $quantidadeTotal;
        $this->assertTrue($produto->save());
    }

    public function testEditarQuantidadeProdutoTamanho()
    {
        $nomeProduto = 'Camisola Polo Adidas';
        $tamanhoReferencia = 'M';

        //buscar o produto e o tamanho
        $produto = Produto::findOne(['nomeProduto' => $nomeProduto]);
        $tamanho = Tamanho::findOne(['referencia' => $tamanhoReferencia]);

        //verificar se o produto e o tamanho foram encontrados
        $this->assertNotNull($produto, 'Produto não encontrado');
        $this->assertNotNull($tamanho, 'Tamanho não encontrado');

        //buscar a associação produto-tamanho
        $produtohastamanho = ProdutosHasTamanho::findOne(['produto_id' => $produto->id, 'tamanho_id' => $tamanho->id]);
        $this->assertNotNull($produtohastamanho, 'Produto com o respetivo tamanho não encontrado');

        //alterar a quantidade do tamanho
        $produtohastamanho->quantidade = 10;
        $this->assertTrue($produtohastamanho->save());

        //recalcular a quantidade total do produto
        $quantidadeTotal = ProdutosHasTamanho::find()
            ->where(['produto_id' => $produto->id])
            ->sum('quantidade');

        //atualizar a quantidade total no produto
        $produto->quantidade = $quantidadeTotal;
        $this->assertTrue($produto->save());

        $this->assertEquals($quantidadeTotal, $produto->quantidade, 'Quantidade total do produto não foi atualizada corretamente');
    }

    public function testEditarTamanhoProdutoTamanho()
    {
        $nomeProduto = 'Camisola Polo Adidas';
        $tamanhoReferencia = 'M';

        //buscar o produto e o tamanho
        $produto = Produto::findOne(['nomeProduto' => $nomeProduto]);
        $tamanho = Tamanho::findOne(['referencia' => $tamanhoReferencia]);

        //verificar se o produto e o tamanho foram encontrados
        $this->assertNotNull($produto, 'Produto não encontrado');
        $this->assertNotNull($tamanho, 'Tamanho não encontrado');

        //buscar a associação produto-tamanho
        $produtohastamanho = ProdutosHasTamanho::findOne(['produto_id' => $produto->id, 'tamanho_id' => $tamanho->id]);
        $this->assertNotNull($produtohastamanho, 'Produto com o respetivo tamanho não encontrado');

        $novoTamanho = 'S';
        $tamanho = Tamanho::findOne(['referencia' => $novoTamanho]);

        //alterar a quantidade do tamanho
        $produtohastamanho->tamanho_id = $tamanho->id;
        $this->assertTrue($produtohastamanho->save());
    }

    public function testApagarProdutoTamanho()
    {
        $nomeProduto = 'Camisola Polo Adidas';
        $tamanhoReferencia = 'S';

        //buscar o produto e o tamanho
        $produto = Produto::findOne(['nomeProduto' => $nomeProduto]);
        $tamanho = Tamanho::findOne(['referencia' => $tamanhoReferencia]);

        //verificar se o produto e o tamanho foram encontrados
        $this->assertNotNull($produto, 'Produto não encontrado');
        $this->assertNotNull($tamanho, 'Tamanho não encontrado');

        //buscar a associação produto-tamanho
        $produtohastamanho = ProdutosHasTamanho::findOne(['produto_id' => $produto->id, 'tamanho_id' => $tamanho->id]);
        $this->assertGreaterThan(0, $produtohastamanho->delete());
    }
}