<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */

$this->title = 'Update Tournament: ' . $model->id_torneio;
$this->registerCssFile('@web/css/tournament-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
$this->params['breadcrumbs'][] = ['label' => 'Tournaments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_torneio, 'url' => ['view', 'id_torneio' => $model->id_torneio]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tournament-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>
