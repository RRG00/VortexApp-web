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
    <a href="<?= Url::to(['/site/index']) ?>" class="btn btn-primary" style="margin-bottom: 10px;"><- Voltar ao Inicio</a>

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <?= $initials ?>
                </div>
                <div class="profile-info">
                    <h1><?= Html::encode($user->username) ?></h1>
                    <p><strong>Email:</strong> <?= Html::encode($user->email) ?></p>
                    <p><strong>Membro desde:</strong> <?= Yii::$app->formatter->asDate($user->created_at, 'long') ?></p>
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