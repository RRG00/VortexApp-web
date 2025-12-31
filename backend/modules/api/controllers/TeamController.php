<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use common\models\Equipa;
use common\models\User;
use Yii;

class TeamController extends Controller
{
    public $modelClass = Equipa::class;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }


    //READ 
    public function actionFindTeam($id)
    {
        $team = $this->modelClass::findOne($id);
        if (!$team) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'No teams found'];
        }

        return [
            'id'           => $team->id,
            'nome'         => $team->nome,
            'id_capitao'   => $team->id_capitao,
            'data_criacao' => $team->data_criacao,
            'username'     => $team->capitaoUsername ? $team->capitaoUsername->username : null,
        ];
    }
    //CREATE
    public function actionCreate()
    {
        $model   = new $this->modelClass;
        $request = Yii::$app->request;

        $model->load($request->post(), '');
        $userId = $request->post('id_user');

        
        $user = User::findOne($userId);   

        if (!$user) {
            Yii::$app->response->statusCode = 400;
            return [
                'status'  => 'error',
                'message' => 'User not exists', 
            ];
        }

        // 3) user existe → passa a capitão desta equipa
        $model->id_capitao = $user->id;

        if ($model->validate() && $model->save()) {
            return [
                'status'  => 'success',
                'message' => 'Team created successfully',
                'team_id' => $model->id,
            ];
        }

        Yii::$app->response->statusCode = 400;
        return [
            'status'  => 'error',
            'message' => 'Validation failed',
            'errors'  => $model->errors,
        ];
    }



    //Update
    public function actionUpdate($id)
    {

        $model = $this->modelClass::findOne($id);

        if (!$model) {
            Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'Team not found'];
        }

        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->validate() && $model->save()) {
            return ['status' => 'success', 'message' => 'Team updated successfully'];
        } else {
            Yii::$app->response->statuscode = 400;
            return ['status' => 'error', 'message' => 'Validation failed', 'errors' => $model->errors];
        }
    }

    //DELETE
    public function actionDelete($id)
    {

        $model = $this->modelClass::findOne($id);

        if (!$model) {
            Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'Team not found'];
        }

        if ($model->delete()) {
            return ['status' => 'success', 'message' => 'Team deleted successfully'];
        } else {
            Yii::$app->response->statuscode = 400;
            return ['status' => 'error', 'message' => 'Failed to delete team'];
        }
    }
}
