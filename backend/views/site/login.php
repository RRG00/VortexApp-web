<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sessão';
//$this->params['breadcrumbs'][] = $this->title;



?>


<div class="site-login container">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Preencha os campos abaixo para iniciar sessão:</p>

    <div class="row">
        <div class="col-md-8">
            <div class="col-md-10">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Esqueceu-se da sua password: <?= Html::a('Recuperar', ['site/request-password-reset']) ?>.
                    <br>
                    <br>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="img-vortex">
                <img src="../web/assets/img/VortexApp_Logo-NoBackground.png">
            </div>
        </div>

    </div>
</div>

</div>


</div>
<!-- /.login-card-body -->
</div>