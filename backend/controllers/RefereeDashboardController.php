<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Tournament;
use common\models\TournamentSearch;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * RefereeDashboard controller
 */
class RefereeDashboardController extends Controller{

     public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [ 
                            'allow' => true,
                            'actions' => ['index', 'update'], 
                            'roles' => ['viewResults'],
                        ],
                    ],
                ],
            ]
        );
    }

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

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Tournament::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


}

?>