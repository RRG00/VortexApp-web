<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::home() ?>" class="brand-link">
        <img src="<?= Yii::$app->request->baseUrl ?>/assets/img/VortexApp_Logo-NoBackground.png"
            alt="Vortex Logo"
            id="logo-backend"
            class="logo-backend"
            width="52">

        <span class="brand-text font-weight-light">Vortex Painel</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <?php
                $user = Yii::$app->user->identity;
                $username = $user->username ?? 'User';
                $initials = strtoupper(substr($username, 0, 2));

                // Get user role and determine profile image
                $roleImage = null;

                if (!Yii::$app->user->isGuest) {
                    $auth = Yii::$app->authManager;
                    $roles = $auth->getRolesByUser(Yii::$app->user->id);

                    // Map roles to their respective images
                    $roleImageMap = [
                        'admin' => 'administrator.png',
                        'organizer' => 'organizer.png',
                        'referee' => 'referre.png',
                    ];

                    // Find the first matching role
                    foreach ($roleImageMap as $role => $image) {
                        if (isset($roles[$role])) {
                            $roleImage = $image;
                            break;
                        }
                    }
                }
                ?>
                <?php if ($roleImage): ?>
                    <img src="<?= Yii::$app->request->baseUrl ?>/assets/img/<?= Html::encode($roleImage) ?>"
                         alt="<?= Html::encode($username) ?>"
                         class="">
                <?php else: ?>
                    <div class="user-initials elevation-2">
                        <span><?= $initials ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="info">
                <a href="<?= Url::to(['/user/profile']) ?>" class="d-block">
                    <?= Html::encode($username) ?>
                </a>
                <?php if (!empty($user->email)): ?>
                    <small class="d-block text-muted">
                        <?= Html::encode($user->email) ?>
                    </small>
                <?php endif; ?>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => [
                    ['label' => 'User Control', 'icon' => 'users-cog text-white', 'url' => ['user/index']],
                    ['label' => 'Tournament Control', 'icon' => 'fa fa-trophy text-white', 'url' => ['tournament/index']],
                    ['label' => 'Tournament Dashboard', 'icon' => 'fa fa-tachometer-alt text-white', 'url' => ['referee-dashboard/index']],
                    ['label' => 'Yii2 PROVIDED', 'header' => true],
                    ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
                    ['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>