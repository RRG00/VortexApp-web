<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class ChatController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }
    function FazPublishNoMosquitto($canal, $msg)
    {
        $server   = 'localhost';
        $port     = 1883;
        $clientId = 'vortex_chat_' . uniqid();

        $mqtt = new \phpMQTT($server, $port, $clientId);

        if ($mqtt->connect(true, NULL, null, null)) {
            $mqtt->publish($canal, $msg, 0);
            $mqtt->close();
        }
    }

    public function actionHistory($teamId = null)
    {
        if (!$teamId) {
            Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'teamId é obrigatório',
            ];
        }

        try {
            $rows = (new \yii\db\Query())
                ->select(['id', 'id_equipa', 'id_user', 'username', 'mensagem', 'created_at'])
                ->from('chat_mensagens')
                ->where(['id_equipa' => (int)$teamId])
                ->orderBy(['created_at' => SORT_ASC])
                ->all();

            return [
                'status' => 'success',
                'messages' => $rows,
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionSend()
    {
        $request = Yii::$app->request;

        $teamId  = $request->post('teamId');
        $userId  = $request->post('userId');
        $message = $request->post('message');

        if (!$teamId || !$userId || !$message) {
            Yii::$app->response->statusCode = 400;
            return [
                'status'  => 'error',
                'message' => 'teamId, userId e message são obrigatórios',
            ];
        }

        try {
            $timestamp = date('Y-m-d H:i:s');

            $user = (new \yii\db\Query())
                ->select(['username'])
                ->from('user')
                ->where(['id' => (int)$userId])
                ->one();


            $username = $user ? $user['username'] : 'User_' . $userId;


            $result = Yii::$app->db->createCommand()->insert('chat_mensagens', [
                'id_equipa'  => (int)$teamId,
                'id_user'    => (int)$userId,
                'username'   => $username,
                'mensagem'   => $message,
                'created_at' => $timestamp,
            ])->execute();

            if ($result) {

                $publisherFile = Yii::getAlias('@app') . '/../mosquitto/ChatPublisher.php';
                if (file_exists($publisherFile)) {
                    require_once $publisherFile;

                    $topic   = 'vortex/chat/team/' . (int)$teamId;
                    $payload = json_encode([
                        'teamId'    => (int)$teamId,
                        'userId'    => (int)$userId,
                        'username'  => $username,
                        'message'   => $message,
                        'timestamp' => $timestamp,
                    ]);

                    $this->FazPublishNoMosquitto($topic, $payload);
                }

                return [
                    'status'  => 'success',
                    'message' => 'Mensagem enviada com sucesso',
                    'data'    => [
                        'teamId'    => (int)$teamId,
                        'userId'    => (int)$userId,
                        'username'  => $username,
                        'timestamp' => $timestamp,
                    ],
                ];
            }

            Yii::$app->response->statusCode = 500;
            return [
                'status'  => 'error',
                'message' => 'Erro ao inserir mensagem',
            ];
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return [
                'status'  => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
