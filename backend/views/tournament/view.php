<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Tournament $model */

$this->title = $model->id_torneio;
$this->registerCssFile('@web/css/tournament-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
$this->params['breadcrumbs'][] = ['label' => 'Tournaments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tournament-view">

    <h1><?= Html::encode("View Tournament: " . $model->nome) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_torneio',
            'nome',
            'descricao',
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
            'arbitro_id',
            
        ],
    ]) ?>

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

</div>
