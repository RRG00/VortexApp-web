<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\User;
use common\models\UpdateUserForm;
use common\models\Estatisticas;
use yii\web\UploadedFile;
use common\models\MembrosEquipa;
use app\models\Convite;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'edit-profile', 'delete-account', 'accept-invite', 'decline-invite'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Only authenticated users
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete-account' => ['post'],
                    'accept-invite' => ['post'],
                    'decline-invite' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;

        // Get user statistics
        $estatisticas = Estatisticas::find()
            ->where(['id_utilizador' => $user->id])
            ->joinWith(['jogo'])
            ->all();

        // Calculate total wins and losses
        $totalVitorias = 0;
        $totalDerrotas = 0;

        foreach ($estatisticas as $stat) {
            $totalVitorias += $stat->vitorias;
            $totalDerrotas += $stat->derrotas;
        }

        $totalJogos = $totalVitorias + $totalDerrotas;
        $winRate = $totalJogos > 0 ? round(($totalVitorias / $totalJogos) * 100, 1) : 0;

        // Get user's team
        $userTeam = null;
        $membro = MembrosEquipa::findOne(['id_utilizador' => $user->id]);
        if ($membro) {
            $userTeam = $membro->equipa;
        }

        // Get pending invitations
        $invitations = Convite::find()
            ->where(['id_utilizador' => $user->id])
            ->all();

        return $this->render('index', [
            'user' => $user,
            'estatisticas' => $estatisticas,
            'totalVitorias' => $totalVitorias,
            'totalDerrotas' => $totalDerrotas,
            'totalJogos' => $totalJogos,
            'winRate' => $winRate,
            'userTeam' => $userTeam,
            'invitations' => $invitations,
        ]);
    }

    /**
     * View another user's profile
     * @param integer $id User ID
     * @return string
     * @throws NotFoundHttpException if user not found
     */
    public function actionView($id)
    {
        $user = $this->findModel($id);

        // Get user statistics
        $estatisticas = Estatisticas::find()
            ->where(['id_utilizador' => $user->id])
            ->joinWith(['jogo'])
            ->all();

        // Calculate total wins and losses
        $totalVitorias = 0;
        $totalDerrotas = 0;

        foreach ($estatisticas as $stat) {
            $totalVitorias += $stat->vitorias;
            $totalDerrotas += $stat->derrotas;
        }

        $totalJogos = $totalVitorias + $totalDerrotas;
        $winRate = $totalJogos > 0 ? round(($totalVitorias / $totalJogos) * 100, 1) : 0;

        return $this->render('view', [
            'user' => $user,
            'estatisticas' => $estatisticas,
            'totalVitorias' => $totalVitorias,
            'totalDerrotas' => $totalDerrotas,
            'totalJogos' => $totalJogos,
            'winRate' => $winRate,
        ]);
    }

    public function actionEditProfile()
    {
        $user = Yii::$app->user->identity;
        $model = new UpdateUserForm();
        $model->user = $user;

        // Initialize form with current user data
        $model->username = $user->username;
        $model->email = $user->email;

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Perfil atualizado com sucesso!');
                return $this->redirect(['index']);
            }
        }

        return $this->render('edit-profile', ['model' => $model]);
    }

    /**
     * Soft delete user account
     * @return \yii\web\Response
     */
    public function actionDeleteAccount()
    {
        $userId = Yii::$app->user->id;

        if (User::softDeleteById($userId)) {
            // Logout the user
            Yii::$app->user->logout();

            Yii::$app->session->setFlash('success', 'Sua conta foi desativada com sucesso.');
            return $this->goHome();
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível desativar sua conta. Por favor, tente novamente.');
            return $this->redirect(['edit-profile']);
        }
    }

    public function actionAcceptInvite($id)
    {
        $userId = Yii::$app->user->id;
        $convite = Convite::findOne(['id_notificacao' => $id]);

        if (!$convite || $convite->id_utilizador != $userId) {
            Yii::$app->session->setFlash('error', 'Convite inválido.');
            return $this->redirect(['index']);
        }

        $data = json_decode($convite->convite, true);
        $teamId = $data['team_id'] ?? null;

        if (!$teamId) {
            Yii::$app->session->setFlash('error', 'Dados do convite inválidos.');
            return $this->redirect(['index']);
        }

        // Check if user is already member
        $existing = MembrosEquipa::findOne(['id_utilizador' => $userId]);
        if ($existing) {
            // remove convite as user already has a team
            $convite->delete();
            Yii::$app->session->setFlash('error', 'Já pertence a uma equipa.');
            return $this->redirect(['index']);
        }

        $member = new MembrosEquipa();
        $member->id_equipa = $teamId;
        $member->id_utilizador = $userId;
        $member->funcao = 'membro';

        if ($member->save()) {
            // remove convite after successful acceptance
            $convite->delete();
            Yii::$app->session->setFlash('success', 'Aceitou o convite e foi adicionado à equipa.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível adicionar à equipa.');
        }

        return $this->redirect(['index']);
    }

    public function actionDeclineInvite($id)
    {
        $userId = Yii::$app->user->id;
        $convite = Convite::findOne(['id_notificacao' => $id]);

        if (!$convite || $convite->id_utilizador != $userId) {
            Yii::$app->session->setFlash('error', 'Convite inválido.');
            return $this->redirect(['index']);
        }

        if ($convite->delete()) {
            Yii::$app->session->setFlash('success', 'Convite recusado.');
        } else {
            Yii::$app->session->setFlash('error', 'Não foi possível recusar o convite.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('O perfil solicitado não foi encontrado.');
    }
}
