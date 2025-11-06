<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1 style="color: #2c3e50;"><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-dark" style="background-color: #2c3e50; color: white; border: none;">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <?php if ($exception->statusCode == 403): ?>
        <div style="color: #2c3e50;">
            <p>
                <strong>⛔ Acesso Negado!</strong>
            </p>
            <p>
                Não tens permissões para aceder a esta área.
            </p>
        </div>
    <?php else: ?>
        <p style="color: #2c3e50;">
            O servidor encontrou um erro ao processar o teu pedido.
        </p>
    <?php endif; ?>

    <p>
        <?= Html::a('← Voltar ao Dashboard', ['site/index'], ['class' => 'btn btn-dark']) ?>
    </p>

</div>