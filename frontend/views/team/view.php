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
                    <h1 class="display-4 fw-bold mb-2 text-white"><?=$equipa->nome?></h1>
                </div>
            </div>

            <!-- Logo da equipa -->
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 fw-bold mb-2 text-white"><?=$equipa->nome?></h1>
                </div>
            </div>

            <!-- Informações do Torneio -->
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Detalhes -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-info-circle-fill me-2"></i>Nome do Capitão
                            </h3>
                            <p class="text-white-50"><?=$equipa->capitao->username?></p>
                        </div>

                        <!-- Regras -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-file-text-fill me-2"></i>Membros da Equipa
                            </h3>
                            <?php foreach ($equipa->utilizadors as $utilizador) { ?>
                            <p class="text-white-50"><?=$utilizador->username?></p>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>