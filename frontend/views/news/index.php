<?php
/** @var yii\web\View $this */
/** @var array $noticias */

$this->title = 'Notícias';
?>

<section class="news" id="news-page">
    <h2 class="section-title">Todas as Notícias</h2>
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
