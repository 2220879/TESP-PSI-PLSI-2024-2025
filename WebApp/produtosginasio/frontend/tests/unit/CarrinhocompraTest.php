<?php

namespace frontend\tests\unit;

use common\models\Carrinhocompra;
use common\models\Linhacarrinho;
use common\models\Produto;
use common\models\Profile;
use common\models\Tamanho;

class CarrinhocompraTest extends \Codeception\Test\Unit
{
    /*protected function _before()
    {
    }

    protected function _after()
    {
    }*/

    // tests
    public function testSaveProdutoCarrinhoCompras()
    {
        $profile = Profile::find()->where(['id' => 1])->one();

        $produto = Produto::find()->where(['id' => 1])->one();

        $carrinhoCompras = new Carrinhocompra();
        $carrinhoCompras->quantidade = 1;
        $carrinhoCompras->valorTotal = number_format($produto->preco, 2, '.', '') * $produto->quantidade;
        $carrinhoCompras->profile_id = $profile->id;
        $carrinhoCompras->save();

        $this->assertTrue($carrinhoCompras->validate());
    }

    public function testUpdateProdutoCarrinhoCompras()
    {
        $profile = Profile::find()->where(['id' => 1])->one();

        $produto = Produto::find()->where(['id' => 1])->one();

        $carrinhoCompras = Carrinhocompra::find()->where(['profile_id' => $profile])->one();

        $carrinhoCompras->quantidade = 2;
        $carrinhoCompras->save();

        $this->assertTrue($carrinhoCompras->validate());
    }

    public function testRemoveProdutoCarrinhoCompras()
    {
        $profile = Profile::find()->where(['id' => 1])->one();

        $carrinhoCompras = Carrinhocompra::find()->where(['profile_id' => $profile])->one();

        $carrinhoCompras->delete();
    }

    public function testAddLinhacarrinho()
    {
        $profile = Profile::find()->where(['id' => 1])->one();

        $produto = Produto::find()->where(['id' => 1])->one();

        $tamanho = Tamanho::find()->where(['id' => 1])->one();

        $carrinho = Carrinhocompra::find()->where(['profile_id' => $profile->id])->one();

        $linhacarrinho = new Linhacarrinho();
        $linhacarrinho->quantidade = 1;
        number_format($linhacarrinho->precoUnit, 2, '.', '');
        $linhacarrinho->precoUnit = $produto->preco;
        $linhacarrinho->valorIva = $produto->iva->percentagem;
        number_format($linhacarrinho->valorComIva, 2, '.', '');
        $linhacarrinho->valorComIva = $linhacarrinho->precoUnit + ($linhacarrinho->precoUnit * $linhacarrinho->valorIva);
        number_format($linhacarrinho->subtotal, 2, '.', '');
        $linhacarrinho->subtotal = $produto->preco * $linhacarrinho->quantidade;
        $linhacarrinho->carrinhocompras_id = $carrinho->id;
        $linhacarrinho->produto_id = $produto->id;
        $linhacarrinho->tamanho_id = $tamanho->id;
        $linhacarrinho->save();
    }

    public function testUpdateLinhacarrinho()
    {
        $produto = Produto::find()->where(['id' => 1])->one();

        $tamanho = Tamanho::find()->where(['id' => 1])->one();

        $carrinho = Carrinhocompra::find()->where(['profile_id' => 1])->one();

        $linhacarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id])->one();

        $linhacarrinho->quantidade = 2;
        $linhacarrinho->precoUnit = $produto->preco;
        $linhacarrinho->valorIva = $produto->iva->percentagem;
        $linhacarrinho->valorComIva = $produto->preco + ($produto->preco * $produto->iva->percentagem);
        $linhacarrinho->subtotal = $produto->preco * $linhacarrinho->quantidade;
        $linhacarrinho->carrinhocompras_id = $carrinho->id;
        $linhacarrinho->produto_id = $produto->id;
        $linhacarrinho->tamanho_id = $tamanho->id;

        $linhacarrinho->save();
    }

    public function testRemoveLinhacarrinho()
    {
        $carrinho = Carrinhocompra::find()->where(['profile_id' => 1])->one();

        $linhacarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id])->one();

        $linhacarrinho->delete();
    }
}
