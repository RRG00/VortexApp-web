<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Tournament;

class TournamentTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before() {}

    public function testTournamentWithoutCamps()
    {
        $tournament = new Tournament();

        $this->assertFalse($tournament->validate());

        $erros = $tournament->getErrors();

        $this->assertArrayHasKey('nome', $erros);
        $this->assertArrayHasKey('best_of', $erros);
        $this->assertArrayHasKey('limite_inscricoes', $erros);
        $this->assertArrayHasKey('data_inicio', $erros);
        $this->assertArrayHasKey('data_fim', $erros);
        $this->assertArrayHasKey('estado', $erros);
        $this->assertArrayHasKey('organizador_id', $erros);
        $this->assertArrayHasKey('id_jogo', $erros);
    }

    public function testTournamentWithCamps()
    {
        $tournament = new Tournament();

        $tournament->nome = "Torneio Teste";
        $tournament->best_of = 3;
        $tournament->limite_inscricoes = 16;
        $tournament->data_inicio = '2024-10-01 10:00:00';
        $tournament->data_fim = '2024-10-10 18:00:00';
        $tournament->estado = 'Em breve';
        $tournament->organizador_id = 1;
        $tournament->id_jogo = 1;
        

        $this->assertTrue($tournament->validate());
    }
}
