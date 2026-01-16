<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Equipa;

use common\models\User;
use common\models\MembrosEquipa;
use Yii;
use yii\filters\auth\QueryParamAuth;


class TeamController extends ActiveController
{
    public $modelClass = Equipa::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //$behaviors['authenticator'] = [
        //'class'      => QueryParamAuth::class,
        // 'tokenParam' => 'access-token',
        //];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionFind($id)
    {
        $team = Equipa::findOne($id);
        if (!$team) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'Team not found'];
        }

        return [
            'status' => 'success',
            'team' => [
                'id'          => $team->id,
                'nome'        => $team->nome,
                'id_capitao'  => $team->id_capitao,
                'data_criacao' => $team->data_criacao,
            ],
        ];
    }

    public function actionByUser($id_user)
    {
        $user = User::findOne($id_user);
        if (!$user) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'User not found'];
        }

        $membro = MembrosEquipa::findOne(['id_utilizador' => $user->id]);
        if (!$membro || !$membro->equipa) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'User has no team'];
        }

        $team = $membro->equipa;

        $capitao = User::findOne($team->id_capitao);
        if (!$capitao) {
            Yii::$app->response->statusCode = 500;
            return ['status' => 'error', 'message' => 'Captain not found for team'];
        }

        $membros = MembrosEquipa::find()
            ->where(['id_equipa' => $team->id])
            ->all();

        $membersArray = [];
        foreach ($membros as $m) {
            if (!$m->user) {
                continue;
            }
            $u = $m->user;
            $membersArray[] = [
                'id'       => $u->id,
                'username' => $u->username,
                'funcao'   => $m->funcao,
            ];
        }


        return [
            'status' => 'success',
            'team' => [
                'id'          => $team->id,
                'nome'        => $team->nome,
                'id_capitao'  => $team->id_capitao,
                'data_criacao' => $team->data_criacao,
            ],
            'captain' => [
                'id'       => $capitao->id,
                'username' => $capitao->username,
            ],
            'members' => $membersArray,
        ];
    }



    //READ 
    public function actionCreate()
    {
        $data   = Yii::$app->request->bodyParams;
        $userId = $data['id_user'] ?? null;

        $user = User::findOne($userId);
        if (!$user) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'User not exists'];
        }

        $membro = MembrosEquipa::findOne(['id_utilizador' => $userId]);
        if ($membro && $membro->equipa) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'User already has a team'];
        }

        $model = new Equipa();
        $model->nome         = $data['nome'] ?? null;
        $model->data_criacao = $data['data_criacao'] ?? date('Y-m-d H:i:s');
        $model->id_capitao   = $userId;

        $tx = Yii::$app->db->beginTransaction();
        try {
            if (!$model->validate() || !$model->save()) {
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => 'Validation failed', 'errors' => $model->errors];
            }

            $membroEquipa = new MembrosEquipa();
            $membroEquipa->id_equipa     = $model->id;
            $membroEquipa->id_utilizador = $userId;
            $membroEquipa->funcao        = 'capitao';
            if (!$membroEquipa->save()) {
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => 'Failed to add captain', 'errors' => $membroEquipa->errors];
            }

            $auth = Yii::$app->authManager;
            $captainRole = $auth->getRole('captain');
            if ($captainRole && !$auth->getAssignment('captain', $userId)) {
                $auth->assign($captainRole, $userId);
            }

            $tx->commit();
            Yii::$app->response->statusCode = 201;
            return [
                'status'  => 'success',
                'message' => 'Team created successfully',
                'team_id' => $model->id,
            ];
        } catch (\Throwable $e) {
            $tx->rollBack();
            Yii::$app->response->statusCode = 500;
            return ['status' => 'error', 'message' => 'Internal server error'];
        }
    }




    //Update
    public function actionUpdate($id)
    {
        $model = Equipa::findOne($id);
        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'Team not found'];
        }

        $data = Yii::$app->request->bodyParams;


        $userId = $data['id_user'] ?? null;
        if (!$userId || !User::findOne($userId)) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Only captian can edit the Team'];
        }

        $membro = MembrosEquipa::findOne([
            'id_equipa'     => $id,
            'id_utilizador' => $userId,
        ]);

        if (!$membro || $membro->funcao !== 'capitao') {
            Yii::$app->response->statusCode = 403;
            return ['status' => 'error', 'message' => 'Only the captain can update the team'];
        }

        $model->load($data, '');

        if ($model->validate() && $model->save()) {
            return ['status' => 'success', 'message' => 'Team updated successfully'];
        }

        Yii::$app->response->statusCode = 400;
        return ['status' => 'error', 'message' => 'Validation failed', 'errors' => $model->errors];
    }

    //DELETE
    public function actionDelete($id)
    {
        $team = Equipa::findOne($id);
        if (!$team) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'Team not found'];
        }

        $tx = Yii::$app->db->beginTransaction();
        try {
            MembrosEquipa::deleteAll(['id_equipa' => $team->id]);


            if (!$team->delete()) {
                Yii::$app->response->statusCode = 400;
                $tx->rollBack();
                return ['status' => 'error', 'message' => 'Failed to delete team'];
            }

            $tx->commit();
            return ['status' => 'success', 'message' => 'Team deleted successfully'];
        } catch (\Throwable $e) {
            $tx->rollBack();
            Yii::$app->response->statusCode = 500;
            return ['status' => 'error', 'message' => 'Internal server error'];
        }
    }
}
