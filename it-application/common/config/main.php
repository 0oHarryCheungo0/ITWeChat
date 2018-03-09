<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'Asia/Shanghai',
    'components' => [
        'wechat' => [
            'class' => 'common\components\WechatApp',
            'redis' => true,
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1:3306;dbname=itwechat',
            'username' => 'root', //数据库用户名
            'password' => 'Mis.1234', //数据库密码
            'charset' => 'utf8mb4',
            'enableSchemaCache' => true,
            'schemaCacheDuration'=>0,
        ],
        'helper' => [
            'class' => 'common\components\Helper',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
    ],
];
