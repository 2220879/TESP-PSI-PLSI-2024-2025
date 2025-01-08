<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class FavoritoCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('LoginForm[username]', 'ruben');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('button[name="login-button"]');
    }

    // tests
    public function testAdicionarFavorito(FunctionalTester $I)
    {
        // Acessar a página inicial
        $I->amOnRoute('/');

        $I->see('Produtos');
        $I->click('Produtos');

        $I->see('Calças Trefoil Essentials');
        $I->click('Calças Trefoil Essentials');

        $I->click('#adicionar-favorito');

        $I->see('Produto adicionado aos favoritos.');
    }

    public function testRemoverFavorito(FunctionalTester $I)
    {
        $I->amOnRoute('/');
        $I->click('#ver-favoritos');

        $I->click('#apagar-favorito-1');

        $I->see('Produto removido dos favoritos.');
    }
}