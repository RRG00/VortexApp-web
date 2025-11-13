<?php

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Estatisticas[] $estatisticas */
/** @var int $totalVitorias */
/** @var int $totalDerrotas */
/** @var int $totalJogos */
/** @var float $winRate */

use yii\helpers\Html;

$this->title = Html::encode($user->username) . ' - Perfil';

// Generate user initials
$nameParts = explode(' ', $user->username);
$initials = strtoupper(substr($nameParts[0], 0, 1));
if (count($nameParts) > 1) {
    $initials .= strtoupper(substr($nameParts[1], 0, 1));
}
?>

<div class="profile-container">
    <a href="<?= \yii\helpers\Url::to(['/ranking/index']) ?>" class="back-button">← Voltar aos Rankings</a>
    
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($user->profileImage): ?>
                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($user->profileImage->path) ?>" 
                     alt="<?= Html::encode($user->username) ?>">
            <?php else: ?>
                <?= $initials ?>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1><?= Html::encode($user->username) ?></h1>
            <p>📧 <?= Html::encode($user->email) ?></p>
            <p>📅 Membro desde <?= Yii::$app->formatter->asDate($user->created_at, 'long') ?></p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total de Jogos</div>
            <div class="stat-value"><?= $totalJogos ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Vitórias</div>
            <div class="stat-value"><?= $totalVitorias ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Derrotas</div>
            <div class="stat-value"><?= $totalDerrotas ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Taxa de Vitória</div>
            <div class="stat-value"><?= $winRate ?>%</div>
        </div>
    </div>

    <div class="games-section">
        <h2>Estatísticas por Jogo</h2>
        <?php if (!empty($estatisticas)): ?>
            <?php foreach ($estatisticas as $stat): ?>
                <div class="game-stat">
                    <div class="game-name"><?= Html::encode($stat->jogo->nome) ?></div>
                    <div class="game-stats">
                        <div class="game-stat-item">
                            <span>Vitórias</span>
                            <strong><?= $stat->vitorias ?></strong>
                        </div>
                        <div class="game-stat-item">
                            <span>Derrotas</span>
                            <strong><?= $stat->derrotas ?></strong>
                        </div>
                        <div class="game-stat-item">
                            <span>K/D</span>
                            <strong><?= number_format($stat->kd, 2) ?></strong>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-stats">
                <p>Este jogador ainda não tem estatísticas registadas.</p>
            </div>
        <?php endif; ?>
    </div>
</div>