<?php

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */
/** @var common\models\Inscricao[] $inscricoes */

use yii\helpers\Html;

$this->registerCssFile(Yii::getAlias('@web/css/referre-index.css'), [
    'depends' => [\yii\bootstrap4\BootstrapAsset::class]
]);

$this->title = 'Tournament Management | Vortex';
?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2><?= Html::encode($model->nome) ?></h2>
            <p class="text-muted">Gestão do Torneio</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-warning">
                    <h3 class="card-title">Equipas Inscritas</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($inscricoes)): ?>
                        <div class="alert alert-info">
                            Nenhuma equipa inscrita neste torneio.
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($inscricoes as $inscricao): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">
                                            <?= Html::encode($inscricao->equipa->nome) ?>
                                        </h5>
                                        <small class="text-muted">ID: <?= $inscricao->equipa->id ?></small>
                                    </div>
                                    <p class="mb-1">
                                        <small>Data de Criação: <?= Yii::$app->formatter->asDate($inscricao->equipa->data_criacao, 'php:d/m/Y') ?></small>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <strong>Total de equipas: <?= count($inscricoes) ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title text-white">Brackets</h3>
                </div>
                <div class="card-body">
                    <p>Área de brackets do torneio...</p>
                </div>
            </div>
        </div>
    </div>
</div>