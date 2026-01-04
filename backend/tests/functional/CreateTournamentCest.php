<?php

namespace backend\tests\Functional;

use backend\tests\FunctionalTester;

final class CreateTournamentCest
{
    protected function loginAsOrg(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'org',
            'LoginForm[password]' => '12345678',
        ]);

        $I->see('Bem-vindo ao Vortex');
    }

     public function createTournament(FunctionalTester $I)
        {
        $this->loginAsOrg($I);

        $I->amOnRoute('tournament/create'); 

        $I->submitForm('form', [
            'Tournament[nome]'        => 'Torneio Funcional ' . time(),
            'Tournament[jogo_id]'     => 1,              
            'Tournament[data_inicio]' => '2026-01-10',
            'Tournament[data_fim]'    => '2026-01-20',
        ]);

        $I->see('Torneio Funcional'); 
    }

}
