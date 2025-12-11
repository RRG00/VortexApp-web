<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;

class UserController extends Controller
{
    public $modelClassUser = 'common\models\User';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionNames()
    {
        $usersmodel = new $this->modelClassUser;
        $recs = $usersmodel::find()->select(['username'])->all();
        return $recs;
    }


}
