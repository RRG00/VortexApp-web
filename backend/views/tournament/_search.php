<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\TournamentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tournament-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_torneio') ?>

    <?= $form->field($model, 'nome') ?>

    <?= $form->field($model, 'best_of') ?>

    <?= $form->field($model, 'regras') ?>

    <?= $form->field($model, 'limite_inscricoes') ?>

    <?php // echo $form->field($model, 'premios') ?>

    <?php // echo $form->field($model, 'data_inicio') ?>

    <?php // echo $form->field($model, 'data_fim') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'organizador_id') ?>

    <?php // echo $form->field($model, 'aprovado_por') ?>

    <?php // echo $form->field($model, 'id_jogo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
