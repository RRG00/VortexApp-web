<?php 

namespace backend\modules\api\controllers;
use Yii;
use common\models\LoginForm;
use yii\web\Controller; 

class LoginController extends Controller {

    public $modelClassLogin = 'common\models\LoginForm';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        $body = Yii::$app->request->post();
        $model->username = $body['username'] ?? null;
        $model->password = $body['password'] ?? null;

        if ($model->validate() && $model->login()) {
            $user = Yii::$app->user->identity;
            var_dump($user->id);
            $auth  = Yii::$app->authManager;
            $roles = $auth->getRolesByUser($user->id);
            $rolename = null;
            if (!empty($roles)) {
                foreach ($roles as $role) {
                    $rolename = array_keys($roles)[0];  
                }
            }
                return [
                    'success' => true,
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $rolename,
                ];
        }
        


        Yii::$app->response->statusCode = 401;
        return [
            'success' => false,
            'message' => 'Invalid username or password',
        ];
    }



}
