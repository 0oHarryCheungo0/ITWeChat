<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/20
 * Time: 上午11:44
 */

return [
    'port' => 9502,
    'worker_num' => 1, //一般设置为服务器CPU数的1-4倍
    'daemonize' => true, //以守护进程执行
    'max_request' => 1000,//超过设置值将重新fork一个新进程
    'task_worker_num' => 1, //task进程的数量
    "task_ipc_mode " => 3, //使用消息队列通信，并设置为争抢模式
    "log_file" => dirname(__FILE__) . '/queue.log', //日志
];