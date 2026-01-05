<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $torneio common\models\Tournament */
/* @var $brackets array */ // Verify this variable is passed from Controller

$formatter = \Yii::$app->formatter;

// Register the Bracket CSS
$this->registerCssFile(Yii::getAlias('@web/css/referre-management.css'), [
    'depends' => [\yii\bootstrap5\BootstrapAsset::class]
]);

$userTeamId = null;
if (!Yii::$app->user->isGuest) {
    $member = \common\models\MembrosEquipa::findOne(['id_utilizador' => Yii::$app->user->id]);
    $userTeamId = $member ? $member->id_equipa : null;
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 fw-bold mb-2 text-white"><?= Html::encode($torneio->nome) ?></h1>
                    <span class="badge bg-primary fs-6 px-3 py-2"><?= Html::encode($torneio->jogo->nome) ?></span>
                </div>
            </div>

            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card shadow-sm" style="background-color: #252542; border: none;">
                                <div class="card-body">
                                    <h3 class="h5 text-primary mb-3">
                                        <i class="bi bi-info-circle-fill me-2"></i>Detalhes do Torneio
                                    </h3>
                                    <p class="text-white-50"><?= Html::encode($torneio->descricao) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card shadow-sm" style="background-color: #252542; border: none;">
                                <div class="card-body">
                                    <h3 class="h5 text-primary mb-3">
                                        <i class="bi bi-file-text-fill me-2"></i>Regras
                                    </h3>
                                    <p class="text-white-50"><?= Html::encode($torneio->regras) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card shadow-sm" style="background-color: #252542; border: none;">
                                <div class="card-body">
                                    <h3 class="h5 text-primary mb-3">
                                        <i class="bi bi-diagram-3-fill me-2"></i>Brackets do Torneio
                                    </h3>

                                    <div style="overflow-x: auto; padding-bottom: 10px;">
                                        <?php if (empty($brackets['rounds'])): ?>
                                            <p class="text-white-50 text-center">Os emparelhamentos ainda não foram gerados.</p>
                                        <?php else: ?>

                                            <div class="tournament-bracket">
                                                <?php foreach ($brackets['rounds'] as $roundIndex => $round): ?>

                                                    <div class="bracket-round">
                                                        <div class="bracket-round-title">
                                                            <?= Html::encode($round['name']) ?>
                                                        </div>

                                                        <div class="bracket-matches" style="display: flex; flex-direction: column; gap: 15px;">
                                                            <?php foreach ($round['matches'] as $match): ?>
                                                                <?php
                                                                $isFinal = ($roundIndex === count($brackets['rounds']) - 1);
                                                                ?>

                                                                <div class="bracket-match <?= $isFinal ? 'final' : '' ?>">

                                                                    <div class="match-team <?= $match['winner'] === 1 ? 'winner' : '' ?>">
                                                                        <span class="team-name">
                                                                            <?= Html::encode($match['team1']['name']) ?>
                                                                        </span>
                                                                        <span class="team-score">
                                                                            <?= $match['score1'] ?>
                                                                        </span>
                                                                    </div>

                                                                    <div class="match-team <?= $match['winner'] === 2 ? 'winner' : '' ?>">
                                                                        <span class="team-name">
                                                                            <?= Html::encode($match['team2']['name']) ?>
                                                                        </span>
                                                                        <span class="team-score">
                                                                            <?= $match['score2'] ?>
                                                                        </span>
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
                        <?php if (!empty($inscricoes)): ?>
                            <div class="col-12">
                                <div class="card shadow-sm mb-4" style="background-color: #252542; border: none;">
                                    <div class="card-body">
                                        <h3 class="h5 text-primary mb-3">
                                            <i class="bi bi-people-fill me-2"></i>Equipas inscritas
                                        </h3>
                                        <ul class="list-group list-group-flush" style="text-align: center;">
                                            <?php foreach ($inscricoes as $ins): ?>

                                                <?php
                                                // 2. Check if this is the user's team
                                                $isMyTeam = ($userTeamId !== null && $userTeamId == $ins->equipa->id);
                                                ?>

                                                <li class="list-group-item"
                                                    style="background-color:#252542; border: none; color: #fff; text-align: center; font-weight: bold;">

                                                    <?= Html::a(
                                                        Html::encode($ins->equipa->nome),
                                                        ['team/view', 'id' => $ins->equipa->id],
                                                        ['class' => 'team-link text-decoration-none text-white']
                                                    ) ?>

                                                    <?php if ($isMyTeam): ?>
                                                        <span class="badge bg-info text-dark ms-2" style="font-size: 0.75em;">
                                                            <i class="bi bi-person-check-fill"></i> Sua Equipa
                                                        </span>
                                                    <?php endif; ?>

                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-white-50">Ainda não há equipas inscritas.</p>
                            </div>
                        <?php endif; ?>

                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-primary mb-1">Best Of</h5>
                                    <p class="fs-4 fw-bold mb-0 text-white"><?= $torneio->best_of ?></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-success mb-1">Prémios</h5>
                                    <p class="fs-4 fw-bold mb-0 text-white"><?= Html::encode($torneio->premios) ?>€</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-info mb-1">Período</h5>
                                    <p class="small mb-0 fw-semibold text-white-50">
                                        Início: <?= $formatter->asDate($torneio->data_inicio, "php:d/m/Y") ?><br>
                                        Fim: <?= $formatter->asDate($torneio->data_fim, "php:d/m/Y") ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-warning mb-1">Inscrições</h5>
                                    <p class="fs-4 fw-bold mb-0 text-white">
                                        <?= $inscritos ?> / <?= $torneio->limite_inscricoes ?>
                                    </p>
                                    <p class="small mb-0 text-white-50">
                                        Vagas: <?= $vagas ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <?= Html::a(
                    '<i class="bi bi-trophy-fill me-2"></i>Inscrever Equipa',
                    ['tournament/inscricao', 'id' => $torneio->id],
                    [
                        'class' => 'btn btn-primary btn-lg py-3',
                        'data-method' => 'post',
                    ]
                ) ?>
            </div>

        </div>
    </div>
</div>