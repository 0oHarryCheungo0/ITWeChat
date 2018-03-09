<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'wechat',
    'language' => 'cn',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'wechat\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-wechat',
            'cookieValidationKey' => 'DZZiFLzYljcJ8o5SHH1P4JEdS6Lxub6I',
        ],
        'session' => [
            'name' => 'it-wechat',
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => '127.0.0.1',
                'port'     => 6379,
                'database' => 0,
            ],
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 1 : 1,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error','info'],
                    'logVars' => ['_POST'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ]
            ],
        ],

    ],
    'params' => $params,
];
