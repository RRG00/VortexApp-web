<?php

namespace frontend\controllers;

use common\models\Tournament;

class TournamentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $tournaments = Tournament::find()->limit(3)->all();
        return $this->render('index', ['torneios' => $tournaments]);
    }

    public function actionView($id)
    {
        $a =3;
        $tournament = Tournament::find()
            ->where(['id_torneio' => $id])
            ->one();


        if ($tournament === null)
        {
            throw new \yii\web\HttpException(404, 'Torneio não encontrado');
        }

        return $this->render('view', ['torneio' => $tournament]);
    }


}
