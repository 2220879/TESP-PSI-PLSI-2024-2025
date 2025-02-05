<?php

namespace frontend\tests\unit;

use common\models\Carrinhocompra;
use common\models\Linhacarrinho;
use common\models\Produto;
use common\models\Profile;
use common\models\Tamanho;

class CarrinhocompraTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testSaveCarrinhoCompras()
    {
        $profile = Profile::find()->one();
        if (!$profile) {
            $this->fail('Nenhum perfil encontrado.');
            return;
        }

        $carrinhoCompras = new Carrinhocompra();
        $carrinhoCompras->quantidade = 1;
        $carrinhoCompras->valorTotal = 0.00;
        $carrinhoCompras->profile_id = $profile->id;

        if ($carrinhoCompras->save()) {
            $this->assertTrue(true);
        } else {
            $this->fail('Falha ao salvar o carrinho de compras.');
        }
    }


    public function testUpdateCarrinhoCompras()
    {
        $carrinhoCompras = Carrinhocompra::find()->where(['id' => 4])->one();
        $carrinhoCompras->quantidade = 2;
        $carrinhoCompras->valorTotal = 0.00;
        $carrinhoCompras->profile_id = Profile::find()->one()->id;

        $this->assertEquals(1, $carrinhoCompras->update(), "Falha ao atualizar o carrinho de compras");
    }

    public function testRemoveCarrinhoCompras()
    {
        $carrinhoCompras = Carrinhocompra::find()->where(['id' => 5])->one();

        $this->assertEquals(1, $carrinhoCompras->delete(), "Falha ao eliminar o carrinho de compras");
    }

    public function testAddLinhacarrinho()
    {
        $linhacarrinho = new Linhacarrinho();

        $linhacarrinho->quantidade = 1;
        $linhacarrinho->precoUnit = 14.99;
        $linhacarrinho->valorIva = 0.06;
        $linhacarrinho->valorComIva = number_format($linhacarrinho->precoUnit + ($linhacarrinho->precoUnit * $linhacarrinho->valorIva), 2, '.', '');
        $linhacarrinho->subtotal = number_format($linhacarrinho->valorComIva * $linhacarrinho->quantidade, 2, '.', '');
        $linhacarrinho->carrinhocompras_id = 2;
        $linhacarrinho->produto_id = 8;
        $linhacarrinho->tamanho_id = 1;

        $this->assertTrue($linhacarrinho->save());
        $linhacarrinhoGuardar = Linhacarrinho::findOne($linhacarrinho->id);
        $this->assertNotNull($linhacarrinhoGuardar, 'Linha carrinho não foi encontrada na base de dados.');
    }


    public function testUpdateLinhacarrinho()
    {
        $linhacarrinho = Linhacarrinho::find()->where(['id' => 1])->one();

        $linhacarrinho->quantidade = 2;
        $linhacarrinho->precoUnit = 14.99;
        $linhacarrinho->valorIva = 0.06;
        $linhacarrinho->valorComIva = 15.89;
        $linhacarrinho->subtotal = $linhacarrinho->valorComIva * $linhacarrinho->quantidade;

        $carrinho = Carrinhocompra::find()->where(['id' => 2])->one();

        if ($carrinho) {
            $linhacarrinho->carrinhocompras_id = $carrinho->id;
        } else {
            $this->fail('Nenhum carrinho de compras encontrado.');
            return;
        }

        $produto = Produto::find()->where(['id' => 8])->one();
        if ($produto) {
            $linhacarrinho->produto_id = $produto->id;
        } else {
            $this->fail('Nenhum produto encontrado.');
            return;
        }

        $tamanho = Tamanho::find()->where(['id' => 1])->one();
        if ($tamanho) {
            $linhacarrinho->tamanho_id = $tamanho->id;
        } else {
            $this->fail('Nenhum tamanho encontrado.');
            return;
        }

        $this->assertEquals(1, $linhacarrinho->update(), "Falha ao atualizar o carrinho de compras");
    }

    public function testRemoveLinhacarrinho()
    {
        $linhacarrinho = Linhacarrinho::find()->where(['id' => 1])->one();
        $this->assertEquals(1, $linhacarrinho->delete(), "Falha ao eliminar a linha no carrinho de compras");
    }

}
