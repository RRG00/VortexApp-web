<?php

namespace frontend\controllers;

use common\models\Equipa;
use common\models\EquipaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TeamController implements the CRUD actions for Equipa model.
 */
class TeamController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Equipa models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EquipaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Equipa model.
     * @param int $id_equipa Id Equipa
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_equipa)
    {
        return $this->render('view', [
            'equipa' => $this->findModel($id_equipa),
        ]);
    }

    /**
     * Creates a new Equipa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Equipa();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_equipa' => $model->id_equipa]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Equipa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_equipa Id Equipa
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_equipa)
    {
        $model = $this->findModel($id_equipa);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_equipa' => $model->id_equipa]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Equipa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_equipa Id Equipa
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_equipa)
    {
        $this->findModel($id_equipa)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Equipa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_equipa Id Equipa
     * @return Equipa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_equipa)
    {
        if (($model = Equipa::findOne(['id_equipa' => $id_equipa])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
