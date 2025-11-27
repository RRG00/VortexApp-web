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
            <a href="<?= Url::to(['/profile/index']) ?>" class="btn btn-primary" style="margin-bottom: 10px;">
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
                <?= $form->field($model, 'username')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Digite seu nome de usuário',
                    'class' => 'form-control'
                ])->label('Nome de Usuário') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model, 'email')->textInput([
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
                <?= $form->field($model, 'current_password')->passwordInput([
                    'placeholder' => 'Digite sua senha atual',
                    'class' => 'form-control'
                ])->label('Senha Atual') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model, 'new_password')->passwordInput([
                    'placeholder' => 'Digite a nova senha',
                    'class' => 'form-control'
                ])->label('Nova Senha') ?>
            </div>

            <div class="form-row">
                <?= $form->field($model, 'confirm_password')->passwordInput([
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
            <p>Desativar sua conta irá:</p>
            <ul style="margin-left: 20px; margin-bottom: 15px;">
                <li>Desconectá-lo imediatamente</li>
                <li>Impedir o acesso à sua conta</li>
                <li>Manter seus dados armazenados com segurança</li>
            </ul>
            <p style="margin-bottom: 15px;"><em>Nota: Para reativar sua conta, entre em contato com o suporte.</em></p>
            <?= Html::a('Desativar Conta', ['/profile/delete-account'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Tem certeza que deseja desativar sua conta? Você será desconectado imediatamente e não poderá acessar o sistema.',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

</div>