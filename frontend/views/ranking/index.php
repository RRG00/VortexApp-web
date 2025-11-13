<?php

/** @var yii\web\View $this */
/** @var common\models\Estatisticas[] $rankings */

use yii\helpers\Url;

use yii\helpers\Html;

$this->title = 'Rankings';
?>

<section class="rankings" id="rankings">
    <h1 class="section-title">Rankings</h1>
    <div class="ranking-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Jogador</th>
                    <th>Jogo</th>
                    <th>Vitórias</th>
                    <th>Derrotas</th>
                    <th>K/D</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rankings)): ?>
                    <?php foreach ($rankings as $index => $ranking): ?>
                        <tr>
                            <td><span class="rank"><?= $index + 1 ?></span></td>
                            <td>
                                <div class="player-info">
                                    <a href="<?= Url::to(['/profile/view', 'id' => $ranking->utilizador->id]) ?>" class="player-avatar-link" title="Ver perfil de <?= Html::encode($ranking->utilizador->username) ?>">
                                        <div class="player-avatar">
                                            <?php if ($ranking->utilizador->profileImage): ?>
                                                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($ranking->utilizador->profileImage->path) ?>"
                                                    alt="<?= Html::encode($ranking->utilizador->username) ?>"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <?= $initials ?>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <span><?= Html::encode($ranking->utilizador->username ?? 'Unknown') ?></span>
                                </div>
                            </td>
                            <td><?= Html::encode($ranking->jogo->nome ?? 'Unknown Game') ?></td>
                            <td><?= Html::encode($ranking->vitorias) ?></td>
                            <td><?= Html::encode($ranking->derrotas) ?></td>
                            <td><?= Html::encode($ranking->kd) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Nenhum ranking disponível</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>