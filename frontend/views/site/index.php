<?php

/** @var yii\web\View $this */
/** @var array $noticias */
use yii\helpers\Html;

$this->title = 'VORTEX';
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
    <section class="rankings" id="rankings">
        <h2 class="section-title">Rankings</h2>
        <div class="ranking-table">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jogador/Equipa</th>
                        <th>Jogo</th>
                        <th>Vitórias</th>
                        <th>Derrotas</th>
                        <th>Pontos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="rank">1</span></td>
                        <td>
                            <div class="player-info">
                                <div class="player-avatar">VX</div>
                                <span>Vortex Elite</span>
                            </div>
                        </td>
                        <td>Counter-Strike 2</td>
                        <td>24</td>
                        <td>3</td>
                        <td style="color: var(--success); font-weight: bold;">1,850</td>
                    </tr>
                    <tr>
                        <td><span class="rank">2</span></td>
                        <td>
                            <div class="player-info">
                                <div class="player-avatar">TP</div>
                                <span>Team Phoenix</span>
                            </div>
                        </td>
                        <td>Counter-Strike 2</td>
                        <td>22</td>
                        <td>5</td>
                        <td style="color: var(--success); font-weight: bold;">1,720</td>
                    </tr>
                    <tr>
                        <td><span class="rank">3</span></td>
                        <td>
                            <div class="player-info">
                                <div class="player-avatar">NV</div>
                                <span>Nova Gaming</span>
                            </div>
                        </td>
                        <td>League of Legends</td>
                        <td>20</td>
                        <td>6</td>
                        <td style="color: var(--success); font-weight: bold;">1,650</td>
                    </tr>
                    <tr>
                        <td><span class="rank">4</span></td>
                        <td>
                            <div class="player-info">
                                <div class="player-avatar">DW</div>
                                <span>Dark Warriors</span>
                            </div>
                        </td>
                        <td>Valorant</td>
                        <td>19</td>
                        <td>7</td>
                        <td style="color: var(--success); font-weight: bold;">1,590</td>
                    </tr>
                    <tr>
                        <td><span class="rank">5</span></td>
                        <td>
                            <div class="player-info">
                                <div class="player-avatar">SX</div>
                                <span>Storm X</span>
                            </div>
                        </td>
                        <td>Counter-Strike 2</td>
                        <td>18</td>
                        <td>8</td>
                        <td style="color: var(--success); font-weight: bold;">1,520</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

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

