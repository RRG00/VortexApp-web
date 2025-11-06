<?php
/** @var yii\web\View $this */
/** @var \common\models\Tournament $torneios */
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>
<section class="tournaments" id="tournaments">
    <h1 class="section-title">Torneios Ativos</h1>
    <div class="tournament-grid">
        <?php foreach ($torneios as $torneio) {?>
        <div class="tournament-card">
            <div class="tournament-header">
                <span class="game-badge"><?=$torneio->jogo->nome?></span>
                <span class="status <?php
                if ($torneio->estado == 'em breve' || $torneio->estado == 'pending') {
                    echo 'pending';
                } elseif ($torneio->estado == 'Ativo' || $torneio->estado == 'active' || $torneio->estado == 'Em Curso') {
                    echo 'active';
                } elseif ($torneio->estado == 'concluido' || $torneio->estado == 'completed' || $torneio->estado == 'Terminado') {
                    echo 'completed';
                } elseif ($torneio->estado == 'cancelado' || $torneio->estado == 'cancelled') {
                    echo 'cancelled';
                } else {
                    echo 'pending'; // classe padrão
                }
                ?>"><?=$torneio->estado?></span>
            </div>
            <div class="tournament-info">
                <h3><?=$torneio->nome?></h3>
                <div class="tournament-details">
                    <span class="detail-item"><?=$torneio->limite_inscricoes?> Equipas</span>
                    <span class="detail-item">Best Of: <?=$torneio->best_of?></span>
                    <span class="detail-item"><?=$torneio->premios?>€</span>
                </div>
                <p style="color: var(--text-secondary); margin: 1rem 0;">
                    <?=$torneio->descricao?>
                </p>
                <?= Html::a('Ver Detalhes',Url::to(['/tournament/view','id'=> $torneio -> id_torneio]),['class' => ['btn btn-primary']])?>
                <!-- index.php?r=tournament/view&id=1 -->
            </div>
        </div>
        <?php } ?>
    </div>
</section>
