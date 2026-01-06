    <?php
    $params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );

    return [
        'id' => 'app-backend',
        'basePath' => dirname(__DIR__),
        'controllerNamespace' => 'backend\controllers',
        'bootstrap' => ['log'],
        'modules' => [
            'api' => [
                'class' => 'backend\modules\api\ModuleAPI',
                'controllerNamespace' => 'backend\modules\api\controllers',
            ]
        ],
        'components' => [
            /* 'view' => [
                'theme' => [
                    'pathMap' => [
                        '@app/views' => '@app/views'
                    ],
                ],
            ],*/
            'request' => [
                'csrfParam' => '_csrf-backend',
                'parsers' => [
                    'application/json' => 'yii\web\JsonParser',
                ],
            ],
            'user' => [
                'identityClass' => 'common\models\User',
                'enableAutoLogin' => true,
                'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            ],
            'session' => [
                // this is the name of the session cookie used for login on the backend
                'name' => 'advanced-backend',
            ],
            'log' => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets' => [
                    [
                        'class' => \yii\log\FileTarget::class,
                        'levels' => ['error', 'warning'],
                    ],
                ],
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                //'enableStrictParsing' => false,
                //'showScriptName' => false,
                'rules' => [

                    //ENDPOINT -> LOGIN
                    'POST api/login' => 'api/login/login',

                    //ENDPOINTS API -> NOTIFICAÇÕES
                    'POST api/notifications/publish-invite' => 'api/notifications/publish-invite',

                    // ENDPOINTS API -> EQUIPAS
                    'GET api/team/find/<id>'        => 'api/team/find',
                    'GET api/team/by-user/<id_user>' => 'api/team/by-user',
                    'POST api/team/create'          => 'api/team/create',
                    'PUT api/team/update/<id>'      => 'api/team/update',
                    'DELETE api/team/delete/<id>'   => 'api/team/delete',

                    //ENDPOINTS API -> USER
                    'GET api/user/find/<id>' => 'api/user/find-user',
                    'PUT api/user/update/<id>' => 'api/user/update-user',
                    'POST api/user/create' => 'api/user/create',
                    'DELETE api/user/delete/<id>' => 'api/user/delete',

                    //ENDPOINTS API -> TOURNAMENT
                    'GET api/tournament/team/<id_equipa>' => 'api/tournament/find-team-tournament',
                    'GET api/tournaments/find' => 'api/tournament/find-tournament',
                    'POST api/tournament/create' => 'api/tournament/create-tournament',
                    'PUT api/tournament/update/<id>' => 'api/tournament/update-tournament',
                    'DELETE api/tournament/delete/<id>' => 'api/tournament/delete-tournament',

                    //ENDPOINTS API -> User Profile
                    'GET api/profile/view-user-profile/<id_user>'   => 'api/profile/view-user-profile',
                    'PUT api/profile/update-user-profile/<id_user>' => 'api/profile/update-user-profile',


                    //ENDPOINTS CHAT(ANDROID)
                    'POST api/chat/send' => 'api/chat/send',
                    'GET api/chat/history' => 'api/chat/history',
                ],
            ],
        ],

        'params' => $params,
    ];
