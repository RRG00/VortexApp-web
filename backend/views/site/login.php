<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sessão';
//$this->params['breadcrumbs'][] = $this->title;



?>


<div class="site-login">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>Preencha os campos abaixo para iniciar sessão:</p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Esqueceu-se da sua password: <?= Html::a('Recuperar', ['site/request-password-reset']) ?>.
                    <br>
                </div>
                <br>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-lg-7">
                <a>
                    <img src="<?= Yii::$app->request->baseUrl ?>/assets/img/VortexApp_Logo-NoBackground.png"
                        alt="Vortex Logo"
                        id="login-logo"
                        class="login-logo">
                </a>
            </div>
        </div>
    </div>
</div>