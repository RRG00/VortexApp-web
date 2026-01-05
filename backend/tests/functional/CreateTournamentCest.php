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

        $I->see('Gestão de Torneios');
    }

    public function createTournament(FunctionalTester $I)
{
    $this->loginAsOrg($I);

    $I->amOnRoute('tournament/create');

    $name = 'Torneio Funcional ' . time();

    $I->submitForm('form', [
        'Tournament[nome]'             => $name,
        'Tournament[limite_inscricoes]' => 8,
        'Tournament[regras]'           => 'Regras teste',
        'Tournament[descricao]'        => 'Descrição teste',
        'Tournament[requisitos]'       => 'Requisitos teste',
        'Tournament[premios]'          => '100',
        'Tournament[data_inicio]'      => '2026-01-10',
        'Tournament[data_fim]'         => '2026-01-20',
        'Tournament[arbitro_id]'       => 1,   
        'Tournament[id_jogo]'          => 1,   
    ]);

    $I->see('Gestão de Torneios');
    $I->see($name);
}

}
