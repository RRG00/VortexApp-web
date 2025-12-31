<?php

namespace backend\modules\api\controllers;

use yii\base\Controller;

class TeamController extends Controller
{
    public $modelClass = 'common\models\Equipa';


    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    //Get Team
    public function actionFindTeam($id)
    {
        $team = $this->modelClass::findOne($id);
        if (!$team) {
            \Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'No teams found'];
        }

        return [
            'id' => $team->id,
            'name' => $team->name,
            'description' => $team->description,
        ];
    }

    
}

