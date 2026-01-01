<?php 

require_once __DIR__ . '/phpMQTT.php';

function publishTeamChatMessage($idEquipa, $idUser, $username, $mensagem)
{
    $server   = 'localhost';   // broker MQTT
    $port     = 1883;          // porta do broker
    $clientId = 'vortex_php_' . uniqid();

    $mqtt = new phpMQTT($server, $port, $clientId);

    if (!$mqtt->connect(true, null)) {
        echo "Falha a ligar ao broker\n";
        return false;
    }

    $topic = "vortex/chat/team/" . (int)$idEquipa;

    $payload = json_encode([
        'id_equipa' => (int)$idEquipa,
        'id_user'   => (int)$idUser,
        'username'  => $username,
        'mensagem'  => $mensagem,
        'timestamp' => date('c'),
    ]);

    $mqtt->publish($topic, $payload, 0, false);
    $mqtt->close();

    echo "Mensagem enviada para $topic\n";
    return true;
}