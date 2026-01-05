<?php

namespace frontend\controllers;

use common\models\Equipa;
use common\models\Inscricao;
use common\models\InscricaoTorneio;
use common\models\Tournament;
use common\models\MembrosEquipa;
use yii;
use yii\filters\AccessControl;


class TournamentController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['inscricao'],
                        'roles' => ['insTournament'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $tournaments = Tournament::find()->all();
        return $this->render('index', ['torneios' => $tournaments]);
    }

    public function actionView($id)
    {
        $tournament = Tournament::findOne($id);
        if ($tournament === null) {
            throw new \yii\web\NotFoundHttpException('Torneio não encontrado');
        }

        $inscricoes = \common\models\Inscricao::find()
            ->where(['id_torneio' => $id])
            ->with('equipa')        
            ->all();

        $inscritos = count($inscricoes);
        $limite    = $tournament->limite_inscricoes;
        $vagas     = max(0, $limite - $inscritos);

        return $this->render('view', [
            'torneio'    => $tournament,
            'inscritos'  => $inscritos,
            'vagas'      => $vagas,
            'inscricoes' => $inscricoes,
        ]);
    }




    public function actionInscricao($id)
    {
        $userId = Yii::$app->user->id;
        $membroEquipa = MembrosEquipa::findOne(['id_utilizador' => $userId]);
        if ($membroEquipa === null) {
            Yii::$app->session->setFlash('error', 'Não pertence a nenhuma equipa. Não pode inscrever-se no torneio.');
            return $this->redirect(['tournament/view', 'id' => $id]);
        }

        if ($membroEquipa->funcao !== 'capitao') {
            Yii::$app->session->setFlash('error', 'Apenas o capitão da equipa pode inscrever a equipa no torneio.');
            return $this->redirect(['tournament/view', 'id' => $id]);
        }

        $tournament = Tournament::findOne($id);
        if ($tournament === null) {
            Yii::$app->session->setFlash('error', 'Torneio não encontrado.');
            return $this->redirect(['tournament/index']);
        }


        $inscritos = Inscricao::find()
            ->where(['id_torneio' => $id])
            ->count();

        if ($inscritos >= $tournament->limite_inscricoes) {
            Yii::$app->session->setFlash('error', 'Limite de inscrições atingido para este torneio.');
            return $this->redirect(['tournament/view', 'id' => $id]);
        }

        $enrollment = new Inscricao();
        $enrollment->id_torneio   = $id;
        $enrollment->id_utilizador = $userId;
        $enrollment->id_equipa    = $membroEquipa->id_equipa;

        if ($enrollment->save()) {
            Yii::$app->session->setFlash('success', 'Equipa inscrita com sucesso!');
        } else {
            $errors = $enrollment->getFirstErrors();
            $msg = $errors ? implode(' ', $errors) : 'Erro ao inscrever equipa.';
            Yii::$app->session->setFlash('error', $msg);
        }

        return $this->redirect(['tournament/view', 'id' => $id]);
    }
}
