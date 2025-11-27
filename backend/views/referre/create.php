<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Referre $model */

$this->title = 'Create Referre';
$this->params['breadcrumbs'][] = ['label' => 'Referres', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="referre-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
