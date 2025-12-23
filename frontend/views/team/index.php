<?php

use common\models\Equipa;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\MembrosEquipa;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var common\models\EquipaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>
<div class="equipa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Cards das Equipas -->
    <section class="tournaments" id="teams">
        <div style="display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 2rem;">
            <?= Html::a('Criar Equipa', ['create'], ['class' => 'btn btn-primary', 'style' => 'position: absolute; left: 0;']) ?>
            <h1 class="section-title" style="margin: 0;">Equipas</h1>
        </div>
        <div class="tournament-grid">
            <?php foreach ($dataProvider->models as $equipa): ?>
                <div class="tournament-card <?= $userTeam && $userTeam->id == $equipa->id ? 'user-team' : '' ?>" style="<?= $userTeam && $userTeam->id == $equipa->id ? 'border: 3px solid #007bff; box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);' : '' ?>">
                    <?php if ($userTeam && $userTeam->id == $equipa->id): ?>
                        <div class="user-team-badge" style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 5px; font-weight: bold; position: absolute; top: 10px; right: 10px;">Sua Equipa</div>
                    <?php endif; ?>

                    <div class="tournament-info">
                        <h3><?= Html::encode($equipa->nome) ?></h3>
                        <div class="tournament-details">

                            <span class="detail-item">
                                Capitão:
                                 <?= $equipa->capitao && $equipa->capitao
                                    ? Html::encode($equipa->capitao->user->username)
                                    : 'N/A' ?>
                            </span>

                            <span class="detail-item">
                                <?= Yii::$app->formatter->asDate($equipa->data_criacao, 'short') ?>
                            </span>
                        </div>
                        <p style="color: var(--text-secondary); margin: 1rem 0;">
                            <?php if ($equipa->capitao && $equipa->capitao->user): ?>
                                Equipa liderada por <?= Html::encode($equipa->capitao->user->username) ?>
                            <?php else: ?>
                                Equipa registada no sistema
                            <?php endif; ?>
                        </p>

                        <?= Html::a('Ver Detalhes', ['view', 'id' => $equipa->id], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php echo LinkPager::widget([
                'pagination' => $dataProvider->pagination,
            ]); ?>
        </div>
    </section>


</div>