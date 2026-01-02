<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\UpdateTeamForm $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Editar Equipa';
$initials = strtoupper(substr($model->nome, 0, 2));
?>

<div class="profile-container">

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($model->profileImage): ?>
                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($model->profileImage->path) ?>.<?= Html::encode($model->profileImage->extension) ?>"
                     alt="<?= Html::encode($model->nome) ?>"
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            <?php else: ?>
                <?= $initials ?>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1>Editar Equipa</h1>
            <p>Atualize as informações da equipa</p>
            <a href="<?= Url::to(['/team/view', 'id' => $model->id]) ?>" class="btn btn-primary" style="margin-bottom: 10px;">
                Voltar ao Perfil
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="edit-profile-section">
        <?php $form = ActiveForm::begin([
            'id' => 'edit-profile-form',
            'options' => ['class' => 'profile-form', 'enctype' => 'multipart/form-data']
        ]); ?>

        <div class="form-section">
            <h3>Informações Básicas</h3>

            <div class="form-row">
                <?= $form->field($model, 'nome')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Digite o nome da Equipa',
                    'class' => 'form-control'
                ])->label('Nome da Equipa') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model, 'imageFile')->fileInput();
                ?>
            </div>
        </div>

        <div class="form-actions">
            <?= Html::submitButton('Guardar Alterações', [
                'class' => 'btn btn-primary',
                'name' => 'save-button'
            ]) ?>
            <?= Html::a('Cancelar', ['/profile/index'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <!-- Account Actions -->
    <div class="account-actions">
        <h3>Ações da Equipa</h3>
        <div class="danger-zone">
            <p><strong>Zona de Perigo</strong></p>
            <p>Uma vez que você excluir a sua equipa, não há como voltar atrás.</p>
            <?= Html::a('Excluir Equipa', ['/equipa/delete'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Tem certeza que deseja excluir a sua equipa? Esta ação não pode ser desfeita.',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

</div>


