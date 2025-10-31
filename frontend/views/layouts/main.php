<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
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
    <link rel="stylesheet" type="text/css" href="../web/css/styleTemplate.css" />
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>

    <nav>
            <div class="logo">
                <img src="../web/assets/img/VortexApp_Logo-NoBackground.png" alt="Vortex Logo" id="logo">
            </div>
            <ul class="nav-links">
                <?= Html::a('Início',['/site/index'])?>
                <?= Html::a('Torneios',['/tournament/index'])?>
                <li><a href="#rankings">Rankings</a></li>
                <li><a href="#news">Notícias</a></li>
                <li><a href="#calendar">Calendário</a></li>
            </ul>
            <div class="auth-buttons">
                <?= Html::a('Login',['login'],['class' => ['btn btn-secondary']])?>
                <?= Html::a('Registar',['/site/signup'],['class' => ['btn btn-primary']])?>
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
                    <li><a href="#tournaments">Torneios</a></li>
                    <li><a href="#rankings">Rankings</a></li>
                    <li><a href="#news">Notícias</a></li>
                    <li><a href="#calendar">Calendário</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Suporte</h3>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Termos de Serviço</a></li>
                    <li><a href="#">Política de Privacidade</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Comunidade</h3>
                <ul class="footer-links">
                    <li><a href="#">Discord</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">YouTube</a></li>
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
