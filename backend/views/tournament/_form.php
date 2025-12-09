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


    <?= $form->field($model, 'descricao')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'requisitos')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'best_of')->hiddenInput(['value' => '0'])->label(false) ?>

    <?= $form->field($model, 'regras')->hiddenInput(['value' => 'Por definir'])->label(false) ?>

    <?= $form->field($model, 'requesitos')->textarea(['value' => 'Por definir'])->label('Requesitos do Torneio') ?>

    <?= $form->field($model, 'limite_inscricoes')->HiddenInput(['value' => '0'])->label(false) ?>

    <?= $form->field($model, 'premios')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_inicio')->Input('date') ?>

    <?= $form->field($model, 'data_fim')->Input('date') ?>

    <?= $form->field($model, 'estado')->hiddenInput(['value' => 'Por Definir'])->label(false) ?>

    <?= $form->field($model, 'organizador_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?= $form->field($model, 'aprovado_por')->hiddenInput(
        ['value' => Yii::$app->user->identity->id]
    )->label(false) ?>

    <?= $form->field($model, 'arbitro_id')->dropDownList(
        ArrayHelper::map(
            User::find()->where(['papel' => 'referee'])->all(),
            'id',
            'username'
        ),
        ['prompt' => 'Selecione um árbitro']
    )
    ?>

    <?= $form->field($model, 'id_jogo')->dropDownList(
        ArrayHelper::map(
            Jogo::find()->all(),
            'id_jogo',
            'nome'
        ),
        ['prompt' => 'Selecione um jogo']
    )->label('Jogo')
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']);
        ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>