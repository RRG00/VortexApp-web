<?php

/** @var yii\web\View $this */
/** @var array $noticias */

$this->title = 'VORTEX';
?>
 <!-- Hero Section -->
    <section class="hero" id="home">
        <h1>Bem-vindo ao Vortex</h1>
        <p>A plataforma definitiva para competições de eSports</p>
        <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
            <a href="#tournaments" class="btn btn-primary">Explorar Torneios</a>
            <a href="#register" class="btn btn-secondary">Criar Equipa</a>
        </div>
    </section>

    <!-- Tournaments Section -->
    <section class="tournaments" id="tournaments">
        <h2 class="section-title">Torneios Ativos</h2>
        <div class="tournament-grid">
            <div class="tournament-card">
                <div class="tournament-header">
                    <span class="game-badge">Counter-Strike 2</span>
                    <span class="status cancelled">Ativo</span>
                </div>
                <div class="tournament-info">
                    <h3>Vortex Championship 2025</h3>
                    <div class="tournament-details">
                        <span class="detail-item">16 Equipas</span>
                        <span class="detail-item">Eliminatória</span>
                        <span class="detail-item">€5,000</span>
                    </div>
                    <p style="color: var(--text-secondary); margin: 1rem 0;">
                        Torneio principal da temporada. As melhores equipas competem pelo título.
                    </p>
                    <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Inscrever Equipa</a>
                </div>
            </div>

            <div class="tournament-card">
                <div class="tournament-header">
                    <span class="game-badge">League of Legends</span>
                    <span class="status active">Ativo</span>
                </div>
                <div class="tournament-info">
                    <h3>Vortex League Spring 2025</h3>
                    <div class="tournament-details">
                        <span class="detail-item">8 Equipas</span>
                        <span class="detail-item">Liga</span>
                        <span class="detail-item">€3,000</span>
                    </div>
                    <p style="color: var(--text-secondary); margin: 1rem 0;">
                        Liga de primavera com formato de pontos. Sistema de promoção e despromoção.
                    </p>
                    <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Inscrever Equipa</a>
                </div>
            </div>

            <div class="tournament-card">
                <div class="tournament-header">
                    <span class="game-badge">Valorant</span>
                    <span class="status pending">Em Breve</span>
                </div>
                <div class="tournament-info">
                    <h3>Vortex Invitational</h3>
                    <div class="tournament-details">
                        <span class="detail-item">12 Equipas</span>
                        <span class="detail-item">Eliminatória</span>
                        <span class="detail-item">€2,500</span>
                    </div>
                    <p style="color: var(--text-secondary); margin: 1rem 0;">
                        Torneio por convite para as melhores equipas da região.
                    </p>
                    <a href="#" class="btn btn-secondary" style="width: 100%; text-align: center;">Ver Detalhes</a>
                </div>
            </div>
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

