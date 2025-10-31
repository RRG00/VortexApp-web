<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tournament-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'best_of')->textInput() ?>

    <?= $form->field($model, 'regras')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'limite_inscricoes')->textInput() ?>

    <?= $form->field($model, 'premios')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_inicio')->textInput() ?>

    <?= $form->field($model, 'data_fim')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organizador_id')->textInput() ?>

    <?= $form->field($model, 'aprovado_por')->textInput() ?>

    <?= $form->field($model, 'id_jogo')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
