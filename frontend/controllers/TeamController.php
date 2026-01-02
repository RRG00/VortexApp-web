<?php

namespace frontend\controllers;

use common\models\Equipa;
use common\models\EquipaSearch;
use common\models\MembrosEquipa;
use common\models\UpdateTeamForm;
use common\models\User;
use frontend\models\Convite;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\filters\AccessControl;


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
                        'attach-member' => ['POST'],
~~~~                        'remove-member' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['?', '@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['add-members', 'attach-member', 'delete-members', 'remove-member'],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['view'],
                            'roles' => ['?', '@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update', 'delete'],
                            'roles' => ['@'],
                        ]

                    ]
                ]
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

        $userTeam = null;
        if (!Yii::$app->user->isGuest) {
            $membro = MembrosEquipa::findOne(['id_utilizador' => Yii::$app->user->id]);
            if ($membro) {
                $userTeam = $membro->equipa;
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userTeam' => $userTeam,
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
        $equipa = $this->findModel($id);
        $membros = $equipa->membrosEquipas;
        $capitao = null;
        foreach ($membros as $membro) {
            if ($membro->funcao === 'capitao') {
                $capitao = $membro->user->username;
                break;
            }
        }

        $isUserTeam = false;
        $isCaptain = false;
        if (!Yii::$app->user->isGuest) {
            $membro = MembrosEquipa::findOne(['id_equipa' => $id, 'id_utilizador' => Yii::$app->user->id]);
            $isUserTeam = $membro !== null;
            $isCaptain = $membro && $membro->funcao === 'capitao';
        }

        return $this->render('view', [
            'equipa' => $equipa,
            'capitao' => $capitao,
            'isUserTeam' => $isUserTeam,
            'isCaptain' => $isCaptain,
        ]);
    }

    public function actionAddMembers($id)
{
    $equipa = $this->findModel($id);

    $searchTerm = Yii::$app->request->get('q');

    $query = User::find()
        ->alias('u')
        ->innerJoin('auth_assignment aa', 'aa.user_id = u.id')
        ->andWhere(['aa.item_name' => 'player'])
        ->andWhere(['u.status' => User::STATUS_ACTIVE])
        ->andWhere([
            'not exists',
            MembrosEquipa::find()
                ->select(new Expression('1'))
                ->where('membros_equipa.id_utilizador = u.id'),
        ])
        ->andFilterWhere(['like', 'u.username', $searchTerm]);

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination' => ['pageSize' => 10],
        'sort' => ['defaultOrder' => ['username' => SORT_ASC]],
    ]);

    return $this->render('add-members', [
        'equipa' => $equipa,
        'dataProvider' => $dataProvider,
        'searchTerm' => $searchTerm,
    ]);
}
    


    public function actionAttachMember($id, $userId)
    {
        $equipa = $this->findModel($id);

        $currentUserId = Yii::$app->user->id;
        $currentMembership = MembrosEquipa::findOne(['id_equipa' => $id, 'id_utilizador' => $currentUserId]);

        if (!$currentMembership || $currentMembership->funcao !== 'capitao') {
            Yii::$app->session->setFlash('error', 'Apenas o capitão desta equipa pode adicionar membros.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $existingMembership = MembrosEquipa::findOne(['id_utilizador' => $userId]);
        if ($existingMembership) {
            Yii::$app->session->setFlash('error', 'Este utilizador já pertence a uma equipa.');
            return $this->redirect(['add-members', 'id' => $id]);
        }

        // Create an invitation record in the convite table
        $inviter = User::findOne($currentUserId);
        $inviterUsername = $inviter ? $inviter->username : null;

        $convite = new Convite();
        $convite->id_utilizador = $userId;
        $convite->convite = json_encode([
            'team_id' => $id,
            'inviter_id' => $currentUserId,
            'inviter_username' => $inviterUsername,
        ]);
        $convite->data_envio = date('Y-m-d H:i:s');

        if ($convite->save()) {
            Yii::$app->session->setFlash('success', 'Convite enviado ao utilizador.');

            // Publish MQTT message to notify the user in real-time (requires mosquitto with websockets for browser clients)
            try {
                $mqttFile = Yii::getAlias('@app') . '/../../mosquitto/phpMQTT.php';
                if (file_exists($mqttFile)) {
                    require_once($mqttFile);

                    $server = '127.0.0.1'; // broker host
                    $port = 1883; // broker port (use 1883 for server publishing)
                    $clientId = 'vortex-server-' . uniqid();

                    $mqtt = new \phpMQTT($server, $port, $clientId);
                    if ($mqtt->connect(true, NULL, null, null)) {
                        $topic = "invites/{$userId}";
                        $payload = json_encode([
                            'type' => 'team_invite',
                            'team_id' => $id,
                            'inviter_id' => $currentUserId,
                            'inviter_username' => $inviterUsername,
                            'convite_id' => $convite->id_notificacao,
                        ]);
                        $mqtt->publish($topic, $payload, 0);
                        $mqtt->close();
                    }
                }
            } catch (\Throwable $e) {
                // don't break user flow on mqtt error; optionally log
                Yii::error('MQTT publish error: ' . $e->getMessage(), __METHOD__);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível enviar o convite.');
        }

        return $this->redirect(['add-members', 'id' => $id]);
    }

    /**
     * Creates a new Equipa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $userId = Yii::$app->user->id;
        $membro = MembrosEquipa::findOne(['id_utilizador' => $userId]);

        if ($membro !== null) {
            $equipa = Equipa::findOne($membro->id_equipa);

            if ($equipa !== null) {

                Yii::$app->session->setFlash('error', 'Já pertence a uma equipa e não pode criar outra.');
                return $this->redirect(['view', 'id' => $equipa->id]);
            } else {
                $membro->delete();
            }
        }

        $equipaModel = new Equipa();
        $equipaModel->data_criacao = date('Y-m-d H:i:s');
        $equipaModel->id_capitao = $userId;

        if ($this->request->isPost) {
            if ($equipaModel->load($this->request->post()) && $equipaModel->save()) {

                $membroEquipa = new MembrosEquipa();
                $membroEquipa->create($equipaModel->id, $userId);

                if ($membroEquipa->save()) {
                    $auth = Yii::$app->authManager;
                    $captainRole = $auth->getRole('captian'); // nome igual ao RBAC
                    if ($captainRole && !$auth->getAssignment('captian', $userId)) {
                        $auth->assign($captainRole, $userId);
                    }
                    return $this->redirect(['view', 'id' => $equipaModel->id]);
                } else {
                    Yii::$app->session->setFlash('error', 'Não foi possível adicionar o utilizador à equipa.');
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
        $userId = Yii::$app->user->id;
        $isMember = MembrosEquipa::find()->where(['id_equipa' => $id, 'id_utilizador' => $userId])->exists();
        if (!$isMember) {
            Yii::$app->session->setFlash('error', 'Esta equipa não é a sua.');
            return $this->redirect(['view', 'id' => $id]);
        }
        if ($model->id_capitao !== $userId) {
            Yii::$app->session->setFlash('error', 'Você não é o capitão da equipa.');
            return $this->redirect(['view', 'id' => $id]);
        }

        if ($this->request->isPost && $model->load($this->request->post())) {

            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            // If there's an image file, try to upload it
            if ($model->imageFile) {
                $model->upload();
            }
            
            // Save the model
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Equipa atualizada com sucesso!');
                return $this->redirect(['view', 'id' => $id]);
            } else {
                Yii::$app->session->setFlash('error', 'Erro ao atualizar a equipa.');
            }
        }
        return $this->render('edit-team', [
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
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userId = Yii::$app->user->id;
        $isMember = MembrosEquipa::find()->where(['id_equipa' => $id, 'id_utilizador' => $userId])->exists();
        if (!$isMember) {
            Yii::$app->session->setFlash('error', 'Esta equipa não é a sua.');
            return $this->redirect(['view', 'id' => $id]);
        }
        if ($model->id_capitao !== $userId) {
            Yii::$app->session->setFlash('error', 'Você não é o capitão da equipa.');
            return $this->redirect(['view', 'id' => $id]);
        }
        
        // Delete all relations before deleting the team
        try {
            // Delete all team members
            MembrosEquipa::deleteAll(['id_equipa' => $id]);
            
            // Delete team inscriptions
            \common\models\Inscricao::deleteAll(['id_equipa' => $id]);
            
            // Delete team image if exists
            if ($model->profileImage) {
                $model->profileImage->delete();
            }
            
            // Delete the team
            $model->delete();
            
            Yii::$app->session->setFlash('success', 'Equipa apagada com sucesso.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Erro ao apagar equipa: ' . $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect(['index']);
    }

    public function actionDeleteMembers($id)
    {
        $equipa = $this->findModel($id);
        $currentUserId = Yii::$app->user->id;
        
        // Check if user is the captain
        $currentMembership = MembrosEquipa::findOne(['id_equipa' => $id, 'id_utilizador' => $currentUserId]);
        if (!$currentMembership || $currentMembership->funcao !== 'capitao') {
            Yii::$app->session->setFlash('error', 'Apenas o capitão desta equipa pode remover membros.');
            return $this->redirect(['view', 'id' => $id]);
        }

        // Get all team members except the captain
        $membros = MembrosEquipa::find()
            ->where(['id_equipa' => $id])
            ->andWhere(['<>', 'id_utilizador', $currentUserId])
            ->with('user')
            ->all();

        return $this->render('delete-members', [
            'equipa' => $equipa,
            'membros' => $membros,
        ]);
    }

    public function actionRemoveMember($id, $userId)
    {
        $equipa = $this->findModel($id);
        $currentUserId = Yii::$app->user->id;
        
        // Check if user is the captain
        $currentMembership = MembrosEquipa::findOne(['id_equipa' => $id, 'id_utilizador' => $currentUserId]);
        if (!$currentMembership || $currentMembership->funcao !== 'capitao') {
            Yii::$app->session->setFlash('error', 'Apenas o capitão desta equipa pode remover membros.');
            return $this->redirect(['view', 'id' => $id]);
        }

        // Cannot remove the captain
        if ($userId === $currentUserId) {
            Yii::$app->session->setFlash('error', 'Não pode remover a si próprio da equipa.');
            return $this->redirect(['delete-members', 'id' => $id]);
        }

        // Find and delete the member
        $membro = MembrosEquipa::findOne(['id_equipa' => $id, 'id_utilizador' => $userId]);
        if ($membro) {
            if ($membro->delete()) {
                Yii::$app->session->setFlash('success', 'Membro removido com sucesso.');
            } else {
                Yii::$app->session->setFlash('error', 'Não foi possível remover o membro.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Membro não encontrado.');
        }

        return $this->redirect(['delete-members', 'id' => $id]);
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
