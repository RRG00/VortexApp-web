<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;

class VortexController extends Controller
{
    public $modelClassUser = 'common\models\User';
    public $modelClassEquipa = 'common\models\Equipa';
    public $modelClassTournament = 'common\models\Tournament';
    public $modelClassJogo = 'common\models\JOGO';
    public $modelClassImages = 'common\models\Images';
    public $modelClassLoginForm = 'common\models\LoginForm';

    public function actionCount()
    {
        $usersmodel = new $this->modelClassUser;
        $recs = $usersmodel::find()->all();
        return ['count' => count($recs)];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionNomes()
    {
        $usersmodel = new $this->modelClassUser;
        $recs = $usersmodel::find()->select(['username'])->all();
        return $recs;
    }

    public function actionDelpornome($username)
    {
        $climodel = new $this->modelClassUser;
        $recs = $climodel::deleteAll(['nome' => $username]);
        return $recs;
    }

}
