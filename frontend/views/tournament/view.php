<?php

use yii\helpers\Html;
use yii\helpers\Url;


$formatter = \Yii::$app->formatter;

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header do Torneio -->
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 fw-bold mb-2 text-white"><?= $torneio->nome ?></h1>
                    <span class="badge bg-primary fs-6 px-3 py-2"><?= $torneio->jogo->nome ?></span>
                </div>
            </div>

            <!-- Informações do Torneio -->
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Detalhes -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-info-circle-fill me-2"></i>Detalhes do Torneio
                            </h3>
                            <p class="text-white-50"><?= $torneio->descricao ?></p>
                        </div>
                        <!-- Regras -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-file-text-fill me-2"></i>Regras
                            </h3>
                            <p class="text-white-50"><?= $torneio->regras ?></p>
                        </div>
                        <!-- Equipas Inscritas -->
                        <?php if (!empty($inscricoes)): ?>
                            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                                <div class="card-body">
                                    <h3 class="h5 text-primary mb-3">
                                        <i class="bi bi-people-fill me-2"></i>Equipas inscritas
                                    </h3>
                                    <ul class="list-group list-group-flush"
                                        style="text-align: center;">
                                        <?php foreach ($inscricoes as $ins): ?>
                                            <li class="list-group-item"
                                                style="background-color:#252542; border: none; color: #fff; text-align: center;">
                                                <?= Html::a(
                                                    Html::encode($ins->equipa->nome),
                                                    ['team/view', 'id' => $ins->equipa->id],
                                                    ['class' => 'team-link']
                                                ) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                </div>
                            </div>
                        <?php else: ?>
                            <p class="text-white-50">Ainda não há equipas inscritas.</p>
                        <?php endif; ?>
                        <!-- Grid de Informações Rápidas -->
                        <div class="row g-4">
                            <!-- Best Of -->
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-primary mb-1">Best Of</h5>
                                    <p class="fs-4 fw-bold mb-0 text-white"><?= $torneio->best_of ?></p>
                                </div>
                            </div>
                            <!-- Prémios -->
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-success mb-1">Prémios</h5>
                                    <p class="fs-4 fw-bold mb-0 text-white"><?= $torneio->premios ?>€</p>
                                </div>
                            </div>
                            <!-- Período -->
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-info mb-1">Período</h5>
                                    <p class="small mb-0 fw-semibold text-white-50">
                                        Data Início: <?= $formatter->asDate($torneio->data_inicio, "long") ?><br>
                                        Data Fim: <?= $formatter->asDate($torneio->data_fim, "long") ?>
                                    </p>
                                </div>
                            </div>
                            <!-- Inscrições -->
                            <div class="col-md-3">
                                <div class="text-center p-3 rounded" style="background-color: #252542;">
                                    <h5 class="text-warning mb-1">Inscrições</h5>
                                    <p class="fs-4 fw-bold mb-0 text-white">
                                        <?= $inscritos ?> / <?= $torneio->limite_inscricoes ?>
                                    </p>
                                    <p class="small mb-0 text-white-50">
                                        Vagas disponíveis: <?= $vagas ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Botão de Inscrição -->
        <div class="d-grid">
            <?= Html::a(
                '<i class="bi bi-trophy-fill me-2"></i>Inscrever Equipa',
                ['tournament/inscricao', 'id' => $torneio->id],
                [
                    'class' => 'btn btn-primary btn-lg py-3',
                    'data-method' => 'post',
                ]
            ) ?>
        </div>
    </div>
</div>
</div>