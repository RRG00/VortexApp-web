<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Equipa;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        
        $auth->removeAll();

        //acessBackend
        $accessBackend = $auth->createPermission('accessBackend');
        $accessBackend->description = 'Permissão para acessar o backend';
        $auth->add($accessBackend);

        $usersDashboard = $auth->createPermission('usersDashboard');
        $usersDashboard->description = 'Acesso ao Dashboard de Users';
        $auth->add($usersDashboard);

        //Permissões gestão de Users
        $viewUsers = $auth->createPermission('viewUsers');
        $viewUsers->description ="Ver Lista de Users";
        $auth->add($viewUsers);

        $createUsers = $auth->createPermission('createUsers');
        $createUsers -> description ="Criar User";
        $auth->add($createUsers);
        
        $updateUsers = $auth->createPermission('updateUsers');
        $updateUsers->description="Atualizar Users";
        $auth->add($updateUsers);


        $deleteUsers = $auth->createPermission('deleteUsers');
        $deleteUsers-> description="Apagar Users";
        $auth->add($deleteUsers);

        //Premissões gestão Torneios
        $viewTournament = $auth->createPermission('viewTournament');
        $viewTournament->description="Ver Torneios";
        $auth->add($viewTournament);

        $createTournament = $auth->createPermission('createTournament');
        $createTournament->description="Criar Torneios";
        $auth->add($createTournament);

        $startTournament = $auth->createPermission('startTournament');
        $startTournament->description="Comecar Torneios";
        $auth->add($startTournament);

        $updateTournament = $auth->createPermission('updateTournament');
        $updateTournament->description="Atualizar Torneios";
        $auth->add($updateTournament);

        $deleteTournament = $auth->createPermission('deleteTournament');
        $deleteTournament->description="Apagar Torneios";
        $auth->add($deleteTournament);

        $finishTournament = $auth->createPermission('finishTournament');
        $finishTournament->description="Finalizar Torneios";
        $auth->add($finishTournament);

        //Permissões para Resultados e gestão de Players
        $viewResults = $auth->createPermission('viewResults');
        $viewResults->description="Ver Resultados";
        $auth->add($viewResults);

        $updateResults = $auth->createPermission('updateResults');
        $updateResults->description="Atualizar Resultados";
        $auth->add($updateResults);

        $managePlayers = $auth->createPermission('managePlayers');
        $managePlayers->description="Gerir Players";
        $auth->add($managePlayers);
        
        $banPlayers = $auth->createPermission('banPlayers');
        $banPlayers->description="Banir Players";
        $auth->add($banPlayers);

        //Premissões frontend (captain)
        $updateTeam = $auth -> createPermission('updateTeam');
        $updateTeam->description= "Atualizar Equipa";
        $auth->add($updateTeam);

        $insTournament = $auth->createPermission('insTournament');
        $insTournament->description= "Increver equipa";
        $auth->add($insTournament);

        $deleteTeam = $auth->createPermission('deleteTeam');
        $deleteTeam->description = "Apagar equipa";
        $auth->add($deleteTeam);

        //Player see Index only
        $indexFrontend = $auth-> createPermission('indexFrontend');
        $indexFrontend->description="indexFrontend";
        $auth->add($indexFrontend);

        //Roles
        //Admin -> GereUsers
        $admin = $auth->createRole('admin');
        $admin->description="Administrador";
        $auth->add($admin);
        $auth->addChild($admin, $usersDashboard);
        $auth->addChild($admin, $accessBackend);
        $auth->addChild($admin, $viewUsers);
        $auth->addChild($admin, $createUsers);
        $auth->addChild($admin, $updateUsers);
        $auth->addChild($admin, $deleteUsers);
        $auth->addChild($admin, $viewTournament);
        $auth->addChild($admin, $createTournament);
        $auth->addChild($admin, $startTournament);
        $auth->addChild($admin, $updateTournament);
        $auth->addChild($admin, $deleteTournament);
        $auth->addChild($admin, $finishTournament);
        $auth->addChild($admin, $viewResults);
        $auth->addChild($admin, $updateResults);
        $auth->addChild($admin, $managePlayers);
        $auth->addChild($admin, $banPlayers);

        //Organizador -> Gere Torneios
        $organizer = $auth->createRole('organizer');
        $organizer->description="Organizador";
        $auth->add($organizer);
        $auth->addChild($organizer, $accessBackend);
        $auth->addChild($organizer, $viewTournament);
        $auth->addChild($organizer, $createTournament);
        $auth->addChild($organizer, $startTournament);
        $auth->addChild($organizer, $updateTournament);
        $auth->addChild($organizer, $deleteTournament);
        $auth->addChild($organizer, $finishTournament);
        $auth->addChild($organizer, $viewResults);
        $auth->addChild($organizer, $updateResults);


        //Arbitro -> Ver e Atualizar Resultados
        $referee = $auth->createRole('referee');
        $referee->description="referee";
        $auth->add($referee);
        $auth->addChild($referee, $accessBackend);
        $auth->addChild($referee, $viewResults);
        $auth->addChild($referee, $updateResults);
        $auth->addChild($referee, $updateTournament);
        $auth->addChild($referee, $startTournament);
        $auth->addChild($referee, $finishTournament);

        //captain -> create team , update etc
        $captain = $auth->createRole('captain');
        $captain->description="captain";
        $auth->add($captain);
        $auth->addChild($captain, $updateTeam);
        $auth->addChild($captain, $insTournament);
        $auth->addChild($captain, $deleteTeam);

        //Player -> index frontend only
        $player = $auth->createRole('player');
        $player->description="player";
        $auth->add($player);

        //Atribuir Roles a utilizadores
        $auth->assign($admin, 1); 

        $equipes = Equipa::find()->all();
        
        // Array to track IDs we have already processed
        $processedCaptains = [];

        foreach ($equipes as $equipa) {
            $captainId = $equipa->id_capitao;

            // Check if captain exists AND hasn't been processed yet
            if ($captainId && !in_array($captainId, $processedCaptains)) {
                $auth->assign($captain, $captainId);
                
                // Add to processed list so we don't add them again
                $processedCaptains[] = $captainId;
            }
        }

        echo "RBAC Admin criado com sucesso!\n";
        echo "RBAC Organizador criado com sucesso!\n";
        echo "RBAC Player criado com sucesso!\n";
        echo "RBAC Referre criado com sucesso!\n";



    }
}