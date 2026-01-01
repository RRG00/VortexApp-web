<?php
require_once __DIR__ . '/phpMQTT.php';

$server   = 'localhost';
$port     = 1883;
$clientId = 'vortex_sub_' . uniqid();


$pdo = new PDO('mysql:host=localhost;dbname=vortexdb;charset=utf8', 'root', '');


function handleChatMessage($topic, $msg) {
    global $pdo;

    $data = json_decode($msg, true);
    if (!$data) {
        return;
    }

    $stmt = $pdo->prepare("
        INSERT INTO chat_mensagens (id_equipa, id_user, username, mensagem, created_at)
        VALUES (:id_equipa, :id_user, :username, :mensagem, :created_at)
    ");

    $stmt->execute([
        ':id_equipa'  => $data['id_equipa'] ?? 0,
        ':id_user'    => $data['id_user'] ?? 0,
        ':username'   => $data['username'] ?? '',
        ':mensagem'   => $data['mensagem'] ?? '',
        ':created_at' => date('Y-m-d H:i:s'),
    ]);

    echo "Gravada mensagem da equipa {$data['id_equipa']}: {$data['mensagem']}\n";
}

$mqtt = new phpMQTT($server, $port, $clientId);

if (!$mqtt->connect(true, null)) {
    exit("Falha a ligar ao broker\n");
}


$topics = [
    'vortex/chat/team/+' => [
        'qos' => 0,
        'function' => 'handleChatMessage'
    ]
];

$mqtt->subscribe($topics, 0);

echo "À escuta em vortex/chat/team/+ ...\n";


while ($mqtt->proc()) {
}
