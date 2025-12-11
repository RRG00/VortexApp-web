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


    //GET USER
    public function actionFindUser($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            \Yii::$app->response->statusCode = 404;
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
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'photo' => $photoUrl,
        ];
    }

    //PUT USER (EDIT)
    public function actionUpdateUser($id){

        $user = User::findOne($id);
        if (!$user) {
            Yii::$app->response->statusCode = 404;
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
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Failed to update user', 'errors' => $user->errors];
        }
    }

   

}
