<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class SignupCest
{
    protected $formId = '#form-signup';


    public function signupUser(FunctionalTester $I)
    {
        $I->amOnRoute('site/signup');
        $I->see('Registar');
        $I->submitForm('#form-signup', [
            'SignupForm[username]' => 'testplayer_',
            'SignupForm[email]'    => 'testplayer_' . '@test.com',
            'SignupForm[password]' => '12345678',
        ]);

        $I->see('Thank you for registration');
    }
}
