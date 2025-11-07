<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$auth = Yii::$app->authManager;
$roles = $auth->getRoles();

$roleList = [];
foreach ($roles as $role) {
    $roleList[$role->name] = ucfirst($role->name);
}

/** @var yii\web\View $this */
/** @var app\Models\User $model */
/** @var yii\widgets\ActiveForm $form */

// Importa o CSS externo
$this->registerCssFile('@web/css/user-form.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
?>

<div class="user-page-bg">
    <div class="user-form-wrapper container d-flex justify-content-center align-items-center">
        <div class="card user-card">
            <div class="user-card-header">
                <?= $model->isNewRecord ? 'Criar Utilizador' : 'Editar Utilizador' ?>
            </div>

            <div class="user-card-body">
                <?php $form = ActiveForm::begin([
                        'options' => ['class' => 'needs-validation'],
                        'enableClientValidation' => true,
                ]); ?>

                <div class="row g-3">
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')
                                ->textInput(['maxlength' => true, 'placeholder' => 'Nome de utilizador'])
                                ->label('Utilizador') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'email')
                                ->textInput(['maxlength' => true, 'placeholder' => 'exemplo@dominio.com'])
                                ->label('E-mail') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'password_hash')
                                ->passwordInput(['maxlength' => true, 'placeholder' => 'Senha'])
                                ->label('Palavra-passe') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'status')
                                ->dropDownList([10 => 'Ativo', 0 => 'Inativo'], ['prompt' => 'Selecione o estado'])
                                ->label('Estado') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'papel')
                                ->dropDownList($roleList, ['prompt' => 'Selecione um papel'])
                                ->label('Papel / Função') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'verification_token')
                                ->textInput(['maxlength' => true, 'placeholder' => 'Token de verificação'])
                                ->label('Token de Verificação') ?>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <?= Html::submitButton(
                            $model->isNewRecord ? 'Criar' : 'Guardar',
                            ['class' => 'btn btn-primary-custom']
                    ) ?>
                    <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-outline-light ms-2']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
