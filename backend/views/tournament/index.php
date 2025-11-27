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
    <div class="tournament-card-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0 user-table'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'nome',
                    'label' => 'Nome',
                    'enableSorting' => false,
                ],

                [
                    'attribute' => 'best_of',
                    'label' => 'Best_of',
                    'enableSorting' => false,
                ],

                [
                    'attribute' => 'regras',
                    'format' => 'ntext',
                    'label' => 'Regras',
                    'enableSorting' => false,
                ],

                [
                    'attribute' => 'limite_inscricoes',
                    'label' => 'Limite de Inscrições',
                    'enableSorting' => false,
                ],


                [
                    'class' => ActionColumn::class,
                    'header' => 'Ações',
                    'urlCreator' => function ($action, Tournament $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_torneio' => $model->id_torneio]);
                    }
                ],
            ],
        ]);  ?>
    </div>
</div>