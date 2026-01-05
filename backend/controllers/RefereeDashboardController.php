<?php

namespace backend\controllers;

use common\models\Inscricao;
use common\models\Equipa;
use common\models\MembrosEquipa;
use common\models\Estatisticas;
use common\models\Partida;
use Yii;
use yii\web\Controller;
use common\models\Tournament;
use common\models\TournamentSearch;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * RefereeDashboard controller
 */
class RefereeDashboardController extends Controller
{

    public $enableCsrfValidation = false;


    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'update', 'management', 'get-team-players', 'save-stats', 'start-tournament', 'finish-tournament'],
                            'roles' => ['viewResults', 'updateResults', 'startTournament'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new TournamentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        if (!Yii::$app->user->isGuest) {
            $auth = Yii::$app->authManager;
            $roles = $auth->getRolesByUser(Yii::$app->user->id);

            if (!isset($roles['admin'])) {
                $dataProvider->query->andWhere([
                    'arbitro_id' => Yii::$app->user->id,
                ]);
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionManagement($id)
    {
        // HANDLE SAVING (POST REQUEST)
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            return $this->processMatchUpdate($id);
        }

        // HANDLE VIEWING (GET REQUEST)
        $model = $this->findModel($id);

        // 1. Fetch ALL matches from DB ordered by structure
        $matches = Partida::find()
            ->where(['id_torneio' => $id])
            ->orderBy(['round' => SORT_ASC, 'match_index' => SORT_ASC])
            ->with(['equipaA', 'equipaB']) // Optimization: Eager Load
            ->all();

        // 2. Format for the View
        $rounds = [];
        foreach ($matches as $match) {
            $rIndex = $match->round;

            // Initialize round if not exists
            if (!isset($rounds[$rIndex])) {
                $rounds[$rIndex] = [
                    'name' => $this->getRoundName($rIndex, count($matches)), // Helper method below
                    'matches' => []
                ];
            }

            // Determine winner for UI class
            $winner = null;
            if ($match->estado === Partida::ESTADO_CONCLUIDA) {
                if ($match->vitorias_a > $match->vitorias_b) $winner = 1;
                elseif ($match->vitorias_b > $match->vitorias_a) $winner = 2;
            }

            $rounds[$rIndex]['matches'][] = [
                'match_id' => $match->match_index, // Visual Index
                'partida_id' => $match->id_partida, // DATABASE ID (Vital)
                'score1' => $match->vitorias_a,
                'score2' => $match->vitorias_b,
                'winner' => $winner,
                'team1' => [
                    'id' => $match->equipa_a,
                    'name' => $match->equipaA ? $match->equipaA->nome : 'TBD'
                ],
                'team2' => [
                    'id' => $match->equipa_b,
                    'name' => $match->equipaB ? $match->equipaB->nome : 'TBD'
                ]
            ];
        }

        return $this->render('management', [
            'model' => $model,
            'inscricoes' => Inscricao::findAll(['id_torneio' => $id]),
            'brackets' => ['rounds' => $rounds], // Pass the structure
        ]);
    }

    // Helper for round names
    private function getRoundName($roundIndex, $totalMatches)
    {
        // Logic to name rounds (Quarter Finals, Semi Finals, etc)
        // You can keep your existing getRoundNames logic or simplify:
        return "Round " . ($roundIndex + 1);
    }

    // Add this to your behaviors/access rules if needed
    public function actionStartTournament($id)
    {
        $tournament = Tournament::findOne($id);
        $inscricoes = Inscricao::findAll(['id_torneio' => $id]);

        // 1. Shuffle and prep teams
        shuffle($inscricoes);
        $teams = [];
        foreach ($inscricoes as $ins) $teams[] = $ins->id_equipa;

        $teamCount = count($teams);
        if ($teamCount < 2) return $this->redirect(['management', 'id' => $id]);

        // Calculate Bracket Size (2, 4, 8, 16...)
        $numRounds = ceil(log($teamCount, 2));
        $bracketSize = pow(2, $numRounds);

        // Clear existing matches for this tournament to avoid duplicates
        Partida::deleteAll(['id_torneio' => $id]);

        // 2. Generate Empty Bracket Tree
        for ($round = 0; $round < $numRounds; $round++) {
            $matchesInRound = $bracketSize / pow(2, $round + 1);

            for ($i = 0; $i < $matchesInRound; $i++) {
                $match = new Partida();
                $match->id_torneio = $id;
                $match->round = $round;     // 0 = Round 1 (First Round)
                $match->match_index = $i;   // Vertical position (0, 1, 2...)
                $match->estado = Partida::ESTADO_PENDENTE;
                $match->vitorias_a = 0;
                $match->vitorias_b = 0;

                // Populate Round 1 with real teams
                if ($round == 0) {
                    $teamA_Index = $i * 2;
                    $teamB_Index = ($i * 2) + 1;

                    $match->equipa_a = $teams[$teamA_Index] ?? null; // Null means "Bye"
                    $match->equipa_b = $teams[$teamB_Index] ?? null;

                    // Auto-advance if a team has a "Bye" (no opponent)
                    if ($match->equipa_a && !$match->equipa_b) {
                        $match->estado = Partida::ESTADO_CONCLUIDA;
                        $match->vitorias_a = 1; // Auto win
                        // We will handle the propagation in the save logic or recursivley here, 
                        // but for simplicity, let's just save the match first.
                    }
                } else {
                    // Future rounds start empty
                    $match->equipa_a = null;
                    $match->equipa_b = null;
                }

                $match->save();
            }
        }

        return $this->redirect(['management', 'id' => $id]);
    }

    private function processMatchUpdate($tournamentId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 1. Get Tournament to save the winner later
        $tournament = Tournament::findOne($tournamentId);
        if (!$tournament) return ['success' => false, 'message' => 'Torneio não encontrado'];

        if ($tournament->estado === 'Concluido') {
            return ['success' => false, 'message' => 'O torneio foi encerrado (Concluido).'];
        }

        $gameId = $tournament->id_jogo;
        $data = json_decode(Yii::$app->request->rawBody, true);

        if (!isset($data['brackets'])) return ['success' => false];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($data['brackets'] as $matchData) {
                if (empty($matchData['partida_id'])) continue;

                $partida = Partida::findOne($matchData['partida_id']);
                if (!$partida) continue;

                // Check if match was already finished (to avoid double stats)
                $wasConcluded = ($partida->estado === Partida::ESTADO_CONCLUIDA);

                // Update Scores
                $partida->vitorias_a = (int)$matchData['team1']['score'];
                $partida->vitorias_b = (int)$matchData['team2']['score'];

                // === CHECK IF A WINNER WAS SELECTED ===
                if ($matchData['winner'] !== null) {
                    $partida->estado = Partida::ESTADO_CONCLUIDA;

                    // Identify Winner and Loser IDs
                    $winnerId = ($matchData['winner'] == 1) ? $partida->equipa_a : $partida->equipa_b;
                    $loserId  = ($matchData['winner'] == 1) ? $partida->equipa_b : $partida->equipa_a;

                    // --- PROGRESSION LOGIC ---
                    // Calculate where the winner should go next
                    $nextRound = $partida->round + 1;
                    $nextMatchIndex = floor($partida->match_index / 2);

                    // Try to find the next match in the database
                    $nextMatch = Partida::findOne([
                        'id_torneio' => $tournamentId,
                        'round' => $nextRound,
                        'match_index' => $nextMatchIndex
                    ]);

                    if ($nextMatch) {
                        // CASE A: Next match exists -> ADVANCE WINNER TO NEXT ROUND
                        if ($partida->match_index % 2 == 0) {
                            $nextMatch->equipa_a = $winnerId;
                        } else {
                            $nextMatch->equipa_b = $winnerId;
                        }
                        $nextMatch->save();
                    } else {
                        // CASE B: No next match exists -> THIS IS THE FINAL!
                        // SAVE THE TOURNAMENT WINNER
                        $tournament->vencedor = $winnerId;
                        $tournament->save(false); // save(false) skips validation to ensure it writes
                    }
                    // -------------------------

                    // --- STATS LOGIC (Only run if match wasn't already finished) ---
                    if (!$wasConcluded) {
                        $this->updatePlayerStats($winnerId, $gameId, 'win');
                        $this->updatePlayerStats($loserId, $gameId, 'loss');
                    }
                } else {
                    // === RESET LOGIC (If user un-selects the winner) ===
                    $partida->estado = Partida::ESTADO_EM_ANDAMENTO;

                    // If this was the final, we must REMOVE the winner from the tournament table
                    // We check if it was the final by looking for a next match again
                    $nextRound = $partida->round + 1;
                    $nextMatchIndex = floor($partida->match_index / 2);
                    $nextMatch = Partida::findOne(['id_torneio' => $tournamentId, 'round' => $nextRound, 'match_index' => $nextMatchIndex]);

                    if (!$nextMatch) {
                        // It was the final, clear the winner
                        $tournament->vencedor = null;
                        $tournament->save(false);
                    }
                }

                $partida->save();
            }

            $transaction->commit();
            return ['success' => true, 'message' => 'Resultados guardados!'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Helper function to keep code clean
    private function updatePlayerStats($teamId, $gameId, $type)
    {
        if (!$teamId) return;
        $members = MembrosEquipa::findAll(['id_equipa' => $teamId]);

        foreach ($members as $membro) {
            $estat = Estatisticas::findOne(['id_utilizador' => $membro->id_utilizador, 'id_jogo' => $gameId]);
            if (!$estat) {
                $estat = new Estatisticas();
                $estat->id_utilizador = $membro->id_utilizador;
                $estat->id_jogo = $gameId;
            }

            if ($type === 'win') $estat->vitorias++;
            else $estat->derrotas++;

            $estat->save(false);
        }
    }

    public function actionFinishTournament($id)
{
    $model = $this->findModel($id);
    
    // Check if already finished
    if ($model->estado === 'Concluido') {
        Yii::$app->session->setFlash('warning', 'O torneio já se encontra encerrado.');
        return $this->redirect(['management', 'id' => $id]);
    }

    // Update the state to your database value 'Concluido'
    $model->estado = 'Concluido';
    
    if ($model->save()) {
        Yii::$app->session->setFlash('success', 'Torneio encerrado com sucesso! Edições bloqueadas.');
    } else {
        Yii::$app->session->setFlash('error', 'Erro ao encerrar o torneio.');
    }

    return $this->redirect(['management', 'id' => $id]);
}


    /**
     * Get round names based on bracket size
     */
    private function getRoundNames($bracketSize)
    {
        $names = [];
        $numRounds = log($bracketSize, 2);

        if ($numRounds == 1) {
            $names[] = 'FINAL';
        } elseif ($numRounds == 2) {
            $names[] = 'SEMI-FINALS';
            $names[] = 'GRAND FINAL';
        } elseif ($numRounds == 3) {
            $names[] = 'QUARTER-FINALS';
            $names[] = 'SEMI-FINALS';
            $names[] = 'GRAND FINAL';
        } elseif ($numRounds >= 4) {
            $names[] = 'ROUND OF ' . $bracketSize;
            for ($i = 1; $i < $numRounds - 2; $i++) {
                $names[] = 'ROUND OF ' . ($bracketSize / pow(2, $i));
            }
            $names[] = 'SEMI-FINALS';
            $names[] = 'GRAND FINAL';
        }

        return $names;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Get team players for stats input
     */
    public function actionGetTeamPlayers()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $team1Id = Yii::$app->request->get('team1');
        $team2Id = Yii::$app->request->get('team2');

        if (!$team1Id || !$team2Id) {
            return [
                'success' => false,
                'message' => 'IDs de equipas inválidos'
            ];
        }

        // Get team 1 players
        $team1Players = MembrosEquipa::find()
            ->where(['id_equipa' => $team1Id])
            ->with('user')
            ->all();

        // Get team 2 players
        $team2Players = MembrosEquipa::find()
            ->where(['id_equipa' => $team2Id])
            ->with('user')
            ->all();

        $team1Data = [];
        foreach ($team1Players as $member) {
            if ($member->user) {
                $team1Data[] = [
                    'id' => $member->user->id,
                    'username' => $member->user->username,
                ];
            }
        }

        $team2Data = [];
        foreach ($team2Players as $member) {
            if ($member->user) {
                $team2Data[] = [
                    'id' => $member->user->id,
                    'username' => $member->user->username,
                ];
            }
        }

        return [
            'success' => true,
            'team1Players' => $team1Data,
            'team2Players' => $team2Data,
        ];
    }

    /**
     * Save player statistics
     */
    public function actionSaveStats()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $data = json_decode(Yii::$app->request->rawBody, true);

            if (!isset($data['stats']) || !isset($data['game_id'])) {
                return [
                    'success' => false,
                    'message' => 'Dados inválidos'
                ];
            }

            $savedCount = 0;
            $errors = [];

            foreach ($data['stats'] as $stat) {
                // Check if stat already exists
                $estatistica = Estatisticas::find()
                    ->where([
                        'id_utilizador' => $stat['playerId'],
                        'id_jogo' => $data['game_id']
                    ])
                    ->one();

                if (!$estatistica) {
                    $estatistica = new Estatisticas();
                    $estatistica->id_utilizador = $stat['playerId'];
                    $estatistica->id_jogo = $data['game_id'];
                    $estatistica->vitorias = 0;
                    $estatistica->derrotas = 0;
                }

                // Update stats (assuming kills are stored in pontuacao for now)
                // You may need to add kills/deaths columns to your table
                $estatistica->pontuacao = $stat['kills']; // Kills
                $estatistica->kd = $stat['kd'];

                if ($estatistica->save()) {
                    $savedCount++;
                } else {
                    $errors[] = $estatistica->getErrors();
                }
            }

            if (!empty($errors)) {
                return [
                    'success' => false,
                    'message' => 'Erro ao guardar algumas estatísticas',
                    'errors' => $errors
                ];
            }

            return [
                'success' => true,
                'message' => "$savedCount estatísticas guardadas com sucesso!"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ];
        }
    }

    protected function findModel($id)
    {
        if (($model = Tournament::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
