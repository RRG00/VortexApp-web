<?php

namespace backend\modules\api\controllers;

use yii\web\Controller;
use common\models\Tournament;

use common\models\User;
use common\models\Inscricao;
use Yii;
use yii\filters\auth\QueryParamAuth;

class TournamentController extends Controller
{

    public $modelClassTournament = 'common\models\Tournament';

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
            'tokenParam' => 'access-token',

        ];

        return $behaviors;
    }

    public function actionFindTeamTournament($id_equipa)
    {

        $inscricoes = Inscricao::find()
            ->where(['id_equipa' => $id_equipa])
            ->with('torneio')
            ->all();

        if (!$inscricoes) {
            Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'No tournaments for this team'];
        }

        $result = [];
        foreach ($inscricoes as $incsc) {
            $t = $incsc->torneio;
            if (!$t) {
                continue;
            }
            $result[] = [
                'id' => $t->id,
                'nome' => $t->nome,
                'bestof' => $t->best_of,
                'data_inicio' => $t->data_inicio,
                'data_fim' => $t->data_fim,
                'estado' => $t->estado
            ];
        }

        return $result;
    }

    public function actionCreateTournament()
    {

        $model = new Tournament();
        $request = Yii::$app->request;
        $data = $request->bodyParams;


        //Veirifcar se o organizador existe
        $organizerId = $data['organizador_id'] ?? null;
        $organizer = $organizerId ? User::findOne($organizerId) : null;


        if (!$organizer) {
            Yii::$app->response->statuscode = 400;
            return ['status' => 'error', 'message' => 'Organizer doesnt exists'];
        }
        //vERIFICA O REFEREE
        $refereeId = $data['arbitro_id'] ?? null;
        $referee   = $refereeId ? User::findOne($refereeId) : null;

        if (!$referee) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Referee doesnt exists'];
        }

        $model->load($data, '');
        $model->organizador_id = $organizer->id;
        $model->arbitro_id = $referee->id;

        if ($model->validate() && $model->save()) {
            Yii::$app->response->statuscode = 201;
            return [
                'status' => 'sucess',
                'message' => 'Tournament Created',
                'id' => $model->id,
            ];
        }

        Yii::$app->response->statuscode = 400;
        return [
            'status' => 'error',
            'message' => 'Tournament Validation error ',
            'erros' => $model->errors,
        ];
    }
    public function actionAllTournaments()
    {
        $tournaments = Tournament::find()->all();

        if (!$tournaments) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'No tournaments found'];
        }

        $result = [];
        foreach ($tournaments as $t) {
            $result[] = [
                'id'          => $t->id,
                'nome'        => $t->nome,
                'best_of'     => $t->best_of,
                'data_inicio' => $t->data_inicio,
                'data_fim'    => $t->data_fim,
                'estado'      => $t->estado,
            ];
        }

        return $result;
    }
    public function actionRegisterTeam()
    {
        $data = Yii::$app->request->bodyParams;
        $idTorneio = $data['id_torneio'] ?? null;
        $idEquipa  = $data['id_equipa']  ?? null;

        if (!$idTorneio || !$idEquipa) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'id_torneio e id_equipa são obrigatórios'];
        }

        $torneio = Tournament::findOne($idTorneio);
        $equipa  = \common\models\Equipa::findOne($idEquipa);

        if (!$torneio || !$equipa) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'Torneio ou equipa não encontrado'];
        }


        $existe = Inscricao::find()
            ->where(['id_torneio' => $idTorneio, 'id_equipa' => $idEquipa])
            ->exists();
        if ($existe) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Equipa já inscrita neste torneio'];
        }

        $insc = new Inscricao();
        $insc->id_torneio = $idTorneio;
        $insc->id_equipa  = $idEquipa;

        if ($insc->save()) {
            Yii::$app->response->statusCode = 201;
            return ['status' => 'success', 'message' => 'Equipa inscrita com sucesso'];
        }

        Yii::$app->response->statusCode = 400;
        return ['status' => 'error', 'message' => 'Falha ao inscrever equipa', 'errors' => $insc->errors];
    }



    public function actionUpdateTournament($id)
    {
        $data = Yii::$app->request->bodyParams;

        $model = Tournament::findOne($id);

        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['status' => 'error', 'message' => 'Tornament not found'];
        }

        $data = Yii::$app->request->bodyParams;

        //verificar organizador
        if (isset($data['organizador_id'])) {
            $organizer = User::findOne($data['organizador_id']);
            if (!$organizer) {
                Yii::$app->response->statusCode = 400;
                return ['status' => 'error', 'message' => 'Organizer does not exist'];
            }

            //Verificar Arbrito
            if (isset($data['arbitro_id'])) {
                $referee = User::findOne($data['arbitro_id']);
                if (!$referee) {
                    Yii::$app->response->statusCode = 400;
                    return ['status' => 'error', 'message' => 'Referee does not exist'];
                }
            }
        }


        $model->load($data, '');

        if ($model->validate() && $model->save()) {
            return ['status' => 'sucess', 'message' => 'Tournament updated'];
        }

        Yii::$app->response->statuscode = 400;
        return [
            'status' => 'error',
            'message' => 'Tournament validation failed',
            'errors' =>  $model->errors,
        ];
    }

    public function actionDeleteTournament($id)
    {

        $model = Tournament::findOne($id);

        if (!$model) {
            Yii::$app->response->statuscode = 404;
            return ['status' => 'error', 'message' => 'Tournament not found'];
        }

        if ($model->delete()) {
            return ['status' => 'success', 'message' => 'Tournament deleted'];
        }

        Yii::$app->response->statusCode = 400;
        return [
            'status'  => 'error',
            'message' => 'Failed to delete tournament',
        ];
    }
}
