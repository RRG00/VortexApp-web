<?php

namespace frontend\controllers;

use common\models\Equipa;
use common\models\Inscricao;
use common\models\InscricaoTorneio;
use common\models\Tournament;
use common\models\MembrosEquipa;
use common\models\Partida; // Required to fetch matches
use yii;
use yii\filters\AccessControl;

class TournamentController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['inscricao'],
                        'roles' => ['insTournament'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $tournaments = Tournament::find()->all();
        return $this->render('index', ['torneios' => $tournaments]);
    }

    public function actionView($id)
    {
        $tournament = Tournament::findOne($id);
        if ($tournament === null) {
            throw new \yii\web\NotFoundHttpException('Torneio não encontrado');
        }

        $inscricoes = \common\models\Inscricao::find()
            ->where(['id_torneio' => $id])
            ->with('equipa')
            ->all();

        $inscritos = count($inscricoes);
        $limite    = $tournament->limite_inscricoes;
        $vagas     = max(0, $limite - $inscritos);

        $matches = Partida::find()
            ->where(['id_torneio' => $id])
            ->orderBy(['round' => SORT_ASC, 'match_index' => SORT_ASC])
            ->with(['equipaA', 'equipaB'])
            ->all();
        $rounds = [];
        if (!empty($matches)) {
            $totalRounds = $matches[count($matches)-1]->round + 1;

            foreach ($matches as $match) {
                $rIndex = $match->round;
                
                if (!isset($rounds[$rIndex])) {
                    $rounds[$rIndex] = [
                        'name' => $this->getRoundName($rIndex, $totalRounds),
                        'matches' => []
                    ];
                }
                $winner = null;
                if ($match->estado === 'Concluido') {
                    if ($match->vitorias_a > $match->vitorias_b) $winner = 1;
                    elseif ($match->vitorias_b > $match->vitorias_a) $winner = 2;
                }

                $rounds[$rIndex]['matches'][] = [
                    'score1' => $match->vitorias_a,
                    'score2' => $match->vitorias_b,
                    'winner' => $winner,
                    'team1' => ['name' => $match->equipaA ? $match->equipaA->nome : 'TBD'],
                    'team2' => ['name' => $match->equipaB ? $match->equipaB->nome : 'TBD']
                ];
            }
        }

        return $this->render('view', [
            'torneio'    => $tournament,
            'inscritos'  => $inscritos,
            'vagas'      => $vagas,
            'inscricoes' => $inscricoes,
            'brackets'   => ['rounds' => $rounds],
        ]);
    }

    private function getRoundName($roundIndex, $totalRounds) {
        $roundsFromFinal = ($totalRounds - 1) - $roundIndex;

        switch ($roundsFromFinal) {
            case 0: return "GRAND FINAL";
            case 1: return "SEMI-FINALS";
            case 2: return "QUARTER-FINALS";
            default: return "ROUND " . ($roundIndex + 1);
        }
    }

    public function actionInscricao($id)
    {
        $userId = Yii::$app->user->id;
        $membroEquipa = MembrosEquipa::findOne(['id_utilizador' => $userId]);
        if ($membroEquipa === null) {
            Yii::$app->session->setFlash('error', 'Não pertence a nenhuma equipa. Não pode inscrever-se no torneio.');
            return $this->redirect(['tournament/view', 'id' => $id]);
        }

        if ($membroEquipa->funcao !== 'capitao') {
            Yii::$app->session->setFlash('error', 'Apenas o capitão da equipa pode inscrever a equipa no torneio.');
            return $this->redirect(['tournament/view', 'id' => $id]);
        }

        $tournament = Tournament::findOne($id);
        if ($tournament === null) {
            Yii::$app->session->setFlash('error', 'Torneio não encontrado.');
            return $this->redirect(['tournament/index']);
        }

        $inscritos = Inscricao::find()
            ->where(['id_torneio' => $id])
            ->count();

        if ($inscritos >= $tournament->limite_inscricoes) {
            Yii::$app->session->setFlash('error', 'Limite de inscrições atingido para este torneio.');
            return $this->redirect(['tournament/view', 'id' => $id]);
        }

        $enrollment = new Inscricao();
        $enrollment->id_torneio    = $id;
        $enrollment->id_utilizador = $userId;
        $enrollment->id_equipa     = $membroEquipa->id_equipa;

        if ($enrollment->save()) {
            Yii::$app->session->setFlash('success', 'Equipa inscrita com sucesso!');
        } else {
            $errors = $enrollment->getFirstErrors();
            $msg = $errors ? implode(' ', $errors) : 'Erro ao inscrever equipa.';
            Yii::$app->session->setFlash('error', $msg);
        }

        return $this->redirect(['tournament/view', 'id' => $id]);
    }
}