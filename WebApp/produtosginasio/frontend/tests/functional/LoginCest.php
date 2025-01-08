<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class LoginCest
{
    protected $formId = '#login-form';

    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function loginWithEmptyFields(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->click('button[name="login-button"]');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginWithWrongCredentials(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'ruben');
        $I->fillField('LoginForm[password]', 'admin1234567');
        $I->click('button[name="login-button"]');
        $I->see('Incorrect username or password.');
    }

    public function loginSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'ruben');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('button[name="login-button"]');

        $I->see('Logout');
        $I->dontSee('Login');
        $I->amOnPage('/web');
    }
}

