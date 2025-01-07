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
        /*$I->see('Login', 'h1');
        $I->see('Please fill out the following fields to login:');
        $I->submitForm($this->formId, []);
        $I->seeValidationError('Username cannot be blank.');
        $I->seeValidationError('Password cannot be blank.');*/
        $I->amOnRoute('/site/login');
        $I->click('button[name="login-button"]');
        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }
    public function loginWithWrongCredentials(FunctionalTester $I)
    {
        /*$I->submitForm($this->formId, [
            'LoginForm[username]' => 'incorrect_username',
            'LoginForm[password]' => 'incorrect_password',
        ]);
        $I->see('Incorrect username or password.');*/
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'ruben');
        $I->fillField('LoginForm[password]', 'admin1234567');
        $I->click('button[name="login-button"]');
        $I->see('Incorrect username or password.');
    }

    public function loginSuccessfully(FunctionalTester $I)
    {
        /*$I->submitForm($this->formId, [
            'LoginForm[username]' => 'ruben',
            'LoginForm[password]' => 'Admin*1234567',
        ]);
        $I->see('Logout');
        $I->dontSee('Login');
        $I->amOnPage('/web');*/

        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'ruben');
        $I->fillField('LoginForm[password]', 'Admin*1234567');
        $I->click('button[name="login-button"]');

        $I->see('Logout');
        $I->dontSee('Login');
        $I->amOnPage('/web');
    }
}

