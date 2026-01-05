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
                if ($torneio->estado == 'Em breve') {
                    echo 'pending';
                } elseif ($torneio->estado == 'Em andamento') {
                    echo 'active';
                } elseif ($torneio->estado == 'Concluido') {
                    echo 'completed';
                } elseif ($torneio->estado == 'Cancelado') {
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
                <?= Html::a('Ver Detalhes',Url::to(['/tournament/view','id'=> $torneio -> id]),['class' => ['btn btn-primary']])?>
                <!-- index.php?r=tournament/view&id=1 -->
            </div>
        </div>
        <?php } ?>
    </div>
</section>
