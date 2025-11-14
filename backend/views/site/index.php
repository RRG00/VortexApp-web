<?php
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