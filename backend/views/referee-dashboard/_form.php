<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\ArrayHelper;
use common\models\Jogo;



/** @var yii\web\View $this */
/** @var common\models\Tournament $model */
/** @var yii\widgets\ActiveForm $form */

// Importa o CSS externo
$this->registerCssFile('@web/css/tournament-form.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
?>

<div class="tournament-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'organizador_nome_fake')
    ->textInput([
        'value' => $model->organizador ? $model->organizador->username : '',
        'disabled' => true,
    ])
    ->label('Organizador do Torneio') ?>

    <?= $form->field($model, 'nome')->textInput([
    'maxlength' => true,
    'disabled' => true,   
    ]) ?>

    <?= $form->field($model, 'descricao')->textarea([
        'maxlength' => true,
        'disabled' => true, 
        'rows' => 6,
    ]) ?>

    <?= $form->field($model, 'requisitos')->textarea([
        'maxlength' => true,
        'disabled' => true,  
        'rows' => 6,
    ]) ?>

    <?= $form->field($model, 'data_inicio')->input(
        'date', [
        'disabled' => true,
    ]) ?>

    <?= $form->field($model, 'data_fim')
    ->input('date', [
        'disabled' => true,
    ]) ?>

     <?= $form->field($model, 'id_jogo')
    ->textInput([
        'value' => $model->jogo ? $model->jogo->nome : '',
        'disabled' => true,   
    ])
    ->label('Jogo') ?>

    <hr>

    <?= $form->field($model, 'best_of')->dropDownList(
        ['1' => 'Best of 1', '3' => 'Best of 3', '5' => 'Best of 5'],
        ['prompt' => 'Selecione o formato']
    ) ?>
    
    <?= $form->field($model, 'limite_inscricoes')->input('number') ?>

    <?= $form->field($model, 'regras')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'premios')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'estado')->dropDownList(
        ['em_breve' => 'Em breve', 'a_decorrer' => 'A decorrer', 'concluido' => 'Concluído', 'cancelado' => 'Cancelado'],
        ['prompt' => 'Selecione o estado']
    ) ?>

    <?= $form->field($model, 'aprovado_por')->hiddenInput(
        ['value' => Yii::$app->user->identity->id]
    )->label(false) ?>

    <?= $form->field($model, 'arbitro_id')->hiddenInput(
        ['value' => Yii::$app->user->identity->id]
    )->label(false)
    ?>
   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']);
        ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>