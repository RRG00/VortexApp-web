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
                        <?php
                        $user = $ranking->utilizador ?? null;
                        if ($user === null) {
                            continue; 
                        }
                        $username = $user->username ?? 'User';
                        $initials = strtoupper(substr($username, 0, 2));
                        ?>
                        <tr>
                            <td><span class="rank"><?= $index + 1 ?></span></td>
                            <td>
                                <div class="player-info">
                                    <a href="<?= Url::to(['/profile/view', 'id' => $user->id]) ?>"
                                        class="player-avatar-link"
                                        title="Ver perfil de <?= Html::encode($username) ?>">
                                        <div class="player-avatar">
                                            <?php if (isset($user->profileImage)): ?>
                                                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($user->profileImage->path) ?>"
                                                    alt="<?= Html::encode($username) ?>"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <?= $initials ?>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <span><?= Html::encode($username) ?></span>
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
                        <td colspan="6" style="text-align: center;">Nenhum ranking disponível</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>