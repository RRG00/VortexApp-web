<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header da equipa -->
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

            <!-- Informações da equipa -->
            <div class="card shadow-sm mb-4" style="background-color: #1a1a2e; border: none;">
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Detalhes -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-info-circle-fill me-2"></i>Nome do Capitão
                            </h3>
                            <p class="text-white-50">
                                <?= $capitao ? $capitao : 'Sem capitão' ?>
                            </p>



                        </div>

                        <!-- Membros da equipa -->
                        <div class="col-12">
                            <h3 class="h5 text-primary mb-3">
                                <i class="bi bi-file-text-fill me-2"></i>Membros da Equipa
                            </h3>
                            <?php foreach ($equipa->utilizadors as $membro): ?>
                                <p class="text-white-50"><?= $membro->user->username ?></p>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 col-xl-4">

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <?= Html::a('Editar Equipa', ['edit-team','id' => $equipa->id], [
                        'class' => 'btn btn-primary text-nowrap'
                    ]) ?>

                    <?= Html::a('Adicionar Membros', ['create'], [
                        'class' => 'btn btn-primary text-nowrap'
                    ]) ?>
                </div>

            </div>


        </div>
    </div>
</div>