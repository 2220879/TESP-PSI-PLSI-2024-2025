<?php


namespace frontend\tests\acceptance;

use frontend\tests\AcceptanceTester;

class CompraProdutosCest
{
    public function _before(AcceptanceTester $I)
    {
        //login
        $I->amOnPage('/login');
        $I->fillField('LoginForm[username]', 'Tuga Francisco');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('button[name="login-button"]');
    }

    public function _after(AcceptanceTester $I)
    {
        //logout
        $I->see('Logout (Tuga Francisco)');
        $I->click('Logout (Tuga Francisco)');
    }

    // tests
    public function testComprarProdutos(AcceptanceTester $I)
    {
        //adicionar o produto pretendido ao carrinho de compras
        $I->amOnPage('/');
        $I->see('Produtos');
        $I->click('Produtos');
        $I->wait(2);
        $I->see('Polo Adidas');
        $I->scrollTo('a.p_name[href="/produtosginasio/frontend/web/produto/detalhes?id=1"]');
        $I->wait(2);
        $I->click('Polo Adidas');
        $I->wait(2);
        $I->click('button[data-tamanho-id="1"]');
        $I->wait(2);
        $I->click('#adicionar-carrinho .fa.fa-cart-plus');
        $I->wait(2);
        $I->click('#aumentar-quantidade .fa-plus');
        $I->wait(2);
        $I->click('#diminuir-quantidade .fa-minus');
        $I->wait(2);
        $I->click('#finalizar-compra');
        $I->wait(2);
        $I->selectOption('metodo_entrega', '1');
        $I->wait(1);
        $I->scrollTo('select[name="metodo_pagamento"]');
        $I->selectOption('metodo_pagamento', '1');
        $I->wait(5);
        $I->scrollTo('button.btn.btn-primary.mt-2');
        $I->wait(5);
        $I->click('button.btn.btn-primary.mt-2');
        $I->click('Confirmar Compra');
        $I->wait(1);
        $I->click('i.fa.fa-user');
        $I->wait(1);
        $I->click('Minhas Compras');
        $I->wait(10);

    }
}
