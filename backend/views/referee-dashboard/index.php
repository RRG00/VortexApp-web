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
<div class="col-12 mt-4">
    <div class="">
        <div class="d-flex justify-content-end align-items-center">
            <?= \yii\helpers\Html::a(
                '<i class="fas fa-sync-alt "></i> Update Estados',
                ['tournament/update-estado'],
                ['class' => 'btn btn-primary', 'data-method' => 'post']
            ) ?>
        </div>
    </div>
</div>
<div class="referee-index">
    <div class="tournament-grid">
        <?php foreach ($dataProvider->getModels() as $torneio) { ?>
            <div class="tournament-card">
                <div class="tournament-header">
                    <h2 class="game-badge"><?= $torneio->jogo->nome ?></h2>
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
                                            echo 'pending';
                                        }
                                        ?>"><?= $torneio->estado ?></span>
                </div>
                <div class="tournament-info">
                    <h3><?= $torneio->nome ?></h3>
                    <div class="tournament-details">
                        <span class="detail-item"><?= $torneio->limite_inscricoes ?> Equipas</span>
                        <span class="detail-item">Best Of: <?= $torneio->best_of ?></span>
                        <span class="detail-item">Prémio: <?= $torneio->premios ?></span>
                    </div>
                    <div class="d-flex justify-content-start align-items-center">
                        <?= Html::a('Gerir Torneio', Url::to(['/referee-dashboard/management', 'id' => $torneio->id]), ['class' => ['btn btn-primary']]) ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>