<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
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
                'only' => ['index', 'edit-profile'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Only authenticated users
                    ],
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
        
        // Add virtual attributes for password fields
        $model->user->current_password = '';
        $model->user->new_password = '';
        $model->user->confirm_password = '';
        
        if ($model->load(Yii::$app->request->post())) {
            // Validate current password if trying to change
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            if (!empty($model->user->new_password)) {
                if (empty($model->user->current_password) || !$model->validatePassword($model->user->current_password)) {
                    $model->addError('current_password', 'Senha atual incorreta.');
                    return $this->render('edit-profile', ['model' => $model]);
                }
                
                if ($model->user->new_password !== $model->user->confirm_password) {
                    $model->addError('confirm_password', 'As senhas não coincidem.');
                    return $this->render('edit-profile', ['model' => $model]);
                }
                
                $model->user->setPassword($model->user->new_password);
            }
            
            if ($model->update()) {
                Yii::$app->session->setFlash('success', 'Perfil atualizado com sucesso!');
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('edit-profile', ['model' => $model]);
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