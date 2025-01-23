<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class AdicionarCarrinhoCest
{

    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('LoginForm[username]', 'Tuga Francisco');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('button[name="login-button"]');
    }

    public function adicionarCarrinho(FunctionalTester $I)
    {
        // Navega para a página de produtos
        $I->amOnRoute('/');
        $I->see('Produtos');
        $I->click('Produtos');

        $I->see('Calção Adida');
        $I->click('Calção Adida');

        // Verificar se o botão de tamanho é exibido e clicar nele
        $I->seeElement('button[data-tamanho-id="1"]');
        $I->click('button[data-tamanho-id="1"]');

        // Verificar se o botão de adicionar ao carrinho é exibido e clicar nele
        $I->seeElement('#adicionar-carrinho i.fa.fa-cart-plus');
        $I->click('#adicionar-carrinho i.fa.fa-cart-plus');

        // Verificar se o produto foi realmente adicionado ao carrinho
        $I->see('Calção Adida');
    }
}