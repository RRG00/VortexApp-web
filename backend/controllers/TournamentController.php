<?php

namespace backend\controllers;
use common\models\Tournament;
use common\models\TournamentSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TournamentController implements the CRUD actions for Tournament model.
 */
class TournamentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                        'rules' => [
                            [ // Rules => Organizer
                                'allow' => true,
                                'actions' => ['index', 'create', 'view', 'update', 'delete'],
                                'roles' => ['organizer', 'admin'],
                            ],
                        ],
                    ],
            ]
        );
    }

    /**
     * Lists all Tournament models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TournamentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tournament model.
     * @param int $id_torneio Id Torneio
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_torneio)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_torneio),
        ]);
    }

    /**
     * Creates a new Tournament model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Tournament();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_torneio' => $model->id_torneio]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tournament model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_torneio Id Torneio
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_torneio)
    {
        $model = $this->findModel($id_torneio);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_torneio' => $model->id_torneio]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tournament model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_torneio Id Torneio
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_torneio)
    {
        $this->findModel($id_torneio)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tournament model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_torneio Id Torneio
     * @return Tournament the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_torneio)
    {
        if (($model = Tournament::findOne(['id_torneio' => $id_torneio])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
