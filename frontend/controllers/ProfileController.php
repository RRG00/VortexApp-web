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

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'edit-profile', 'delete-account'],
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

        return $this->render('index', [
            'user' => $user,
            'estatisticas' => $estatisticas,
            'totalVitorias' => $totalVitorias,
            'totalDerrotas' => $totalDerrotas,
            'totalJogos' => $totalJogos,
            'winRate' => $winRate,
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
