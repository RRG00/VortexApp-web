<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\LoginForm;

class LoginFormTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testSomeFeature()
    {
    }

    public function testLoginFailsWithWrongPassword()
    {
        $model = new LoginForm([
            'username' => 'admin',
            'password' => 'wrong',
        ]);

        $this->assertFalse($model->login());
    }

    public function testLoginFailsWithUnknownUser()
    {
        $model = new LoginForm([
            'username' => 'naoexiste',
            'password' => 'qualquer',
        ]);

        $this->assertFalse($model->login());
    }
}
