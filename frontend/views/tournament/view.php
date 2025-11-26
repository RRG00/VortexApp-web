<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header do Torneio -->
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 fw-bold mb-2 text-white"><?=$torneio->nome?></h1>
                    <span class="badge bg-primary fs-6 px-3 py-2"><?=$torneio->jogo->nome?></span>
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
                            <p class="text-white-50"><?=$torneio->descricao?></p>
                        </div>

                        <!-- Regras -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-file-text-fill me-2"></i>Regras
                            </h3>
                            <p class="text-white-50"><?=$torneio->regras?></p>
                        </div>

                        <!-- Grid de Informações Rápidas -->
                        <div class="col-md-4">
                            <div class="text-center p-3 rounded" style="background-color: #252542;">
                                <h5 class="text-primary mb-1">Best Of</h5>
                                <p class="fs-4 fw-bold mb-0 text-white"><?=$torneio->best_of?></p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="text-center p-3 rounded" style="background-color: #252542;">
                                <h5 class="text-success mb-1">Prémios</h5>
                                <p class="fs-4 fw-bold mb-0 text-white"><?=$torneio->premios?>€</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="text-center p-3 rounded" style="background-color: #252542;">
                                <h5 class="text-info mb-1">Período</h5>
                                <p class="small mb-0 fw-semibold text-white-50">
                                    <?=date('d/m/Y', strtotime($torneio->data_inicio))?><br>
                                    <?=date('d/m/Y', strtotime($torneio->data_fim))?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botão de Inscrição -->
            <div class="d-grid">
                <?= Html::a(
                    '<i class="bi bi-trophy-fill me-2"></i>Inscrever Equipa',
                    ['tournament/inscricao', 'id' => $torneio->id_torneio],
                    [
                        'class' => 'btn btn-primary btn-lg py-3',
                        'data-method' => 'post', // Força usar POST em vez de GET
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>