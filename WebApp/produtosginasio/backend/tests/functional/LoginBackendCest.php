<?php


namespace backend\tests\functional;

use backend\tests\FunctionalTester;

class LoginBackendCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function loginCamposVazios(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->click('Sign In');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }


    // Teste de verficar a condição for inserido uma password inválida
    public function loginCredenciaisErradas(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', '123456789');
        $I->click('Sign In');
        $I->see('Incorrect username or password.');
    }

    public function loginUser(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('Sign In');

        $I->dontSeeLink('Sign In');
    }
}