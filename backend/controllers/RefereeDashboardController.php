<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\Tournament;
use common\models\TournamentSearch;

/**
 * RefereeDashboard controller
 */
class RefereeDashboardController extends Controller{

    public function actionIndex()
    {
        $searchModel = new TournamentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        if (!Yii::$app->user->isGuest) {
            $dataProvider->query->andWhere([
                'arbitro_id' => Yii::$app->user->id,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}

?>