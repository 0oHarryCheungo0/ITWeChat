<?php

return [
    'class'             => 'yii\db\Connection',
    //'dsn'               => 'dblib:host=112.65.163.211;dbname=WECHAT_TEMP',
  // 'dsn'               => 'dblib:host=10.241.104.218;dbname=wechat_test',
// 'dsn'               => 'dblib:host=101.37.15.177;dbname=wechat_test',   
'dsn'               => 'dblib:host=97.64.24.219:6000;dbname=WECHAT_TEMP',  
 'username'          => 'testuser', //数据库用户名
    'password'          => 'testuser', //数据库密码
    'charset'           => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 0,
    'schemaCache' => 'cache',
];


//return [
//    'class' => 'yii\db\Connection',
//    'dsn' => 'dblib:host=27.36.113.12;dbname=IT',
//    'username' => 'sa',
//    'password' => 'Oftenfull301,',
//    'charset' => 'utf8',
//    'enableSchemaCache' => true,
//    'schemaCacheDuration' => 3600,
//    'schemaCache' => 'cache',
//
//];
