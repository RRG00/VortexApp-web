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

        // Create role Admin and premissions
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador - gere users';
        $auth->add($admin);

        //Create Role Organizer
        $organizer = $auth->createRole('organizer');
        $organizer->description = 'Organizador - gere users';
        $auth->add($organizer);

        $auth->assign($admin,1);
        $auth->assign($organizer,2);


        echo "RBAC Admin criado com sucesso!\n";
        echo "RBAC Organizador criado com sucesso!\n";


    }
}

