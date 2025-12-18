<?php

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];

?>
<div class="container-fluid ">
    <!-- Header -->
    <div class="row mb-3 " style="color:black">
        <div class="col-12">
            <h2>Bem-vindo, <?= Yii::$app->user->identity->username ?>!</h2>
            <p class="text-muted">Painel de Administração - Gestão de Utilizadores</p>
        </div>  
    </div>
    <!-- Estatísticas principais -->
    <div class="row justify-content-center">
        <!-- Administradores -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $admins,
                'text' => 'Administradores',
                'icon' => 'fas fa-user-shield',
                'theme' => 'danger',
                'linkUrl' => ['/user/index'],
                'linkText' => 'Ver admins'
            ]) ?>
        </div>

        <!-- Organizadores -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $organizers,
                'text' => 'Organizadores',
                'icon' => 'fas fa-user-tie',
                'theme' => 'warning',
                'linkUrl' => ['/user/index'],
                'linkText' => 'Ver organizadores'
            ]) ?>
        </div>

        <!-- Jogadores -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $players,
                'text' => 'Jogadores',
                'icon' => 'fas fa-gamepad',
                'theme' => 'success',
                'linkUrl' => ['/user/index'],
                'linkText' => 'Ver jogadores'
            ]) ?>
        </div>
        <!-- Árbitros -->
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => $referees,
                'text' => 'Árbitros',
                'icon' => 'fa fa-user',
                'theme' => 'success',
                'linkUrl' => ['/user/index'],
                'linkText' => 'Ver jogadores'
            ]) ?>

    </div>
    </div>

    <!--Grafico de Registo de Users -->
    <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title" style="color:black">
                            Registos (Últimos 12 Meses)
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="registrosChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                    </div>
                </div>
    </div>

    <!-- Gestão de Estado dos Torneios -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="color:black">
                    Gestão de Estado dos Torneios
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Estado Atual</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tournaments as $tournament): ?>
                                <tr>
                                    <td><?= $tournament->id ?></td>
                                    <td><?= \yii\helpers\Html::encode($tournament->nome) ?></td>
                                    <td>
                                        <span class="badge badge-info"><?= \yii\helpers\Html::encode($tournament->estado) ?></span>
                                    </td>
                                    <td>
                                        <?= \yii\helpers\Html::a('Pendente',
                                            ['tournament/update-estado', 'id' => $tournament->id, 'estado' => 'Pendente'],
                                            ['class' => 'btn btn-sm btn-warning', 'data-method' => 'post']) ?>
                                        <?= \yii\helpers\Html::a('Em Curso',
                                            ['tournament/update-estado', 'id' => $tournament->id, 'estado' => 'Em Curso'],
                                            ['class' => 'btn btn-sm btn-primary', 'data-method' => 'post']) ?>
                                        <?= \yii\helpers\Html::a('Concluído',
                                            ['tournament/update-estado', 'id' => $tournament->id, 'estado' => 'Concluído'],
                                            ['class' => 'btn btn-sm btn-success', 'data-method' => 'post']) ?>
                                        <?= \yii\helpers\Html::a('Cancelado',
                                            ['tournament/update-estado', 'id' => $tournament->id, 'estado' => 'Cancelado'],
                                            ['class' => 'btn btn-sm btn-danger', 'data-method' => 'post']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   <?php
            $this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);

            $monthsJson = json_encode($months);
            $dataJson = json_encode($registrations);

            $this->registerJs("
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('registrosChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: $monthsJson,
                        datasets: [{
                            label: 'Novos Registos',
                            data: $dataJson,
                            borderColor: 'rgba(60, 141, 188, 1)',
                            backgroundColor: 'rgba(60, 141, 188, 0.2)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            });
            ", \yii\web\View::POS_END);
        ?>
</div>