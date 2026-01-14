<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use common\models\Images;
use common\models\User;
use Yii;
use yii\filters\auth\QueryParamAuth;


class UserController extends Controller
{
    public $modelClassUser = 'common\models\User';
    public $modelClassImage = 'common\models\Images';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'access-token',
        ];

        return $behaviors;
    }

    //READ
    public function actionFindUser($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            \Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'No users found'];
        }

        $image = Images::find()
            ->where(['id_user' => $user->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $photoUrl = null;
        if ($image) {
            $baseUrl  = Yii::$app->request->hostInfo;
            $photoUrl = $baseUrl . '/VortexApp-web/frontend/web/uploads/'
                . $image->path . '.' . $image->extension;
        }

        return [
            'status' => 'success',
            'user' => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'photo'    => $photoUrl,
            ],
        ];
    }

    public function actionGetArbitros()
    {
        // OPTION A: If you use Yii2 RBAC (auth_assignment table)
        $sql = "SELECT u.id, u.username 
            FROM user u 
            JOIN auth_assignment a ON u.id = a.user_id 
            WHERE a.item_name = 'arbitro'"; // Make sure the role name is exactly 'arbitro' or 'referee' in your DB

        $arbitros = \Yii::$app->db->createCommand($sql)->queryAll();

        // OPTION B: If you have a 'role' column in your 'user' table
        // $arbitros = \common\models\User::find()
        //     ->select(['id', 'username'])
        //     ->where(['role' => 'arbitro'])
        //     ->asArray()
        //     ->all();

        return $arbitros;
    }

    //PUT 
    public function actionUpdateUser($id)
    {

        $user = User::findOne($id);
        if (!$user) {
            Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $body = Yii::$app->request->bodyParams;

        if (isset($body['username'])) {
            $user->username = $body['username'];
        }

        if (isset($body['email'])) {
            $user->email = $body['email'];
        }

        if ($user->save()) {
            return ['status' => 'success', 'message' => 'User updated successfully'];
        } else {
            Yii::$app->response->statuscode = 400;
            return ['status' => 'error', 'message' => 'Failed to update user', 'errors' => $user->errors];
        }
    }

    //CREATE
    public function actionCreate()
    {
        $user    = new User();
        $request = Yii::$app->request;
        $data    = $request->bodyParams;

        $user->username = $data['username'] ?? null;
        $user->email    = $data['email'] ?? null;

        if (empty($data['password'])) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Password is required'];
        }

        $user->setPassword($data['password']);
        $user->generateAuthKey();

        if ($user->validate() && $user->save()) {

            $auth = Yii::$app->authManager;
            $role = $auth->getRole('player');
            if ($role && !$auth->getAssignment('player', $user->id)) {
                $auth->assign($role, $user->id);
            }

            Yii::$app->response->statusCode = 201;
            return [
                'status'  => 'success',
                'message' => 'User created successfully',
                'user_id' => $user->id,
            ];
        }

        Yii::$app->response->statusCode = 400;
        return [
            'status'  => 'error',
            'message' => 'Validation failed',
            'errors'  => $user->errors,
        ];
    }


    //DELETE
    public function actionDelete($id)
    {

        $user = User::findOne($id);
        if (!$user) {
            Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $user->status = 9;

        if ($user->save(false)) {
            return ['status' => 'sucess', 'message' => 'User deleted'];
        }

        Yii::$app->response->statusCode = 400;
        return ['status' => 'error', 'message' => 'Failed to delete user', 'errors' => $user->errors];
    }
}
