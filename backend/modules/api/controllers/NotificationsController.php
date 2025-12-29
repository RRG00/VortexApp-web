<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\web\Controller;

class NotificationsController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * Publish invite notification via MQTT (Mosquitto)
     * Expects POST JSON: { userId, teamId, inviterId, inviterUsername, conviteId }
     */
    public function actionPublishInvite()
    {
        $body = Yii::$app->request->post();
        if (empty($body['userId']) || empty($body['conviteId'])) {
            Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Missing parameters'];
        }

        $server = isset(Yii::$app->params['mqttHost']) ? Yii::$app->params['mqttHost'] : '127.0.0.1';
        $port = isset(Yii::$app->params['mqttPort']) ? Yii::$app->params['mqttPort'] : 1883;

        $mqttFile = Yii::getAlias('@app') . '/../../mosquitto/phpMQTT.php';
        if (!file_exists($mqttFile)) {
            Yii::$app->response->statusCode = 500;
            return ['status' => 'error', 'message' => 'phpMQTT client not found'];
        }

        require_once($mqttFile);

        $clientId = 'vortex-api-' . uniqid();
        try {
            $mqtt = new \phpMQTT($server, $port, $clientId);
            if ($mqtt->connect(true, NULL, null, null)) {
                $topic = "invites/" . (int)$body['userId'];
                $payload = json_encode([
                    'type' => 'team_invite',
                    'team_id' => isset($body['teamId']) ? $body['teamId'] : null,
                    'inviter_id' => isset($body['inviterId']) ? $body['inviterId'] : null,
                    'inviter_username' => isset($body['inviterUsername']) ? $body['inviterUsername'] : null,
                    'convite_id' => $body['conviteId'],
                ]);

                $mqtt->publish($topic, $payload, 0);
                $mqtt->close();

                return ['status' => 'success'];
            } else {
                Yii::$app->response->statusCode = 500;
                return ['status' => 'error', 'message' => 'MQTT connect failed'];
            }
        } catch (\Throwable $e) {
            Yii::$app->response->statusCode = 500;
            Yii::error('MQTT publish error: ' . $e->getMessage(), __METHOD__);
            return ['status' => 'error', 'message' => 'MQTT error'];
        }
    }
}
