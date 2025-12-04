<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use common\models\Tournament;
use common\models\TournamentSearch;
use yii\web\NotFoundHttpException;

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

    public function actionUpdate($id_torneio)
    {
        $model = $this->findModel($id_torneio);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id_torneio)
    {
        if (($model = Tournament::findOne(['id_torneio' => $id_torneio])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}

?>