<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */

$this->title = $model->id_torneio;
$this->params['breadcrumbs'][] = ['label' => 'Tournaments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tournament-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_torneio' => $model->id_torneio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_torneio' => $model->id_torneio], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_torneio',
            'nome',
            'best_of',
            'regras:ntext',
            'limite_inscricoes',
            'premios',
            'data_inicio',
            'data_fim',
            'estado',
            'organizador_id',
            'aprovado_por',
            'id_jogo',
        ],
    ]) ?>

</div>
