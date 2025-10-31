<?php
/** @var yii\web\View $this */
/** @var \common\models\Tournament $torneios */
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>
<section class="tournaments" id="tournaments">
    <h2 class="section-title">Torneios Ativos</h2>
    <div class="tournament-grid">
        <?php foreach ($torneios as $torneio) {?>
        <div class="tournament-card">
            <div class="tournament-header">
                <span class="game-badge"></span>
                <span class="status active">Ativo</span>
            </div>
            <div class="tournament-info">
                <h3><?=$torneio->nome?></h3>
                <div class="tournament-details">
                    <span class="detail-item">16 Equipas</span>
                    <span class="detail-item">Eliminatória</span>
                    <span class="detail-item">€5,000</span>
                </div>
                <p style="color: var(--text-secondary); margin: 1rem 0;">
                    Torneio principal da temporada. As melhores equipas competem pelo título.
                </p>
                <?= Html::a('Ver Detalhes',Url::to(['/tournament/view','id'=> $torneio -> id_torneio]),['class' => ['btn btn-primary']])?>
                <!-- index.php?r=tournament/view&id=1 -->
            </div>
        </div>
        <?php } ?>
    </div>
</section>
