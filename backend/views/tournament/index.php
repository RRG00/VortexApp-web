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

// Importa o CSS externo
$this->registerCssFile('@web/css/tournament-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
?>

<div class="tournament-index">
    <div class="tournament-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Tournament', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

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
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Tournament $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id_torneio' => $model->id_torneio]);
                 }
            ],
        ],
    ]); ?>
</div>