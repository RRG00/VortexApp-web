<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Equipa;

class EquipaTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    public function testSomeFeature()
    {
    }

     public function testEquipaWithoutCamps()
    {
        $equipa = new Equipa();

        $this->assertFalse($equipa->validate());

        $erros = $equipa->getErrors();

        $this->assertArrayHasKey('nome', $erros);
        $this->assertArrayHasKey('data_criacao', $erros);
    }

    public function testEquipaWithValidCamps()
    {
        $equipa = new Equipa();

        $equipa->nome         = 'Equipa Teste';
        $equipa->data_criacao = date('Y-m-d H:i:s');

        $this->assertTrue($equipa->validate());
    }
}
