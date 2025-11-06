<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;
use common\models\Estatisticas;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Only authenticated users
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        
        // Get user statistics
        $estatisticas = Estatisticas::find()
            ->where(['id_utilizador' => $user->id])
            ->joinWith(['jogo'])
            ->all();
        
        // Calculate total wins and losses
        $totalVitorias = 0;
        $totalDerrotas = 0;
        
        foreach ($estatisticas as $stat) {
            $totalVitorias += $stat->vitorias;
            $totalDerrotas += $stat->derrotas;
        }
        
        $totalJogos = $totalVitorias + $totalDerrotas;
        $winRate = $totalJogos > 0 ? round(($totalVitorias / $totalJogos) * 100, 1) : 0;
        
        return $this->render('index', [
            'user' => $user,
            'estatisticas' => $estatisticas,
            'totalVitorias' => $totalVitorias,
            'totalDerrotas' => $totalDerrotas,
            'totalJogos' => $totalJogos,
            'winRate' => $winRate,
        ]);
    }
}