<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css"
        href="<?= Yii::$app->request->baseUrl ?>/css/styleTemplate.css" />
    <link rel="stylesheet"
        href="<?= Yii::$app->request->baseUrl ?>https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/x-icon"
        href="<?= Yii::$app->request->baseUrl ?>/assets/img/VortexAPP_Logo_SemTexto.png">

</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <nav>
            <div class="logo">
                <a href="<?= Url::to(['/site/index']) ?>">
                    <img src="<?= Yii::$app->request->baseUrl ?>/assets/img/VortexApp_Logo-NoBackground.png"
                        alt="Vortex Logo"
                        id="logo">

                </a>
            </div>
            <ul class="nav-links">
                <li><?= Html::a('Início', ['/site/index']) ?></li>
                <li><?= Html::a('Torneios', ['/tournament/index']) ?></li>
                <li><?= Html::a('Rankings', ['/ranking/index']) ?></li>
                <li><?= Html::a('Equipa', ['/team/index']) ?></li>
                <li><?= Html::a('Notícias', ['/news/index']) ?></li>
                <li><a href="#calendar">Calendário</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('Login', ['/site/login'], ['class' => ['btn btn-secondary']]) ?>
                    <?= Html::a('Registar', ['/site/signup'], ['class' => ['btn btn-primary']]) ?>



                <?php else: ?>
                    <?php
                    $user = Yii::$app->user->identity;
                    $username = $user->username ?? 'User';
                    $initials = strtoupper(substr($username, 0, 2));

                    // TODO: quando criares o modelo de convites, trocar por algo assim:
                    // use common\models\TeamInvite;
                    // $hasInvites = TeamInvite::find()
                    //     ->where(['id_utilizador' => $user->id, 'estado' => 'pendente'])
                    //     ->exists();

                    $hasInvites = true; // por agora, sem convites
                    ?>
                    <a href="<?= Url::to(['/profile/index']) ?>" class="player-avatar-link" title="<?= Html::encode($username) ?>">
                        <div class="player-avatar">
                            <?php if ($user->profileImage): ?>
                                <img src="<?= Yii::$app->request->baseUrl ?>/uploads/<?= Html::encode($user->profileImage->path) ?>"
                                    alt="<?= Html::encode($username) ?>"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="avatar-initials"><?= $initials ?></span>
                            <?php endif; ?>

                            <?php if ($hasInvites): ?>
                                <span class="avatar-notify-dot"></span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <?= Html::a('Logout', ['/site/logout'], [
                        'class' => ['btn btn-secondary'],
                        'data' => ['method' => 'post']
                    ]) ?>
                <?php endif; ?>

            </div>
        </nav>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>


    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Sobre o Vortex</h3>
                <p style="color: var(--text-secondary); line-height: 1.6;">
                    Plataforma completa para gestão e organização de competições de eSports profissionais e amadores.
                </p>
            </div>
            <div class="footer-section">
                <h3>Links Rápidos</h3>
                <ul class="footer-links">
                    <li><?= Html::a('Torneios', ['/tournament/index']) ?></li>
                    <li><?= Html::a('Rankings', ['/ranking/index']) ?></li>
                    <li><?= Html::a('Notícias', ['/news/index']) ?></li>
                    <li><a href="#calendar">Calendário</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Suporte</h3>
                <ul class="footer-links">
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Termos de Serviço</a></li>
                    <li><a href="#">Política de Privacidade</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Comunidade</h3>
                <ul class="footer-links">
                    <li><a href="https://discord.com/">Discord</a></li>
                    <li><a href="https://www.x.com/">X</a></li>
                    <li><a href="https://www.instagram.com/">Instagram</a></li>
                    <li><a href="https://tinyurl.com/yzu6vz88">YouTube</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Vortex eSports Platform. Todos os direitos reservados.</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
