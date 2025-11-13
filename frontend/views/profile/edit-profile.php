<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\UpdateUserForm $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Editar Perfil';
$initials = strtoupper(substr($model->user->username, 0, 2));
?>

<div class="profile-container">

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            <?php if ($model->user->profileImage): ?>
                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($model->user->profileImage->path) ?>"
                    alt="<?= Html::encode($model->user->username) ?>"
                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            <?php else: ?>
                <?= $initials ?>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h1>Editar Perfil</h1>
            <p>Atualize suas informações pessoais</p>
            <a href="<?= Url::to(['/profile/index']) ?>" class="btn btn-secondary" style="margin-bottom: 10px;">
                Voltar ao Perfil
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="edit-profile-section">
        <?php $form = ActiveForm::begin([
            'id' => 'edit-profile-form',
            'options' => ['class' => 'profile-form', 'enctype' => 'multipart/form-data'],
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
        ]); ?>

        <div class="form-section">
            <h3>Informações Básicas</h3>

            <div class="form-row">
                <?= $form->field($model->user, 'username')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Digite seu nome de usuário',
                    'class' => 'form-control'
                ])->label('Nome de Usuário') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model->user, 'email')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Digite seu email',
                    'type' => 'email',
                    'class' => 'form-control'
                ])->label('Email') ?>
            </div>
            <div class="form-row">
                <?= $form->field($model, 'imageFile')->fileInput([
                    'class' => 'form-control'
                ])->label('Imagem de Perfil')
                ?>
            </div>
        </div>

        <div class="form-section">
            <h3>Alterar Senha</h3>
            <p class="help-text">Deixe em branco se não quiser alterar a senha</p>

            <div class="form-row">
                <?= $form->field($model->user, 'current_password')->passwordInput([
                    'placeholder' => 'Digite sua senha atual',
                    'class' => 'form-control'
                ])->label('Senha Atual') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model->user, 'new_password')->passwordInput([
                    'placeholder' => 'Digite a nova senha',
                    'class' => 'form-control'
                ])->label('Nova Senha') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model->user, 'confirm_password')->passwordInput([
                    'placeholder' => 'Confirme a nova senha',
                    'class' => 'form-control'
                ])->label('Confirmar Nova Senha') ?>
            </div>
        </div>

        <div class="form-actions">
            <?= Html::submitButton('Salvar Alterações', [
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
        <h3>Ações da Conta</h3>
        <div class="danger-zone">
            <p><strong>Zona de Perigo</strong></p>
            <p>Uma vez que você excluir sua conta, não há como voltar atrás.</p>
            <?= Html::a('Excluir Conta', ['/user/delete'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

</div>

<style>
    .profile-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
        margin-bottom: 30px;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        border: 3px solid white;
    }

    .profile-info h1 {
        margin: 0 0 10px 0;
        font-size: 28px;
    }

    .profile-info p {
        margin: 5px 0;
        opacity: 0.9;
    }

    .edit-profile-section {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-section:last-of-type {
        border-bottom: none;
    }

    .form-section h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #2d3748;
        font-size: 20px;
    }

    .help-text {
        color: #718096;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .form-row {
        margin-bottom: 20px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #4a5568;
        font-weight: 500;
        font-size: 14px;
    }

    .help-block {
        color: #e53e3e;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #cbd5e0;
    }

    .account-actions {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .account-actions h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #2d3748;
    }

    .danger-zone {
        padding: 20px;
        background: #fff5f5;
        border: 1px solid #feb2b2;
        border-radius: 8px;
    }

    .danger-zone p {
        margin: 5px 0;
        color: #742a2a;
    }

    .danger-zone strong {
        color: #c53030;
    }

    .btn-danger {
        background: #e53e3e;
        color: white;
        margin-top: 15px;
    }

    .btn-danger:hover {
        background: #c53030;
    }

    .has-error .form-control {
        border-color: #e53e3e;
    }

    .has-error label {
        color: #e53e3e;
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>