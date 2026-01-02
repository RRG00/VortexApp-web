<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Equipa $equipa */
/** @var array $membros */

$this->title = 'Remover Membros - ' . $equipa->nome;
?>

<div class="container my-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-white-50 mb-0">Selecione os membros que deseja remover da equipa.</p>
        </div>
        <?= Html::a('Voltar à equipa', ['view', 'id' => $equipa->id], ['class' => 'btn btn-outline-light']) ?>
    </div>

    <?php if (empty($membros)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Nenhum membro para remover. A equipa só tem o capitão.
        </div>
    <?php else: ?>
        <div class="card shadow-sm" style="background-color: #1a1a2e; border: none;">
            <div class="list-group list-group-flush">
                <?php foreach ($membros as $membro): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center" 
                         style="background-color: transparent; color: #fff; border-color: rgba(255,255,255,0.1);">
                        <div>
                            <div class="fw-bold">
                                <i class="bi bi-person-circle me-2"></i>
                                <?= Html::encode($membro->user->username) ?>
                            </div>
                            <div class="text-white-50 small"><?= Html::encode($membro->user->email) ?></div>
                        </div>
                        <?= Html::a(
                            'Remover',
                            ['remove-member', 'id' => $equipa->id, 'userId' => $membro->id_utilizador],
                            [
                                'class' => 'btn btn-danger btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => 'Tem a certeza que pretende remover ' . Html::encode($membro->user->username) . ' da equipa?',
                            ]
                        ) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
