<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var User\Models\User $model */

$this->title = 'Update User: ' . $model->id;
$this->registerCssFile('@web/css/user-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
