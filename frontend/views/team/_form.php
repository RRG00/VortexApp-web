<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Equipa $model */
/** @var yii\widgets\ActiveForm $form */

$status = [
    common\Models\User::STATUS_ACTIVE => 'Ativo',
    common\Models\User::STATUS_INACTIVE => 'Removido',
];
?>

<div class="equipa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
