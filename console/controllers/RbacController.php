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

        // Create role Admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador - gere users';
        $auth->add($admin);

        //Create Role Organizer
        $organizer = $auth->createRole('organizer');
        $organizer->description = 'Organizador - gere users';
        $auth->add($organizer);

        //Create Role Player
        $player = $auth->createRole('player');
        $player->description = 'Player';
        $auth->add($player);

        //Create Role Equipa
        $equipa = $auth->createRole('equipa');
        $equipa->description = 'Equipa';
        $auth->add($equipa);

        //Create referee
        $referre = $auth->createRole('referre');
        $referre->description = 'Referre';
        $auth->add($referre);

        //Create Capitan
        $capitan = $auth->createRole('capitan');
        $capitan->description = 'Capitan';
        $auth->add($capitan);



        $auth->assign($admin,1);
        $auth->assign($equipa,2);
        $auth->assign($referre,3);
        $auth->assign($organizer,4);
        $auth->assign($player,5);
        $auth->assign($capitan,6);



        echo "RBAC Admin criado com sucesso!\n";
        echo "RBAC Organizador criado com sucesso!\n";
        echo "RBAC Player criado com sucesso!\n";
        echo "RBAC Equipa criado com sucesso!\n";
        echo "RBAC Referre criado com sucesso!\n";
        echo "RBAC Capitan criado com sucesso!\n";


    }
}

