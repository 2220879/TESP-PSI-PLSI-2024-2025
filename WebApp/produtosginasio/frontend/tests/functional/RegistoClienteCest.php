<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class RegistoClienteCest
{
    protected $formId = '#form-signup';

    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/signup');
    }

    public function signupWithEmptyFields(FunctionalTester $I)
    {
        $I->see('Signup', 'h1');
        $I->see('Please fill out the following fields to signup:');
        $I->submitForm($this->formId, []);
        $I->seeValidationError('Username cannot be blank.');
        $I->seeValidationError('Email cannot be blank.');
        $I->seeValidationError('Password cannot be blank.');
        $I->seeValidationError('NIF cannot be blank.');
        $I->seeValidationError('Morada cannot be blank.');
        $I->seeValidationError('Telefone cannot be blank.');
    }

    public function registoComPasswordComPoucosCarateres(FunctionalTester $I)
    {
        $I->submitForm(
            $this->formId, [
                'SignupForm[username]' => 'Diogo Major Vieira',
                'SignupForm[email]' => 'diogomajor1993@outlook.pt',
                'SignupForm[password]' => '123456',
                'SignupForm[nif]' => '249561700',
                'SignupForm[morada]' => 'Mira de Aire',
                'SignupForm[telefone]' => '937896545',
            ]
        );
        $I->dontSee('Username cannot be blank.', '.invalid-feedback');
        $I->dontSee('Password must be at least 12 characters.', '.invalid-feedback');
        $I->dontSee('Email cannot be blank.', '.invalid-feedback');
        $I->dontSee('NIF cannot be blank.', '.invalid-feedback');
        $I->dontSee('Morada cannot be blank.', '.invalid-feedback');
        $I->dontSee('Telefone cannot be blank.', '.invalid-feedback');
    }

    public function registoComPasswordComExcessoDeCarateres(FunctionalTester $I)
    {
        $I->submitForm(
            $this->formId, [
                'SignupForm[username]' => 'Diogo Major Vieira',
                'SignupForm[email]' => 'diogomajor1993@outlook.pt',
                'SignupForm[password]' => 'Admin*12345678965',
                'SignupForm[nif]' => '249561700',
                'SignupForm[morada]' => 'Mira de Aire',
                'SignupForm[telefone]' => '937896545',
            ]
        );
        $I->dontSee('Username cannot be blank.', '.invalid-feedback');
        $I->dontSee('Password must be at maximum 16 characters.', '.invalid-feedback');
        $I->dontSee('Email cannot be blank.', '.invalid-feedback');
        $I->dontSee('NIF cannot be blank.', '.invalid-feedback');
        $I->dontSee('Morada cannot be blank.', '.invalid-feedback');
        $I->dontSee('Telefone cannot be blank.', '.invalid-feedback');
    }

    public function signupSuccessfully(FunctionalTester $I)
    {
        $I->submitForm($this->formId, [
            'SignupForm[username]' => 'ruben',
            'SignupForm[email]' => 'ruben@gmail.com',
            'SignupForm[password]' => 'Admin*1234567',
            'SignupForm[nif]' => '123456789',
            'SignupForm[morada]' => 'Leiria',
            'SignupForm[telefone]' => '918775794',
        ]);

        $I->seeRecord('common\models\User', [
            'username' => 'ruben',
            'email' => 'ruben@gmail.com',
            'status' => \common\models\User::STATUS_ACTIVE
        ]);

        $I->seeRecord('common\models\Profile', [
            'nif' => '123456789',
            'morada' => 'Leiria',
            'telefone' => '918775794',
        ]);

        $I->amOnPage('/login');
        $I->see('Login');
    }
}
