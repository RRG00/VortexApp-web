<?php
return [
    'id' => 'app-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'csrfParam' => '_csrf-backend-test',
        ],
        'session' => [
            'name' => 'advanced-backend-test',
        ],
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=vortexdb_test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
