<?php
require_once __DIR__ . '/phpMQTT.php';

$server   = '172.22.21.245';
$port     = 1883;
$clientId = 'vortex_chat_subscriber_' . uniqid();

$mqtt = new \phpMQTT($server, $port, $clientId);

if (!$mqtt->connect(true, NULL, null, null)) {
    exit("Não foi possível ligar ao broker MQTT\n");
}

$topics['vortex/chat/team/+'] = [
    'qos'      => 0,
    'function' => function ($topic, $msg) {
        echo "[$topic] $msg\n";
    },
];

$mqtt->subscribe($topics, 0);

echo "Subscriber à escuta em vortex/chat/team/+ ...\n";

while ($mqtt->proc()) {
}
