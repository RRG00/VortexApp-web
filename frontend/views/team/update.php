<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Equipa $model */

$this->title = 'Update Equipa: ' . $model->id_equipa;
$this->params['breadcrumbs'][] = ['label' => 'Equipas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_equipa, 'url' => ['view', 'id_equipa' => $model->id_equipa]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="equipa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
