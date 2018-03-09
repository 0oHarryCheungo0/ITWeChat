<?php

class Server
{
    private $serv;

    public function __construct($config)
    {
        $this->serv = new swoole_server("0.0.0.0", isset($config['port']) ? $config['port'] : 9502);
        $this->serv->set($config);
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->start();
    }

    public function log($msg, $level = 'info')
    {
        $time = date('Y-m-d H:i:s');
        echo "[{$time}] [{$level}] {$msg}\n";
    }

    public function onReceive(swoole_server $serv, $fd, $from_id, $data)
    {
        $serv->task($data);
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {

        $array = json_decode($data, true);
        $this->log('开始执行任务' . $task_id);
        if ($array['url']) {
            if ($array['request_type'] == 'get') {
                $this->log('请求地址为' . $array['url']);
                $rs = $this->httpGet($array['url'], $array['param']);
            } else {
                $rs = $this->httpPost($array['url'], $array['param']);
            }
            $ret = json_decode($rs, true);
            if ($ret['code'] == 0) {
                $this->log('执行任务成功' . $task_id);
            } else {
                $this->log('执行任务失败' . $task_id);
            }

        }
        $serv->finish($data);
    }

    public function onFinish($serv, $task_id, $data)
    {
        $time = date('Y-m-d H:i:s');
        $this->log('任务已完成' . $task_id);
    }

    protected function httpGet($url, $data)
    {
        if ($data) {
            $url .= '?' . http_build_query($data);
        }
        $curlObj = curl_init(); //初始化curl，
        curl_setopt($curlObj, CURLOPT_URL, $url); //设置网址
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //将curl_exec的结果返回
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlObj, CURLOPT_HEADER, 0); //是否输出返回头信息
        $response = curl_exec($curlObj); //执行
        curl_close($curlObj); //关闭会话
        return $response;
    }

    protected function httpPost($url, $data)
    {
        $curlObj = curl_init(); //初始化curl，
        curl_setopt($curlObj, CURLOPT_URL, $url); //设置网址
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1); //将curl_exec的结果返回
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlObj, CURLOPT_HEADER, 0); //是否输出返回头信息
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($curlObj); //执行
        curl_close($curlObj); //关闭会话
        return $response;
    }
}

$config = require 'queue-config.php';

if (file_exists('queue-config-local.php')) {
    $config_local = require 'queue-config-local.php';
    $config = array_merge($config, $config_local);
}
$start = date('Y-m-d H:i:s');
echo "\n\n";
echo "====================================================================\n\n";
echo "         队列服务已开启@{$start}       \n\n";
echo "====================================================================\n\n";
$server = new Server($config);
