<?php

namespace frontend\controllers;

use common\models\Equipa;
use common\models\EquipaSearch;
use common\models\MembrosEquipa;
use common\models\UpdateTeamForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
                    'class' => VerbFilter::class,
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
    public function actionView($id)
    {
        $membros = $this->findModel($id)->membrosEquipas;
        $index = array_search($id, array_column($membros, 'id_utilizador'));
        if($this->findModel($id)->membrosEquipas[$index]->funcao === 'capitao'){
         $capitao = $this->findModel($id)->membrosEquipas[$index]->user->username;
        }
        //$capitao = $membros

        return $this->render('view', [
            'equipa' => $this->findModel($id),
            'capitao' => $capitao,
        ]);
    }

    /**
     * Creates a new Equipa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $equipaModel = new Equipa();
        $equipaModel -> data_criacao = date('Y-m-d H:i:s');
        if ($this->request->isPost) {
            if ($equipaModel->load($this->request->post()) && $equipaModel->save()) {

                $membroEquipa = new MembrosEquipa();
                $membroEquipa->create($equipaModel->id,Yii::$app->user->id);

                if($membroEquipa->save()){
                    return $this->redirect(['view', 'id' => $equipaModel->id]);
                }

            }
        } else {
            $equipaModel->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $equipaModel,
        ]);
    }

    /**
     * Updates an existing Equipa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_equipa Id Equipa
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->imageFile = "";

        if ($this->request->isPost && $model->load($this->request->post())){

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                if($model->save()){  

                    Yii::$app->session->setFlash('success', 'Equipa atualizada com sucesso!');
                    return $this->redirect(['index']);
                }
            }

        }
        return $this->render('edit-team', [
            'model' => $model,
        ]);
    }

//    public function actionUpdateOld($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($model->load(Yii::$app->request->post())) {
//            // Validate current password if trying to change
//            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
//
//            if ($model->update()) {
//                Yii::$app->session->setFlash('success', 'Equipa atualizada com sucesso!');
//                return $this->redirect(['index']);
//            }
//        }
//
//        return $this->render('edit-team', ['model' => $model]);
//    }




    /**
     * Deletes an existing Equipa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_equipa Id Equipa
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Equipa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_equipa Id Equipa
     * @return Equipa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Equipa::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
