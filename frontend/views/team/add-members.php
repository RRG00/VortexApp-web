<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Equipa $equipa */
/** @var string|null $searchTerm */

$this->title = 'Adicionar Membros - ' . $equipa->nome;
?>

<div class="container my-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-white-50 mb-0">Utilizadores disponíveis sem equipa.</p>
        </div>
        <?= Html::a('Voltar à equipa', ['view', 'id' => $equipa->id], ['class' => 'btn btn-outline-light']) ?>
    </div>
    <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
        <div class="card-body">
            <form class="row g-2" method="get"
                action="/VortexApp-web/frontend/web/index.php?r=team/add-members&id=<?= $equipa->id ?>">
            </form>
        </div>
    </div>
    <?php if ($dataProvider->getTotalCount() === 0): ?>
        <div class="alert alert-info">Não existem utilizadores sem equipa que correspondam à pesquisa.</div>
    <?php else: ?>
        <div class="card shadow-sm" style="background-color: #1a1a2e; border: none;">
            <div class="list-group list-group-flush">
                <?php foreach ($dataProvider->getModels() as $user): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; color: #fff; border-color: rgba(255,255,255,0.1);">
                        <div>
                            <div class="fw-bold"><?= Html::encode($user->username) ?></div>
                            <div class="text-white-50 small"><?= Html::encode($user->email) ?></div>
                        </div>
                        <?= Html::a('Convidar', ['attach-member', 'id' => $equipa->id, 'userId' => $user->id], [
                            'class' => 'btn btn-primary',
                            'data-method' => 'post',
                            'data-confirm' => 'Convidar este utilizador para a equipa?',
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card-body">
                <?= LinkPager::widget(['pagination' => $dataProvider->pagination]); ?>
            </div>
        </div>
    <?php endif; ?>
</div>