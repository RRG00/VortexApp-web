<?php

/** @var yii\web\View $this */
/** @var array $noticias */



use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VORTEX';
$username = $user->username ?? 'User';
$initials = strtoupper(substr($username, 0, 2));
?>
<!--Video Section -->
<section class="vortex-video" style="margin-top: 0.5rem;" id="home">
    <video class="vortex-video-bg" autoplay muted loop playsinline>
        <source src="/VortexApp-web/frontend/web/assets/videos/index-video.mp4" type="video/mp4">
        O teu navegador não suporta vídeo HTML5.
    </video>
    <div class="vortex-video-overlay"></div>
    <div class="vortex-video-content">
        <h1>Bem-vindo ao Vortex</h1>
        <p>A plataforma definitiva para competições de eSports</p>
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
            <?= Html::a('Explorar Torneios', ['/tournament/index'], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Criar Equipa', ['/team/index'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</section>

<!-- Tournaments Section -->
<section class="tournaments" id="tournaments">
    <h2 class="section-title">Torneios Ativos</h2>
    <div class="tournament-grid">
        <?php foreach ($tournaments as $tournament): ?>
            <div class="tournament-card">
                <div class="tournament-header">
                    <span class="game-badge">
                        <?= Html::encode($tournament->jogo->nome) ?>
                    </span>
                    <span class="status active">
                        <?= Html::encode($tournament->estado) ?>
                    </span>
                </div>

                <div class="tournament-info">
                    <h3><?= Html::encode($tournament->nome) ?></h3>

                    <div class="tournament-details">
                        <span class="detail-item">
                            <?= (int)$tournament->limite_inscricoes ?> Equipas
                        </span>
                        <span class="detail-item">
                            Best of <?= Html::encode($tournament->best_of) ?>
                        </span>
                        <span class="detail-item">
                            €<?= Html::encode($tournament->premios) ?>
                        </span>
                    </div>

                    <p style="color: var(--text-secondary); margin: 1rem 0;">
                        <?= Html::encode($tournament->descricao) ?>
                    </p>

                    <?= Html::a(
                        'Ver Detalhes',
                        ['tournament/view/', 'id' => $tournament->id],
                        ['class' => 'btn btn-primary', 'style' => 'width: 100%; text-align: center;']
                    ) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>


<!-- Rankings Section -->
<?php if ($topplayer): ?>
    <section class="rankings" id="rankings">
        <h2 class="section-title">Jogador do Momento</h2>
        <div class="ranking-card">
            <div class="ranking-card-left">
                <a href="<?= Url::to(['/profile/view', 'id' => $topplayer->utilizador->id]) ?>" class="player-avatar-link" title="Ver perfil de <?= Html::encode($topplayer->utilizador->username) ?>">
                    <div class="profile-avatar">
                        <?php if (isset($topplayer->utilizador->profileImage)): ?>
                            <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($topplayer->utilizador->profileImage->path) ?>"
                                alt="<?= Html::encode($topplayer->utilizador->username) ?>"
                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        <?php else: ?>
                            <?= $initials ?>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <div class="ranking-card-right">
                <div class="ranking-header-row">
                    <div>
                        <h3><?= Html::encode($topplayer->utilizador->username ?? 'Desconhecido') ?></h3>
                        <p class="ranking-game">
                            <?= Html::encode($topplayer->jogo->nome ?? 'Sem jogo') ?>
                        </p>
                    </div>
                </div>
                <div class="ranking-bottom-row">
                    <div class="ranking-stats">
                        <div class="stat-item">
                            <span class="stat-label">KD</span>
                            <span class="stat-value">
                                <?= Html::encode(number_format($topplayer->kd, 2)) ?>
                            </span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Vitórias</span>
                            <span class="stat-value"><?= (int)$topplayer->vitorias ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Derrotas</span>
                            <span class="stat-value"><?= (int)$topplayer->derrotas ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rankings-button-row">
                <?= Html::a(
                    'Ver Rankings',
                    ['ranking/index'],
                    ['class' => 'btn btn-primary']
                ) ?>
            </div>
        </div>
    </section>
<?php endif; ?>





<!-- News Section -->
<section class="news" id="news">
    <h2 class="section-title">Últimas Notícias</h2>
    <div class="news-grid">
        <?php if (!empty($noticias)): ?>
            <?php foreach ($noticias as $n): ?>
                <div class="news-card">
                    <div class="news-image">
                        <img src="<?= htmlspecialchars($n['small_image'] ?? '') ?>"
                            alt="<?= htmlspecialchars($n['title'] ?? '') ?>">
                    </div>
                    <div class="news-content">
                        <p class="news-date">
                            <?= htmlspecialchars($n['category'] ?? '') ?>
                        </p>
                        <h3 class="news-title">
                            <?= htmlspecialchars($n['title'] ?? '') ?>
                        </h3>
                        <p class="news-excerpt">
                            <?= htmlspecialchars($n['category'] ?? '') ?>
                        </p>
                        <a href="<?= htmlspecialchars($n['details_link'] ?? '#') ?>"
                            class="btn btn-primary"
                            style="margin-top: 1rem;"
                            target="_blank"
                            rel="noopener noreferrer">
                            Ler Mais
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: var(--text-secondary);">Sem notícias disponíveis de momento.</p>
        <?php endif; ?>
    </div>
</section>