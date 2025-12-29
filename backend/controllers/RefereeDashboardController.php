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
                            'actions' => ['index', 'update', 'management', 'get-team-players', 'save-stats'],
                            'roles' => ['viewResults'],
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
        $model = $this->findModel($id);
        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $data = json_decode(Yii::$app->request->rawBody, true);

            if (isset($data['brackets']) && isset($data['tournament_id'])) {
                $savedCount = 0;
                $errors = [];
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    foreach ($data['brackets'] as $matchData) {
                        if (
                            !isset($matchData['team1']['id']) || !isset($matchData['team2']['id']) ||
                            $matchData['team1']['id'] === null || $matchData['team2']['id'] === null ||
                            $matchData['team1']['id'] === 'null' || $matchData['team2']['id'] === 'null' ||
                            empty($matchData['team1']['id']) || empty($matchData['team2']['id'])
                        ) {
                            continue;
                        }

                        $partida = null;
                        if (!empty($matchData['partida_id'])) {
                            $partida = Partida::findOne($matchData['partida_id']);
                        }

                        if (!$partida) {
                            $partida = Partida::find()
                                ->where([
                                    'id_torneio' => $id,
                                    'equipa_a' => $matchData['team1']['id'],
                                    'equipa_b' => $matchData['team2']['id'],
                                ])
                                ->one();
                        }

                        if (!$partida) {
                            $partida = new Partida();
                            $partida->id_torneio = $id;
                            $partida->equipa_a = $matchData['team1']['id'];
                            $partida->equipa_b = $matchData['team2']['id'];
                            $partida->estado = Partida::ESTADO_PENDENTE;
                        }

                        // Scores
                        $partida->vitorias_a = (int)($matchData['team1']['score'] ?? 0);
                        $partida->vitorias_b = (int)($matchData['team2']['score'] ?? 0);

                        if (!empty($matchData['winner'])) {
                            $partida->estado = Partida::ESTADO_CONCLUIDA;
                        } elseif ($partida->vitorias_a > 0 || $partida->vitorias_b > 0) {
                            $partida->estado = Partida::ESTADO_EM_ANDAMENTO;
                        }
                        if ($partida->save()) {
                            $matchData['partida_id'] = $partida->id_partida;
                            $savedCount++;

                       
                            if ($partida->estado == Partida::ESTADO_CONCLUIDA && !empty($matchData['winner'])) {
                                $winnerTeamId = ($matchData['winner'] == 1) ? $partida->equipa_a : $partida->equipa_b;
                                $loserTeamId  = ($matchData['winner'] == 1) ? $partida->equipa_b : $partida->equipa_a;

                          
                                $winnerMembers = MembrosEquipa::find()->where(['id_equipa' => $winnerTeamId])->all();
                                $loserMembers  = MembrosEquipa::find()->where(['id_equipa' => $loserTeamId])->all();
                 
                                $gameId = $model->id_jogo;

                    
                                foreach ($winnerMembers as $membro) {
                                    $estat = Estatisticas::findOne([
                                        'id_utilizador' => $membro->id_utilizador,
                                        'id_jogo'       => $gameId,
                                    ]);
                                    if (!$estat) {
                                        $estat = new Estatisticas();
                                        $estat->id_utilizador = $membro->id_utilizador;
                                        $estat->id_jogo = $gameId;
                                        $estat->vitorias = 0;
                                        $estat->derrotas = 0;
                                    }
                                    $estat->vitorias++;
                                    $estat->save(false);
                                }

                                foreach ($loserMembers as $membro) {
                                    $estat = Estatisticas::findOne([
                                        'id_utilizador' => $membro->id_utilizador,
                                        'id_jogo'       => $gameId,
                                    ]);
                                    if (!$estat) {
                                        $estat = new Estatisticas();
                                        $estat->id_utilizador = $membro->id_utilizador;
                                        $estat->id_jogo = $gameId;
                                        $estat->vitorias = 0;
                                        $estat->derrotas = 0;
                                    }
                                    $estat->derrotas++;
                                    $estat->save(false);
                                }
                            }
                        } else {
                            $errors[] = $partida->getErrors();
                        }
                    }

                    Yii::$app->session->set('tournament_brackets_' . $id, $data['brackets']);
                    $transaction->commit();

                    return [
                        'success' => empty($errors),
                        'message' => empty($errors)
                            ? "$savedCount partida(s) guardada(s) com sucesso!"
                            : 'Algumas partidas não foram guardadas.',
                        'errors' => $errors,
                    ];
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    return [
                        'success' => false,
                        'message' => 'Erro ao guardar partidas: ' . $e->getMessage(),
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Dados inválidos',
            ];
        }

        $inscricoes = Inscricao::find()
            ->where(['id_torneio' => $id])
            ->with('equipa')
            ->all();

        $brackets = $this->generateBrackets($inscricoes);

        $savedBrackets = $this->loadBracketsFromDatabase($id);
        if (empty($savedBrackets)) {
            $savedBrackets = Yii::$app->session->get('tournament_brackets_' . $id);
        }
        if ($savedBrackets) {
            $brackets = $this->mergeSavedBrackets($brackets, $savedBrackets);
        }

        return $this->render('management', [
            'model' => $model,
            'inscricoes' => $inscricoes,
            'brackets' => $brackets,
        ]);
    }


    /**
     * Generate tournament brackets based on registered teams
     * @param array $inscricoes Array of Inscricao models
     * @return array Bracket structure with rounds
     */
    private function generateBrackets($inscricoes)
    {
        $teams = [];
        foreach ($inscricoes as $inscricao) {
            $teams[] = [
                'id' => $inscricao->equipa->id,
                'nome' => $inscricao->equipa->nome,
            ];
        }

        $teamCount = count($teams);

        // If less than 2 teams, can't create brackets
        if ($teamCount < 2) {
            return ['rounds' => []];
        }

        // Pad teams to next power of 2
        $bracketSize = pow(2, ceil(log($teamCount, 2)));
        while (count($teams) < $bracketSize) {
            $teams[] = ['id' => null, 'nome' => 'TBD'];
        }

        $rounds = [];
        $matchIdCounter = 0; // Track match IDs across rounds

        // Create first round (Quarter-finals, Round of 16, etc.)
        $currentRound = [];
        for ($i = 0; $i < count($teams); $i += 2) {
            $currentRound[] = [
                'match_id' => $matchIdCounter++,
                'team1' => $teams[$i],
                'team2' => $teams[$i + 1],
                'score1' => 0,
                'score2' => 0,
                'winner' => null,
            ];
        }

        // Determine round names
        $roundNames = $this->getRoundNames($bracketSize);
        $rounds[] = [
            'name' => $roundNames[0],
            'matches' => $currentRound,
        ];

        // Generate subsequent rounds
        for ($r = 1; $r < log($bracketSize, 2); $r++) {
            $nextRound = [];
            for ($i = 0; $i < count($currentRound); $i += 2) {
                $nextRound[] = [
                    'match_id' => $matchIdCounter++,
                    'team1' => ['id' => null, 'nome' => 'TBD'],
                    'team2' => ['id' => null, 'nome' => 'TBD'],
                    'score1' => 0,
                    'score2' => 0,
                    'winner' => null,
                ];
            }
            $rounds[] = [
                'name' => $roundNames[$r] ?? 'Round ' . ($r + 1),
                'matches' => $nextRound,
            ];
            $currentRound = $nextRound;
        }

        return ['rounds' => $rounds];
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

    /**
     * Merge saved bracket data with generated brackets
     * Load from Partida table in database
     */
    private function mergeSavedBrackets($brackets, $savedData)
    {
        if (empty($savedData) || empty($brackets['rounds'])) {
            return $brackets;
        }

        foreach ($savedData as $savedMatch) {
            $round = $savedMatch['round'];
            $matchIndex = $savedMatch['match'];

            if (isset($brackets['rounds'][$round]['matches'][$matchIndex])) {
                $brackets['rounds'][$round]['matches'][$matchIndex] = [
                    'match_id' => $savedMatch['match_id'] ?? null,
                    'partida_id' => $savedMatch['partida_id'] ?? null,
                    'team1' => $savedMatch['team1'],
                    'team2' => $savedMatch['team2'],
                    'score1' => $savedMatch['team1']['score'],
                    'score2' => $savedMatch['team2']['score'],
                    'winner' => $savedMatch['winner'],
                ];
            }
        }

        return $brackets;
    }

    /**
     * Load bracket data from Partida table
     * @param int $tournamentId
     * @return array Saved bracket data
     */
    private function loadBracketsFromDatabase($tournamentId)
    {
        $partidas = Partida::find()
            ->where(['id_torneio' => $tournamentId])
            ->all();

        $savedData = [];
        foreach ($partidas as $partida) {
            // Extract round and match index from saved data (will be added to Partida table)
            // For now, we'll reconstruct from session if available
            $sessionData = Yii::$app->session->get('tournament_brackets_' . $tournamentId);
            if ($sessionData) {
                foreach ($sessionData as $match) {
                    if (isset($match['partida_id']) && $match['partida_id'] == $partida->id_partida) {
                        $savedData[] = [
                            'round' => $match['round'],
                            'match' => $match['match'],
                            'match_id' => $match['match_id'] ?? null,
                            'partida_id' => $partida->id_partida,
                            'team1' => [
                                'id' => $partida->equipa_a,
                                'nome' => $partida->equipaA ? $partida->equipaA->nome : 'TBD',
                                'score' => $partida->vitorias_a,
                            ],
                            'team2' => [
                                'id' => $partida->equipa_b,
                                'nome' => $partida->equipaB ? $partida->equipaB->nome : 'TBD',
                                'score' => $partida->vitorias_b,
                            ],
                            'winner' => ($partida->vitorias_a >= 2) ? 1 : (($partida->vitorias_b >= 2) ? 2 : null),
                        ];
                    }
                }
            }
        }

        return $savedData;
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
