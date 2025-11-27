<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var User\Models\User $model */

$this->title = 'Create User';
$this->registerCssFile('@web/css/user-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>