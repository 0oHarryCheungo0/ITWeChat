<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/30
 * Time: 下午5:45
 */

namespace console\controllers;


use console\server\SwooleTask;
use yii\console\Controller;
use yii;

class BonusController extends Controller
{
    /**
     * 同步BPLOG;
     */
    public function actionIndex()
    {
        ini_set('memory_limit', '512M');
        $sql = "select id from VIPBPLOG order by id desc";
        $max = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];
        if (!$max){
            $max = 0;
        }
        $round = 0;
        echo "当前数据库最大值为{$max}\n";
        while (true) {
            $round++;
            if ($round == 20){
                echo "批处理完成,重新运行添加\n";exit;
            }
            echo "\n==========================================\n";
            $sql = "select id from BPLOG order by id desc";
            $vipbpid = Yii::$app->db->createCommand($sql)->queryOne()['id'];
            if (!$vipbpid) {
                $vipbpid = 0;
            }
            echo "本地最大ID为{$vipbpid}\n";
            if ($max == $vipbpid) {
                echo "两个值相等，暂时不处理\n";
                //相等，不处理
                sleep(2);
            } else {
                $sql = "select top 30000 ID,VIPKO,VIPTYPE,VBGROUP,VBID,EXPDATE,TXDATE,LOCKO,STDNO,MEMONO,MEMTYPE,BP,REFAMT FROM VIPBPLOG where id>$vipbpid order by id asc";
                $data = Yii::$app->mssql->createCommand($sql)->queryAll();
                $nums = Yii::$app->db->createCommand()->batchInsert('BPLOG', ['id', 'VIPKO', 'VIPTYPE', 'VBGROUP', 'VBID', 'EXPDATE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'BP', 'REFAMT'], $data)->execute();
                echo "本次更新了{$nums}条数据\n";
            }
            $mem = round(memory_get_usage() / 1024 / 1024, 2) . 'MB';
            echo "使用内存{$mem}\n";
            unset($sql);
            unset($data);
            unset($nums);
            unset($bpid);
            unset($vipbpid);
            unset($mem);
        }
    }

    public function actionSpend()
    {
        ini_set('memory_limit', '512M');
        $sql = "select id from VIPSPEND order by id desc";
        $max = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];
        if (!$max){
            $max = 0;
        }
        $round = 0;
        echo "当前数据库最大值为{$max}\n";
        while (true) {
            $round++;
            if ($round == 20){
                echo "批处理完成,重新运行添加\n";exit;
            }
            echo "\n==========================================\n";
            $sql = "select id from VIPSPEND order by id desc";
            $vipbpid = Yii::$app->db->createCommand($sql)->queryOne()['id'];
            if (!$vipbpid) {
                $vipbpid = 0;
            }
            echo "本地最大ID为{$vipbpid}\n";
            if ($max == $vipbpid) {
                echo "两个值相等，暂时不处理\n";
                //相等，不处理
                sleep(2);
            } else {
                $sql = "select top 30000 * from VIPSPEND where id >$vipbpid order by id asc";
                $data = Yii::$app->mssql->createCommand($sql)->queryAll();
                $nums = Yii::$app->db->createCommand()
                    ->batchInsert('VIPSPEND', ['ID', 'VIPKO', 'VIPTYPE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'QTY', 'DTRAMT'], $data)
                    ->execute();
                echo "本次更新了{$nums}条数据\n";
            }
            $mem = round(memory_get_usage() / 1024 / 1024, 2) . 'MB';
            echo "使用内存{$mem}\n";
            unset($sql);
            unset($data);
            unset($nums);
            unset($bpid);
            unset($vipbpid);
            unset($mem);
        }
    }

    public function actionSwoole()
    {
        $server = new SwooleTask();
    }


    protected function log($message)
    {
        error_log($message, 3, 'msg.log');
    }

}