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
    /*public function testSaveProdutoCarrinhoCompras()
    {
        $profile = Profile::find()->where(['id' => 1])->one();

        $produto = Produto::find()->where(['id' => 1])->one();

        $carrinhoCompras = Carrinhocompra::find()->where(['profile_id' => $profile->id])->one();

        $carrinhoCompras->quantidade = 1;

        $linhacarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinhoCompras->id])->one();

        $linhacarrinho->precoUnit = $produto->preco;

        $carrinhoCompras->valorTotal = number_format($linhacarrinho->precoUnit, 2, '.', '') * $linhacarrinho->quantidade;

        $carrinhoCompras->profile_id = $profile->id;

        $carrinhoCompras->save();

        $this->assertTrue($carrinhoCompras->validate());
    }*/

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
        /*$profile = Profile::find()->where(['id' => 1])->one();

        $produto = Produto::find()->where(['id' => 1])->one();

        $carrinhoCompras = Carrinhocompra::find()->where(['profile_id' => $profile])->one();

        $linhacarrinho = Linhacarrinho::find()->where(['carrinho_id' => $carrinhoCompras->id])->one();

        $carrinhoCompras->quantidade = 2;

        $carrinhoCompras->valorTotal = number_format($linhacarrinho->precoUnit, 2, '.', '');

        $carrinhoCompras->profile_id = $profile->id;

        $carrinhoCompras->save();*/

        $carrinhoCompras = Carrinhocompra::find()->one();
        $carrinhoCompras->quantidade = 2;
        $carrinhoCompras->valorTotal = 0.00;
        $carrinhoCompras->profile_id = Profile::find()->one()->id;

        $carrinhoCompras->update();
    }

    public function testRemoveCarrinhoCompras()
    {
        $carrinhoCompras = Carrinhocompra::find()->one();

        $carrinhoCompras->delete();
    }

    public function testAddLinhacarrinho()
    {
        /*$carrinho = Carrinhocompra::find()->one();

        if (!$carrinho) {
            $this->fail('Nenhum carrinho de compras encontrado.');
            return;
        }

        $produto = Produto::find()->one();

        if (!$produto) {
            $this->fail('Nenhum produto encontrado.');
            return;
        }

        $tamanho = Tamanho::find()->one();

        if (!$tamanho) {
            $this->fail('Nenhum tamanho encontrado.');
            return;
        }*/

        $linhacarrinho = new Linhacarrinho();

        $linhacarrinho->quantidade = 1;
        $linhacarrinho->precoUnit = 14.99;
        $linhacarrinho->valorIva = 0.06;
        $linhacarrinho->valorComIva = number_format($linhacarrinho->precoUnit + ($linhacarrinho->precoUnit * $linhacarrinho->valorIva), 2, '.', '');
        $linhacarrinho->subtotal = number_format($linhacarrinho->valorComIva * $linhacarrinho->quantidade, 2, '.', '');
        $linhacarrinho->carrinhocompras_id = Carrinhocompra::find()->one()->id;
        $linhacarrinho->produto_id = Produto::find()->one()->id;
        $linhacarrinho->tamanho_id = Tamanho::find()->one()->id;

        $this->assertTrue($linhacarrinho->save());
        $linhacarrinhoGuardar = Linhacarrinho::findOne($linhacarrinho->id);
        $this->assertNotNull($linhacarrinhoGuardar, 'Linha carrinho não foi encontrada na base de dados.');
    }


    public function testUpdateLinhacarrinho()
    {
        $linhacarrinho = Linhacarrinho::find()->one();

        if ($linhacarrinho) {
            $linhacarrinho->quantidade = 2;
            $linhacarrinho->precoUnit = 14.99;
            $linhacarrinho->valorIva = 0.06;
            $linhacarrinho->valorComIva = 15.89;
            $linhacarrinho->subtotal = $linhacarrinho->valorComIva * $linhacarrinho->quantidade;

            $carrinho = Carrinhocompra::find()->one();

            if ($carrinho) {
                $linhacarrinho->carrinhocompras_id = $carrinho->id;
            } else {
                $this->fail('Nenhum carrinho de compras encontrado.');
                return;
            }

            $produto = Produto::find()->one();
            if ($produto) {
                $linhacarrinho->produto_id = $produto->id;
            } else {
                $this->fail('Nenhum produto encontrado.');
                return;
            }

            $tamanho = Tamanho::find()->one();
            if ($tamanho) {
                $linhacarrinho->tamanho_id = $tamanho->id;
            } else {
                $this->fail('Nenhum tamanho encontrado.');
                return;
            }

            $linhacarrinho->update();
        } else {
            $this->fail('Nenhuma linha de carrinho encontrada.');
        }
    }

    public function testRemoveLinhacarrinho()
    {
        $carrinho = Carrinhocompra::find()->where(['profile_id' => 1])->one();
        if ($carrinho) {
            $linhacarrinho = Linhacarrinho::find()->where(['carrinhocompras_id' => $carrinho->id])->one();
            if ($linhacarrinho) {
                $linhacarrinho->delete();
            } else {
                $this->fail('Nenhuma linha de carrinho encontrada para deletar.');
            }
        } else {
            $this->fail('Nenhum carrinho de compras encontrado.');
        }
    }

}
