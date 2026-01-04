<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;

class LoginCest
{
    
    public function _before(FunctionalTester $I)
    {
    }

     /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {   
        $I->amOnRoute('/site/login');
        $I->see('Preencha os campos abaixo para iniciar sessão:');

        $I->fillField('LoginForm[username]', 'player');
        $I->fillField('LoginForm[password]', '12345678');
        $I->click('Login');
        
        //$I->see('Bem-vindo ao Vortex');
    }

}
