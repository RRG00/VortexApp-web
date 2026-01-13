<?php

namespace backend\modules\api\controllers;

use Yii;
use common\models\LoginForm;
use yii\web\Controller;

class LoginController extends Controller
{

    public $modelClassLogin = 'common\models\LoginForm';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        Yii::$app->user->enableSession = false;

        if (!isset($behaviors['access'])) {
            $behaviors['access'] = [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [],
            ];
        }

        $behaviors['access']['rules'][] = [
            'allow' => true,
            'actions' => ['login'],
            'roles' => ['?', '@'],
        ];

        return $behaviors;
    }


    public function actionLogin()
    {
        $model = new LoginForm();
        $body = Yii::$app->request->bodyParams;
        $model->username = $body['username'] ?? null;
        $model->password = $body['password'] ?? null;

        if ($model->validate() && $model->login()) {
            $user = Yii::$app->user->identity;
            $auth  = Yii::$app->authManager;
            $roles = $auth->getRolesByUser($user->id);
            $rolename = null;
            if (!empty($roles)) {
                foreach ($roles as $role) {
                    $rolename = array_keys($roles)[0];
                }
            }

            $teamId = null;
            $membro = \common\models\MembrosEquipa::findOne(['id_utilizador' => $user->id]);
            if ($membro && $membro->equipa) {
                $teamId = $membro->equipa->id;
            }

            return [
                'success' => true,
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $rolename,
                'access_token' => $user->auth_key,
                'team_id' => $teamId,
            ];
        }




        Yii::$app->response->statusCode = 401;
        return [
            'success' => false,
            'message' => 'Invalid username or password',
        ];
    }
}
