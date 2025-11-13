<?php

use common\Models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\Models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Utilizadores';
$this->params['breadcrumbs'][] = $this->title;

// Importa o CSS externo
$this->registerCssFile('@web/css/user-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
?>

<div class="user-index-wrapper">
    <div class="user-card">
        <div class="user-card-header">
            <?= Html::encode($this->title) ?>
            <div class="float-end">
                <?= Html::a('Criar Utilizador', ['create'], ['class' => 'btn btn-primary-custom']) ?>
            </div>
        </div>

        <div class="user-card-body">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover align-middle mb-0 user-table'],
                    'columns' => [
                            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],

                            'id',
                            'username',
                            'email:email',
                            'status',
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Ações',
                                    'urlCreator' => function ($action, User $model, $key, $index, $column) {
                                        return Url::toRoute([$action, 'id' => $model->id]);
                                    },
                                    'template' => '{view} {update} {delete}',
                                    'contentOptions' => ['class' => 'text-center'],
                            ],
                    ],
            ]); ?>
        </div>
    </div>
</div>
