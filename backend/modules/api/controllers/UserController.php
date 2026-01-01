<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use common\models\Images;
use common\models\User;
use Yii;


class UserController extends Controller
{
    public $modelClassUser = 'common\models\User';
    public $modelClassImage = 'common\models\Images';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
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
            $baseUrl = Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
            $photoUrl = $baseUrl . '/uploads/users/' . $image->path . '.' . $image->extension;
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
