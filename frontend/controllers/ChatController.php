<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\ChatMensagem;
use common\models\User;
use common\models\MembrosEquipa;
use common\models\Equipa;
use app\mosquitto\ChatPublisher;



class ChatController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['team'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionTeam($id)
    {
        $db     = Yii::$app->db;
        $userId = Yii::$app->user->id;

        if (Yii::$app->request->isPost) {
            $text = trim(Yii::$app->request->post('message', ''));

            if ($text !== '') {
                $db->createCommand()->insert('chat_mensagens', [
                    'id_equipa'  => (int)$id,
                    'id_user'    => (int)$userId,
                    'mensagem'   => $text,
                    'created_at' => date('Y-m-d H:i:s'),
                ])->execute();
                
                ChatPublisher::publish($id, $userId, $text);
            }

            return $this->redirect(['team', 'id' => $id]);
        }


        $messages = $db->createCommand(
            'SELECT * FROM chat_mensagens WHERE id_equipa = :id ORDER BY created_at ASC'
        )->bindValue(':id', (int)$id)
            ->queryAll();

        foreach ($messages as &$m) {
            $member = MembrosEquipa::find()
                ->where([
                    'id_equipa'    => (int)$id,
                    'id_utilizador' => (int)$m['id_user'],
                ])
                ->one();

            $m['funcao'] = $member ? $member->funcao : 'player';
        }
        unset($m);
        $equipa = Equipa::findOne((int)$id);
        $teamName = $equipa ? $equipa->nome : ('Equipa #' . $id);

        return $this->render('team', [
            'teamId'   => (int)$id,
            'teamName' => $teamName,
            'messages' => $messages,
        ]);
    }
}
