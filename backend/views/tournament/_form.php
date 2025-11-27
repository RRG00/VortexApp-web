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

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>
    

    <?= $form->field($model, 'best_of')->dropDownList(
        ['1' => 'Best of 1', '3' => 'Best of 3', '5' => 'Best of 5'],
        ['prompt' => 'Selecione o formato']
    ) ?>

    <?= $form->field($model, 'regras')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'limite_inscricoes')->textInput() ?>

    <?= $form->field($model, 'premios')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_inicio')-> Input('date') ?>

    <?= $form->field($model, 'data_fim')-> Input('date') ?>

    <?= $form->field($model, 'estado')->dropDownList(
        ['Em breve' => 'Em Breve', 'A decorrer' => 'A decorrer','Concluido' => 'Concluído', 'Cancelado' => 'Cancelado', ],
        ['prompt' => 'Selecione o estado'])
    ?>

    <?= $form->field($model, 'organizador_id')->hiddenInput(
        ['readonly' => true, 'value' => Yii::$app->user->id]) ->label(false)
    ?>

    <?= $form->field($model, 'aprovado_por')->textInput(
        ['readonly' => true, 'value' => Yii::$app->user->id])
    ?>

    <?= $form->field($model, 'arbitro_id')->dropDownList(
        ArrayHelper::map(
            User::find()->where(['papel' => 'referre'])->all(),
            'id',
            'username'
        ),
        ['prompt' => 'Selecione um árbitro']) 
    ?>

    <?= $form->field($model, 'id_jogo')->dropDownList(
        ArrayHelper::map(
            Jogo::find()->all(),
            'id_jogo',
            'nome'
        ),
        ['prompt' => 'Selecione um jogo'])->label('Jogo')
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
