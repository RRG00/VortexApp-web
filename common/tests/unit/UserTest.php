<?php


namespace common\tests\unit;

use common\tests\UnitTester;
use common\models\User;

class UserTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {

    }

    public function testUserWithoutCamps()
    {
        $user = new User();

        $this -> assertFalse($user->validate());
        $erros = $user ->getErrors();
        $this->assertArrayHasKey('username', $erros);
        $this->assertArrayHasKey('email', $erros);
        $this->assertArrayHasKey('auth_key', $erros);
        $this->assertArrayHasKey('password_hash', $erros);

    }

    public function testUserWrongCamps(){

        $user = new User();
        $user->username = '';
        $user->email= 'email_invalido';
        $user->auth_key = str_repeat('a', 40);
        $user->password = '1234';

        $this->assertFalse($user->validate());

    }

    public function testUserCorrectCamps(){

        $user = new User();
        $user->username = 'username_teste';
        $user->email= 'test@test.com';
        $user->auth_key = \Yii::$app->security->generateRandomString();
        $user->password = 'Password123';

        $this->assertTrue($user->validate());
    }

}
