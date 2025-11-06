<?php

namespace frontend\controllers;

use common\models\Estatisticas;
use yii\web\Controller;

class RankingController extends Controller
{
    public function actionIndex()
    {
        $rankings = Estatisticas::find()
            ->joinWith(['utilizador', 'jogo'])
            ->orderBy(['vitorias' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'rankings' => $rankings,
        ]);
    }
}

