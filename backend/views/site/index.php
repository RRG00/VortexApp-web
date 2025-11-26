<?php

use hail812\adminlte\widgets\Card;

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [['label' => $this->title]];

// Dados reais da BD (quando tiveres)
// $totalUsers = User::find()->count();
// $admins = User::find()->where(['role' => 'admin'])->count();
// $organizers = User::find()->where(['role' => 'organizer'])->count();
// $players = User::find()->where(['role' => 'player'])->count();


?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
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
                            Evolução de Registos (Últimos 12 Meses)
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="registrosChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
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





    <!-- Ações rápidas -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-bolt"></i> Ações Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="<?= \yii\helpers\Url::to(['/user/create']) ?>" class="btn btn-lg btn-primary w-100">
                                <i class="fas fa-user-plus"></i><br>
                                <strong>Criar Utilizador</strong>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="<?= \yii\helpers\Url::to(['/user/index']) ?>" class="btn btn-lg btn-info w-100">
                                <i class="fas fa-list"></i><br>
                                <strong>Listar Utilizadores</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>