<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use yii\helpers\ArrayHelper;
use common\models\Jogo;

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */
/** @var yii\widgets\ActiveForm $form */

$limite_inscricoes = [
    '4' => '4',
    '8' => '8',
    '16' => '16',
];

$this->registerCssFile('@web/css/tournament-form.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
?>

<div class="tournament-page-bg">
    <div class="tournament-form-wrapper container d-flex justify-content-center align-items-center">
        <div class="card tournament-card">
            <div class="tournament-card-header">
                Gestão de Torneios
            </div>

            <div class="tournament-card-body">
                <?php $form = ActiveForm::begin([
                        'options' => ['class' => 'needs-validation'],
                        'enableClientValidation' => true,
                ]); ?>

                <div class="row g-3">
                    <div class="col-md-12">
                        <?= $form->field($model, 'nome')
                                ->textInput(['maxlength' => true, 'placeholder' => 'Nome do torneio'])
                                ->label('Nome') ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'limite_inscricoes')
                                ->dropDownList($limite_inscricoes, ['prompt' => 'Selecione o formato'])
                                ->label('Limite de Inscrições') ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'regras')
                                ->textarea(['maxlength' => true, 'placeholder' => 'Regras do torneio'])
                                ->label('Regras') ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'descricao')
                                ->textarea(['maxlength' => true, 'placeholder' => 'Descrição do torneio', 'rows' => 3])
                                ->label('Descrição') ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'requisitos')
                                ->textarea(['maxlength' => true, 'placeholder' => 'Requisitos do torneio', 'rows' => 3])
                                ->label('Requisitos') ?>
                    </div>

                    <div class="col-md-12">
                        <?= $form->field($model, 'premios')
                                ->textInput(['maxlength' => true, 'placeholder' => 'Prémios do torneio em €'])
                                ->label('Prémios') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'data_inicio')
                                ->input('date')
                                ->label('Data de Início') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'data_fim')    
                                ->input('date')
                                ->label('Data de Fim') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'arbitro_id')->dropDownList(
                            ArrayHelper::map($referees, 'id', 'username'),
                            ['prompt' => 'Selecione um árbitro']
                            )->label('Árbitro') ?>
                    </div>

                    <div class="col-md-6">
                        <?= $form->field($model, 'id_jogo')->dropDownList(
                                ArrayHelper::map(
                                    Jogo::find()->all(),
                                    'id_jogo',
                                    'nome'
                                ),
                                ['prompt' => 'Selecione um jogo']
                        )->label('Jogo') ?>
                    </div>
                </div>

                <?= $form->field($model, 'estado')->hiddenInput(['value' => 'Dar Update'])->label(false) ?>
                <?= $form->field($model, 'best_of')->hiddenInput(['value' => '1'])->label(false) ?>

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