<?php
return [
    [
        'id' => 1,
        'username' => 'player',
        'auth_key' => 'test_auth_key_player',
        'password_hash' => Yii::$app->security->generatePasswordHash('12345678'),
        'password_reset_token' => null,
        'email' => 'player@test.com',
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
];
