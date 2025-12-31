<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use common\models\Equipa;
use Yii;

class TeamController extends Controller
{
    public $modelClass = Equipa::class;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

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

    public function actionCreate(){
        $model = new $this->modelClass;

        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            $model->save();
            return ['status' => 'success', 'message' => 'Team created successfully', 'team_id' => $model->id];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Validation failed', 'errors' => $model->errors];
        }
    }
}
