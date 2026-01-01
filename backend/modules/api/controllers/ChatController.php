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
                ->select(['id', 'id_equipa', 'id_user', 'mensagem', 'created_at']) // exemplo
                ->from('chat_mensagens')
                ->where(['id_equipa' => (int)$teamId])
                ->orderBy(['created_at' => SORT_ASC])
                ->all();

            return [
                'status' => 'success',
                'count' => count($rows),
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

        $teamId = $request->post('teamId');
        $userId = $request->post('userId');
        $message = $request->post('message');

        // Validação
        if (!$teamId || !$userId || !$message) {
            Yii::$app->response->statusCode = 400;
            return [
                'status' => 'error',
                'message' => 'teamId, userId e message são obrigatórios',
            ];
        }

        try {
            $result = Yii::$app->db->createCommand()->insert('chat_mensagens', [
                'id_equipa' => (int)$teamId,
                'id_utilizador' => (int)$userId,
                'mensagem' => $message,
                'created_at' => date('Y-m-d H:i:s'),
            ])->execute();

            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'Mensagem enviada com sucesso',
                    'data' => [
                        'teamId' => (int)$teamId,
                        'userId' => (int)$userId,
                        'timestamp' => date('Y-m-d H:i:s'),
                    ],
                ];
            } else {
                Yii::$app->response->statusCode = 500;
                return [
                    'status' => 'error',
                    'message' => 'Erro ao inserir mensagem',
                ];
            }
        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 500;
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}
