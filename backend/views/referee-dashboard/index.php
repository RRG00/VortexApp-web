<?php 
use common\models\Tournament;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->registerCssFile(Yii::getAlias('@web/css/referre-index.css'), [
    'depends' => [\yii\bootstrap4\BootstrapAsset::class]
]);

$this->title = 'Referee Dashboard';
?>
<div class="referee-index">
    <div class="referee-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="referee-card-body">
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
                    'class' => ActionColumn::class,
                    'header' => 'Ações',
                    'template' => '{update}',
                    'urlCreator' => function ($action, Tournament $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id_torneio' => $model->id_torneio]);
                    }
                ],
            ],
        ]); ?>
    </div>
</div>