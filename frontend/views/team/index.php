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

    <section class="tournaments" id="teams">
        
        <div style="display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 2rem;">
            <?php if (!$userTeam): // Only show create button if user has no team (optional logic) ?>
                <?= Html::a('Criar Equipa', ['create'], ['class' => 'btn btn-primary', 'style' => 'position: absolute; left: 0;']) ?>
            <?php endif; ?>
            <h1 class="section-title" style="margin: 0;">Equipas</h1>
        </div>

        <?php if ($userTeam): ?>
            <div class="featured-team-container" style="margin-bottom: 3rem; display: flex; justify-content: center;">
                <div class="tournament-card user-team-featured" style="width: 100%; max-width: 800px; border: 3px solid #007bff; box-shadow: 0 0 20px rgba(0, 123, 255, 0.3); transform: scale(1.02);">
                    
                    <div class="user-team-badge" style="background-color: #007bff; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; position: absolute; top: 15px; right: 15px; font-size: 1.1em;">
                        Sua Equipa
                    </div>

                    <div class="tournament-info" style="padding: 2rem;">
                        <h2 style="font-size: 2rem; margin-bottom: 1rem; color: #007bff;"><?= Html::encode($userTeam->nome) ?></h2>
                        
                        <div class="tournament-details" style="font-size: 1.1rem; margin-bottom: 1.5rem;">
                            <span class="detail-item" style="margin-right: 20px;">
                                <i class="fa fa-user-circle"></i> 
                                Capitão: 
                                <strong>
                                    <?= ($userTeam->capitao && $userTeam->capitao->user)
                                        ? Html::encode($userTeam->capitao->user->username)
                                        : 'N/A' ?>
                                </strong>
                            </span>

                            <span class="detail-item">
                                <i class="fa fa-calendar"></i>
                                Criado em: 
                                <strong><?= Yii::$app->formatter->asDate($userTeam->data_criacao, 'long') ?></strong>
                            </span>
                        </div>

                        <p style="color: var(--text-secondary); margin-bottom: 1.5rem; font-size: 1rem;">
                            <?php if ($userTeam->capitao && $userTeam->capitao->user): ?>
                                Você faz parte da liderança de <?= Html::encode($userTeam->capitao->user->username) ?>
                            <?php else: ?>
                                Equipa registada no sistema
                            <?php endif; ?>
                        </p>

                        <div style="text-align: right;">
                            <?= Html::a('Gerir Minha Equipa', ['view', 'id' => $userTeam->id], ['class' => 'btn btn-primary btn-lg', 'style' => 'padding: 10px 30px;']) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin-bottom: 3rem;">
        <?php endif; ?>
        <div class="tournament-grid">
            <?php foreach ($dataProvider->models as $equipa): ?>
                
                <?php 
                // Optional: Skip rendering the user team in the list since it's already at the top
                if ($userTeam && $userTeam->id == $equipa->id) {
                    continue; 
                } 
                ?>

                <div class="tournament-card">
                    <div class="tournament-info">
                        <h3><?= Html::encode($equipa->nome) ?></h3>
                        <div class="tournament-details">
                            <span class="detail-item">
                                Capitão:
                                <?= ($equipa->capitao && $equipa->capitao->user)
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
            
            </div>
        
        <div style="margin-top: 2rem;">
            <?php echo LinkPager::widget([
                'pagination' => $dataProvider->pagination,
            ]); ?>
        </div>
        
    </section>
</div>