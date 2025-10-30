<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        // Delete all
        $auth->removeAll();

        // Create role and premissions
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador - gere users';
        $auth->add($admin);


        $auth->assign($admin);

        echo "RBAC Admin criado com sucesso!\n";
    }
}