<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Referre $model */

$this->title = 'Update Referre: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Referres', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="referre-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
