<?php

use common\models\Tournament;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\TournamentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tournaments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tournament', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_torneio',
            'nome',
            'best_of',
            'regras:ntext',
            'limite_inscricoes',
            //'premios',
            //'data_inicio',
            //'data_fim',
            //'estado',
            //'organizador_id',
            //'aprovado_por',
            //'id_jogo',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Tournament $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_torneio' => $model->id_torneio]);
                 }
            ],
        ],
    ]); ?>


</div>
