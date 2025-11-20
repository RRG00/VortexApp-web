<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Referre $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="referre-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_partida')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
