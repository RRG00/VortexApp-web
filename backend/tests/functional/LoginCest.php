<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\models\User;
use common\models\LoginForm;

/**
 * Class LoginCest
 */
class LoginCest
{

    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {   
        $I->amOnRoute('/site/login');
        $I->see('Preencha os campos abaixo para iniciar sessão:');

        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', '12345678');
        $I->click('Login');
        
        $I->see('Bem-vindo, admin!');
    }
}
