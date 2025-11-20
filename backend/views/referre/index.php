<?php

use app\models\Referre;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ReferreSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

// Importa o CSS externo
$this->registerCssFile('@web/css/referre-index.css', ['depends' => [\yii\bootstrap4\BootstrapAsset::class]]);
?>

<div class="referre-index-wrapper">
  <div class="referre-card">
    <div class="referre-card-header">
      <?= Html::encode($this->title) ?>
    </div>
    <div class="referre-card-body">
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'referre-table table table-hover align-middle mb-0'],
        'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          'id_partida',
          [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Ações',
            'urlCreator' => function ($action, $model, $key, $index, $column) {
              return Url::toRoute([$action, 'id' => $model->id]);
            },
          ],
        ],
      ]); ?>
    </div>
  </div>
</div>

