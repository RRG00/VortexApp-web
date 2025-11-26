<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Equipa $model */

$this->title = 'Criar Equipa';
?>
<div class="equipa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
