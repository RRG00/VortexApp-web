<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Estatisticas[] $estatisticas */
/** @var int $totalVitorias */
/** @var int $totalDerrotas */
/** @var int $totalJogos */
/** @var float $winRate */

$this->title = 'Perfil - ' . Html::encode($user->username);
$initials = strtoupper(substr($user->username, 0, 2));
?>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($user->profileImage): ?>
                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($user->profileImage->path) ?>"
                    alt="<?= Html::encode($user->username) ?>"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            <?php else: ?>
                <?= $initials ?>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1><?= Html::encode($user->username) ?></h1>
            <p><strong>Email:</strong> <?= Html::encode($user->email) ?></p>
            <p><strong>Membro desde:</strong> <?= Yii::$app->formatter->asDate($user->created_at, 'long') ?></p>
            <a href="<?= Url::to(['/profile/edit-profile']) ?>" class="btn btn-primary">Editar Perfil</a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total de Jogos</div>
            <div class="stat-value"><?= $totalJogos ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Vitórias</div>
            <div class="stat-value" style="color: #48bb78;"><?= $totalVitorias ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Derrotas</div>
            <div class="stat-value" style="color: #f56565;"><?= $totalDerrotas ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Taxa de Vitórias</div>
            <div class="stat-value"><?= $winRate ?>%</div>
        </div>
    </div>

    <!-- Team Information -->
    <div class="stats-grid">
        <div class="stat-card" style="grid-column: span 2;">
            <div class="stat-label">Equipa</div>
            <div class="stat-value" style="font-size: 1.1em;">
                <?php if ($userTeam): ?>
                    <div class="team-info">
                        <p><strong>Nome:</strong> <?= Html::a(Html::encode($userTeam->nome), ['/team/view', 'id' => $userTeam->id]) ?></p>
                        <p><strong>Capitão:</strong> <?= $userTeam->capitao ? Html::a(Html::encode($userTeam->capitao->user->username), ['/profile/view', 'id' => $userTeam->capitao->user->id]) : 'N/A' ?></p>
                        <p><strong>Data de criação:</strong> <?= Yii::$app->formatter->asDate($userTeam->data_criacao, 'short') ?></p>
                        <a href="<?= Url::to(['/team/view', 'id' => $userTeam->id]) ?>" class="btn btn-primary btn-sm">Ver</a>
                    </div>
                <?php else: ?>
                    <div class="no-team">
                        <p>Sem equipa.</p>
                        <a href="<?= Url::to(['/team/create']) ?>" class="btn btn-primary btn-sm">Criar</a>
                        <?php if (!empty($invitations)): ?>
                            <div class="mt-3">
                                <h5>Convites pendentes</h5>
                                <?php foreach ($invitations as $invite): ?>
                                    <?php $data = json_decode($invite->convite, true); $team = \common\models\Equipa::findOne($data['team_id'] ?? null); ?>
                                    <div class="card p-2 mb-2">
                                        <div>
                                            <strong><?= Html::encode($team->nome ?? 'Equipa desconhecida') ?></strong>
                                            <div class="small text-muted">Convite enviado por <?= Html::encode($data['inviter_username'] ?? ''); ?></div>
                                        </div>
                                        <div class="mt-2">
                                            <?= Html::beginForm(['/profile/accept-invite', 'id' => $invite->id_notificacao], 'post', ['style' => 'display:inline-block;']) ?>
                                            <?= Html::submitButton('Aceitar', ['class' => 'btn btn-success btn-sm', 'data-confirm' => 'Aceitar convite?']) ?>
                                            <?= Html::endForm() ?>

                                            <?= Html::beginForm(['/profile/decline-invite', 'id' => $invite->id_notificacao], 'post', ['style' => 'display:inline-block; margin-left:8px;']) ?>
                                            <?= Html::submitButton('Recusar', ['class' => 'btn btn-danger btn-sm', 'data-confirm' => 'Recusar convite?']) ?>
                                            <?= Html::endForm() ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="font-size: 0.8em; color: #6c757d;">Aguarde convite.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Games Statistics -->
    <div class="games-section">
        <h2>Estatisticas por Jogo</h2>
        <?php if (!empty($estatisticas)): ?>
            <?php foreach ($estatisticas as $stat): ?>
                <div class="game-stat">
                    <div class="game-name">
                        <?= Html::encode($stat->jogo->nome ?? 'Jogo Desconhecido') ?>
                    </div>
                    <div class="game-stats">
                        <div class="game-stat-item">
                            <span>Vitorias</span>
                            <strong style="color: #48bb78;"><?= Html::encode($stat->vitorias) ?></strong>
                        </div>
                        <div class="game-stat-item">
                            <span>Derrotas</span>
                            <strong style="color: #f56565;"><?= Html::encode($stat->derrotas) ?></strong>
                        </div>
                        <div class="game-stat-item">
                            <span>K/D</span>
                            <strong><?= Html::encode(number_format($stat->kd, 2)) ?></strong>
                        </div>
                        <div class="game-stat-item">
                            <span>Pontuacao</span>
                            <strong><?= Html::encode(number_format($stat->pontuacao, 0)) ?></strong>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-stats">
                <p>Nenhuma estatistica disponivel ainda.</p>
                <p>Comece a jogar para ver suas estatisticas aqui!</p>
            </div>
        <?php endif; ?>
    </div>
</div>