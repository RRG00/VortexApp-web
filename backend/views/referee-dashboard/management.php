<?php

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */
/** @var common\models\Inscricao[] $inscricoes */
/** @var array $brackets */

use yii\helpers\Html;

$this->registerCssFile(Yii::getAlias('@web/css/referre-management.css'), [
    'depends' => [\yii\bootstrap4\BootstrapAsset::class]
]);

$this->registerJsFile('@web/js/tournament-management.js', [
    'depends' => [\yii\web\JqueryAsset::class]
]);

$this->title = 'Tournament Management | Vortex';

$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;

$this->registerJs("
    window.csrfParam = '{$csrfParam}';
    window.csrfToken = '{$csrfToken}';
", \yii\web\View::POS_HEAD);
?>


?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2><?= Html::encode($model->nome) ?></h2>
            <p class="text-muted">Gestão do Torneio</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Equipas Inscritas</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($inscricoes)): ?>
                        <div class="alert alert-info">
                            Nenhuma equipa inscrita neste torneio.
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($inscricoes as $inscricao): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">
                                            <?= Html::encode($inscricao->equipa->nome) ?>
                                        </h5>
                                        <small class="text-muted">ID: <?= $inscricao->equipa->id ?></small>
                                    </div>
                                    <p class="mb-1">
                                        <small>Data de Criação: <?= Yii::$app->formatter->asDate($inscricao->equipa->data_criacao, 'php:d/m/Y') ?></small>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <strong>Total de equipas: <?= count($inscricoes) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title text-white">Tournament Brackets</h3>
                </div>
                <div class="card-body" style="background-color: #2c3e5a; overflow-x: auto;">
                    <?php if (empty($brackets['rounds'])): ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            É necessário pelo menos 2 equipas inscritas para gerar os brackets.
                        </div>
                    <?php else: ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-white">
                                <small><i class="fas fa-info-circle"></i> Clique numa partida para definir o vencedor (BO3)</small>
                            </div>
                            <button class="btn btn-success btn-sm" id="saveBrackets">
                                <i class="fas fa-save"></i> Guardar Resultados
                            </button>
                        </div>
                        <div class="tournament-bracket" id="tournamentBracket">
                            <?php foreach ($brackets['rounds'] as $roundIndex => $round): ?>
                                <div class="bracket-round" data-round="<?= $roundIndex ?>">
                                    <h4 class="round-title"><?= Html::encode($round['name']) ?></h4>
                                    <div class="bracket-matches">
                                        <?php foreach ($round['matches'] as $matchIndex => $match): ?>
                                            <?php
                                                $isFinal = ($roundIndex === count($brackets['rounds']) - 1);
                                            ?>
                                            <div class="bracket-match <?= $isFinal ? 'final' : '' ?> clickable-match"
                                                 data-round="<?= $roundIndex ?>"
                                                 data-match="<?= $matchIndex ?>"
                                                 data-match-id="<?= $match['match_id'] ?? '' ?>"
                                                 data-partida-id="<?= $match['partida_id'] ?? '' ?>"
                                                 data-team1-id="<?= $match['team1']['id'] ?>"
                                                 data-team1-name="<?= Html::encode($match['team1']['name']) ?>"
                                                 data-team2-id="<?= $match['team2']['id'] ?>"
                                                 data-team2-name="<?= Html::encode($match['team2']['name']) ?>"
                                                 onclick="openMatchModal(this)">
                                                <div class="match-team <?= $match['winner'] === 1 ? 'winner' : '' ?>">
                                                    <img src="/img/team-icon.png" alt="" class="team-icon" onerror="this.style.display='none'">
                                                    <span class="team-name"><?= Html::encode($match['team1']['name']) ?></span>
                                                    <span class="team-score"><?= $match['score1'] ?></span>
                                                </div>
                                                <div class="match-team <?= $match['winner'] === 2 ? 'winner' : '' ?>">
                                                    <img src="/img/team-icon.png" alt="" class="team-icon" onerror="this.style.display='none'">
                                                    <span class="team-name"><?= Html::encode($match['team2']['name']) ?></span>
                                                    <span class="team-score"><?= $match['score2'] ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="matchModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="background: #2c3e5a; color: white;">
            <div class="modal-header" style="border-bottom: 1px solid #4a5f7f;">
                <h5 class="modal-title">Definir Resultado - BO3</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="match-selector">
                    <div class="team-selector" id="team1Selector">
                        <div class="team-header">
                            <img src="/img/team-icon.png" alt="" class="team-icon-large" onerror="this.style.display='none'">
                            <h4 id="team1Name">Team 1</h4>
                        </div>
                        <div class="map-results">
                            <h6>Mapas Vencidos</h6>
                            <div class="map-buttons">
                                <button class="map-btn" data-team="1" data-map="1">Mapa 1</button>
                                <button class="map-btn" data-team="1" data-map="2">Mapa 2</button>
                                <button class="map-btn" data-team="1" data-map="3">Mapa 3</button>
                            </div>
                            <div class="score-display">
                                Pontos: <span id="team1Score">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="vs-divider">VS</div>

                    <div class="team-selector" id="team2Selector">
                        <div class="team-header">
                            <img src="/img/team-icon.png" alt="" class="team-icon-large" onerror="this.style.display='none'">
                            <h4 id="team2Name">Team 2</h4>
                        </div>
                        <div class="map-results">
                            <h6>Mapas Vencidos</h6>
                            <div class="map-buttons">
                                <button class="map-btn" data-team="2" data-map="1">Mapa 1</button>
                                <button class="map-btn" data-team="2" data-map="2">Mapa 2</button>
                                <button class="map-btn" data-team="2" data-map="3">Mapa 3</button>
                            </div>
                            <div class="score-display">
                                Pontos: <span id="team2Score">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #4a5f7f;">
                <button type="button" class="btn btn-info" id="openStatsModal">
                    <i class="fas fa-chart-bar"></i> Inserir Estatísticas
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmMatch">Confirmar Resultado</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="statsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background: #2c3e5a; color: white;">
            <div class="modal-header" style="border-bottom: 1px solid #4a5f7f;">
                <h5 class="modal-title">Estatísticas dos Jogadores</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Team 1 Stats -->
                    <div class="col-md-6">
                        <div class="team-stats-section">
                            <h5 id="statsTeam1Name" class="text-center mb-3">Team 1</h5>
                            <div id="team1PlayersStats"></div>
                        </div>
                    </div>

                    <!-- Team 2 Stats -->
                    <div class="col-md-6">
                        <div class="team-stats-section">
                            <h5 id="statsTeam2Name" class="text-center mb-3">Team 2</h5>
                            <div id="team2PlayersStats"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #4a5f7f;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success" id="saveStats">
                    <i class="fas fa-save"></i> Guardar Estatísticas
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    window.tournamentId = {$model->id};
    window.gameId = {$model->id_jogo};
", \yii\web\View::POS_HEAD);
?>