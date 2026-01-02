<?php
require_once __DIR__ . '/ChatPublisher.php';

$idEquipa = 5;
$idUser   = 24;
$username = 'player_api';

while (true) {
    echo "Mensagem: ";
    $linha = trim(fgets(STDIN));
    if ($linha === '') continue;
    if ($linha === 'sair') break;

    publishTeamChatMessage($idEquipa, $idUser, $username, $linha);
}
