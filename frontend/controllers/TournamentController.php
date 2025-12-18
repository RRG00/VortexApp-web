<?php

namespace frontend\controllers;

use common\models\Equipa;
use common\models\Inscricao;
use common\models\InscricaoTorneio;
use common\models\Tournament;
use common\models\MembrosEquipa;
use yii;


class TournamentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $tournaments = Tournament::find()->all();
        return $this->render('index', ['torneios' => $tournaments]);
    }

    public function actionView($id)
    {

        $tournament = Tournament::find()
            ->where(['id' => $id])
            ->one();


        if ($tournament === null)
        {
            throw new \yii\web\HttpException(404, 'Torneio não encontrado');
        }

        return $this->render('view', ['torneio' => $tournament]);
    }

    //Inscrição

    public function actionInscricao($id)
    {
        $enrollment = new Inscricao();
        $enrollment-> id_torneio = $id;
        $enrollment-> id_utilizador = (Yii::$app->user->id);

        $membroEquipa = MembrosEquipa::findOne(['id_utilizador' => $enrollment-> id_utilizador]);

        $enrollment -> id_equipa = $membroEquipa->id_equipa;

        if ($enrollment->save()) {
            Yii::$app->session->setFlash('success', 'Equipa inscrita com sucesso!');
        } else {
            Yii::$app->session->setFlash('error', 'Erro ao inscrever equipa.');
        }
        return $this->redirect(['tournament/view', 'id' => $id]);

    }


}
