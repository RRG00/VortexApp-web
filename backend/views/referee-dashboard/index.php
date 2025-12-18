<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->registerCssFile(Yii::getAlias('@web/css/referre-index.css'), [
    'depends' => [\yii\bootstrap4\BootstrapAsset::class]
]);

$this->title = 'Referee Dashboard';
?>
<div class="referee-index"> 
    <div class="tournament-grid">
        <?php foreach ($dataProvider->getModels() as $torneio) {?>
        <div class="tournament-card">
            <div class="tournament-header">
                <h2 class="game-badge"><?=$torneio->jogo->nome?></h2>
                <span class="status <?php
                if ($torneio->estado == 'Em breve') {
                    echo 'pending';
                } elseif ($torneio->estado == 'A decorrer') {
                    echo 'active';
                } elseif ($torneio->estado == 'Concluido') {
                    echo 'completed';
                } elseif ($torneio->estado == 'Cancelado') {
                    echo 'cancelled';
                } else {
                    echo 'pending';
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
                <?= Html::a('Gerir Torneio',Url::to(['/referee-dashboard/management','id'=> $torneio -> id]),['class' => ['btn btn-primary']])?>
                <!-- index.php?r=tournament/view&id=1 -->
            </div>
        </div>
        <?php } ?>
    </div>
</div>  