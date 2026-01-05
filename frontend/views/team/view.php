<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Images;

/** @var common\models\Equipa $equipa */
/** @var bool $isUserTeam */
/** @var bool $isCaptain */

$this->title = 'Editar Equipa';
$initials = strtoupper(substr($equipa->nome, 0, 2));

?>
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <?php if ($isUserTeam): ?>
                <div style="background-color: #007bff; color: white; padding: 10px; border-radius: 5px; font-weight: bold; text-align: center; margin-bottom: 20px;">Esta é a sua equipa</div>
            <?php endif; ?>
            <div class="team-profile-img">
                <div class="profile-avatar">
                    <?php if ($equipa->profileImage): ?>
                        <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($equipa->profileImage->path) ?>.<?= Html::encode($equipa->profileImage->extension) ?>"
                            alt="<?= Html::encode($equipa->nome) ?>"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <?= $initials ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Nome da equipa -->
            <div class="card shadow-sm mb-4" style="background-color: #252541; border: none;">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 fw-bold mb-2 text-white"><?= $equipa->nome ?></h1>
                </div>
            </div>

            <!-- Informações da equipa -->
            <div class="card shadow-sm mb-4" style="background-color: #252541; border: none;">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Detalhes -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-info-circle-fill me-2"></i>Nome do Capitão
                            </h3>
                            <p class="text-white-50">
                                <?= $capitao ?? 'Sem capitão' ?>
                            </p>



                        </div>

                        <!-- Membros da equipa -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-file-text-fill me-2"></i>Membros da Equipa
                            </h3>
                            <?php if (!empty($equipa->membrosEquipas)): ?>
                                <?php foreach ($equipa->membrosEquipas as $membro): ?>
                                    <p class="text-white-50">
                                        <i class="bi bi-person-fill me-2"></i>
                                        <?= Html::encode($membro->user->username) ?>
                                        <?php if ($membro->funcao === 'capitao'): ?>
                                            <span class="badge bg-primary ms-2">Capitão</span>
                                        <?php endif; ?>
                                    </p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-white-50">Nenhum membro na equipa.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
            <?php if ($isCaptain): ?>
                <div class="d-flex justify-content-center gap-4 mb-4">
                    <?= Html::a('Editar Equipa', ['update', 'id' => $equipa->id], [
                        'class' => 'btn btn-team text-nowrap'
                    ]) ?>
                    <?= Html::a('Adicionar Membros', ['add-members', 'id' => $equipa->id], [
                        'class' => 'btn btn-team text-nowrap'
                    ]) ?>
                    <?= Html::a('Remover Membros', ['delete-members', 'id' => $equipa->id], [
                        'class' => 'btn btn-outline-danger text-nowrap'
                    ]) ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>