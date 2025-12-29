<?php

namespace backend\controllers;
use common\models\Tournament;
use common\models\TournamentSearch;
use common\models\User;
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
                    'class' => AccessControl::class,
                        'rules' => [
                            [ // Rules => Organizer
                                'allow' => true,
                                'actions' => ['index'],
                                'roles' => ['viewTournament'],
                            ],
                            [ 
                                'allow' => true,
                                'actions' => ['create'],
                                'roles' => ['createTournament'],
                            ],
                            [ 
                                'allow' => true,
                                'actions' => ['update'],
                                'roles' => ['updateTournament'],
                            ],
                            [
                                'allow' => true,
                                'actions' => ['delete'],
                                'roles' => ['deleteTournament'],
                            ],
                            [
                                'allow' => true,
                                'actions' => ['update-estado'],
                                'roles' => ['updateTournament'],
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
     * @param int $id Id Torneio
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tournament model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $auth = Yii::$app->authManager;
        $refereesIds = $auth->getUserIdsByRole('referee'); 
        $referees = User::find()->where(['id' => $refereesIds])->all();

        $model = new Tournament();
        $model->organizador_id = Yii::$app->user->id;
        $model->aprovado_por = Yii::$app->user->id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model'    => $model,
            'referees' => $referees,
        ]);
    }

    /**
     * Updates an existing Tournament model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id Id Torneio
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
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

    /**
     * Deletes an existing Tournament model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_torneio Id Torneio
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Updates the estado of all tournaments based on their dates.
     * - 15 days before start: "Em breve"
     * - Between start and end date: "Em andamento"
     * - After end date: "Concluído"
     * @return \yii\web\Response
     */
    public function actionUpdateEstado()
    {
        $tournaments = Tournament::find()->all();
        $updatedCount = 0;
        $today = new \DateTime();

        foreach ($tournaments as $tournament) {
            $dataInicio = new \DateTime($tournament->data_inicio);
            $dataFim = new \DateTime($tournament->data_fim);
            $interval = $today->diff($dataInicio);
            $daysUntilStart = (int)$interval->format('%r%a');

            $newEstado = null;

            if ($today > $dataFim) {
                $newEstado = 'Concluído';
            } elseif ($today >= $dataInicio && $today <= $dataFim) {
                $newEstado = 'Em andamento';
            } elseif ($daysUntilStart <= 15 && $daysUntilStart >= 0) {
                $newEstado = 'Em breve';
            }

            if ($newEstado !== null && $tournament->estado !== $newEstado) {
                $tournament->estado = $newEstado;
                if ($tournament->save(false)) {
                    $updatedCount++;
                }
            }
        }

        if ($updatedCount > 0) {
            Yii::$app->session->setFlash('success', "Estados de $updatedCount torneio(s) atualizados com sucesso.");
        } else {
            Yii::$app->session->setFlash('info', 'Nenhum torneio necessitou atualização de estado.');
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Finds the Tournament model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id Id Torneio
     * @return Tournament the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tournament::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
