<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/18
 * Time: 下午12:14
 */

namespace console\server;

use api\models\BPLOG;
use swoole_server;

class SwooleTask
{
    public function __construct()
    {
        $setting = [
            'daemonize' => 0,
            'worker_num'=>2,
            'task_worker_num' => 2,
        ];
        $this->serv = new swoole_server("0.0.0.0", 9660);
        $this->serv->set($setting);
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->start();
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        $serv->task($data);
//        $this->log('接收任务' . $fd);
        $serv->send($fd, 'ok');
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {

        $array = json_decode($data, true);
//        $this->log('开始执行任务' . $task_id);
        $mem = round(memory_get_usage() / 1024 / 1024, 2) . 'MB';
//        $this->log("内存使用{$mem}");
        $serv->finish($data);
    }

    public function onFinish($serv, $task_id, $data)
    {
        $this->log('任务已完成' . $task_id);
    }

    public function log($msg, $level = 'info')
    {
        $time = date('Y-m-d H:i:s');
        echo "[{$time}] [{$level}] {$msg}\n";
    }

}