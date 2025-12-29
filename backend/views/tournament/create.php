<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */

$this->title = 'Create Tournament';
$this->registerCssFile('@web/css/tournament-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
$this->params['breadcrumbs'][] = ['label' => 'Tournaments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-create">

    <?= $this->render('_form', [
        'model' => $model,
        'referees' => $referees,
    ]) ?>

</div>