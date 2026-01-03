<?php

namespace app\mosquitto;

use Yii;

class ChatPublisher
{
    public static function publish($teamId, $userId, $text)
    {
        require_once __DIR__ . '/phpMQTT.php';

        $server   = '127.0.0.1';
        $port     = 1883;
        $clientId = 'vortex_chat_' . uniqid();

        $mqtt = new \phpMQTT($server, $port, $clientId);

        if ($mqtt->connect(true, NULL, null, null)) {
            $topic = "vortex/chat/team/$teamId";

            $payload = json_encode([
                'teamId'  => (int)$teamId,
                'userId'  => (int)$userId,
                'message' => $text,
                'time'    => date('Y-m-d H:i:s'),
            ]);

            $mqtt->publish($topic, $payload, 0);
            $mqtt->close();
        }
    }
}
