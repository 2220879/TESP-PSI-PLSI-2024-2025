<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class FavoritoCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField('LoginForm[username]', 'Tuga Francisco');
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

        $I->see('Calção Adida');
        $I->click('Calção Adida');

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