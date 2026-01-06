<?php

require_once __DIR__ . '/phpMQTT.php';

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
