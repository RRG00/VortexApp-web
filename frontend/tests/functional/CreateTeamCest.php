<?php




namespace frontend\tests\Functional;

use frontend\tests\FunctionalTester;

final class CreateTeamCest
{
    protected function loginAsPlayer(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'player',
            'LoginForm[password]' => '12345678',
        ]);

        $I->see('Bem-vindo ao Vortex');
    }

    public function createTeam(FunctionalTester $I)
    {
        $this->loginAsPlayer($I);

        $I->amOnRoute('equipa/create');
        $I->submitForm('form', [
            'Equipa[nome_equipa]' => 'Equipa Funcional ' . time(),
        ]);

        $I->see('Equipa Funcional');
    }
}
