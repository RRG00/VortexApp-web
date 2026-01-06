<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use Yii;
use common\models\User;
use common\models\Estatisticas;
use common\models\MembrosEquipa;
use frontend\models\Convite;
use common\models\Images;
use yii\filters\auth\QueryParamAuth;

class ProfileController extends Controller
{

    public $enableCsrfValidation = false; 

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'access-token',

        ];

        return $behaviors;
    }

    public function actionViewUserProfile($id_user)
    {
        $user = User::findOne($id_user);

        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'User not found'];
        }

        // Stats
        $estatistica = Estatisticas::find()
            ->where(['id_utilizador' => $user->id])
            ->one();

        $stats = null;
        if ($estatistica) {
            $stats = [
                'vitorias'  => $estatistica->vitorias,
                'derrotas'  => $estatistica->derrotas,
                'pontuacao' => $estatistica->pontuacao,
                'kd'        => $estatistica->kd,
            ];
        }

        //Photo_url
        //Photo_url
        $image = Images::find()
            ->where(['id_user' => $user->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($image) {
            $photoUrl = Yii::$app->request->hostInfo
                . '/VortexApp-web/frontend/web/uploads/'
                . $image->path . '.' . $image->extension;
        } else {
            $photoUrl = 'DONT HAVE IMG';
        }


        return [
            'status' => 'success',
            'user' => [
                'id'        => $user->id,
                'username'  => $user->username,
                'email'     => $user->email,
                'photo_url' => $photoUrl,
            ],
            'stats' => $stats,
        ];
    }

    public function actionUpdateUserProfile($id_user)
    {
        $user = User::findOne($id_user);

        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $data = Yii::$app->request->bodyParams;

        if (isset($data['username'])) {
            $user->username = $data['username'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        if ($user->save()) {
            return [
                'status' => 'success',
                'message' => 'Profile updated',
            ];
        }

        Yii::$app->response->statusCode = 400;
        return [
            'status'  => 'error',
            'message' => 'Failed to update profile',
            'errors'  => $user->errors,
        ];
    }
}
