<?php

$mysql_database = require_once __DIR__ . '/mysql.php';

$mssql_database = require_once __DIR__ . '/mssql.php';



return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // 'redis' => [
        //     'class' => 'yii\redis\Connection',
        //     'hostname' => 'r-bp15020a8be0fe94.redis.rds.aliyuncs.com',
        //     'port' => 6379,
        //     'password' => 'FpLlmE0setjV',
        // ],
       'redis' => [
           'class' => 'yii\redis\Connection',
           'hostname' => 'test.oftenfull.com',
           'port' => 6379,
           'password' => 'oftenfull',
       ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@runtime/cache',
        ],
        'mssql' => $mssql_database,
        'db' => $mysql_database,
    ],
];
