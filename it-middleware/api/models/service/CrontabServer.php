<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/12
 * Time: 上午11:42
 */

namespace api\models\service;

use GuzzleHttp\Client;
use yii;

class CrontabServer
{
    public static function BPLOG()
    {
        $sql = "select top 1 id from VIPBPLOG order by id desc";
        $crm_id = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];
        $sql = "select id from BPLOG order by id desc";
        $local_id = Yii::$app->db->createCommand($sql)->queryOne()['id'];
        if (!$local_id) {
            $local_id = 0;
        }
        if ($crm_id == $local_id) {
            return 0;
        } else {
            $sql = "select top 1000 ID,VIPKO,VIPTYPE,VBGROUP,VBID,EXPDATE,TXDATE,LOCKO,STDNO,MEMONO,MEMTYPE,BP,REFAMT FROM VIPBPLOG where id>$local_id order by ID asc";
            $data = Yii::$app->mssql->createCommand($sql)->queryAll();
            return Yii::$app->db->createCommand()
                ->batchInsert('BPLOG', ['id', 'VIPKO', 'VIPTYPE', 'VBGROUP', 'VBID', 'EXPDATE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'BP', 'REFAMT'], $data)
                ->execute();
        }
    }

    public static function SPEND()
    {
        $sql = "select top 1 id from VIPSPEND order by id desc";
        $crm_id = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];
        $sql = "select id from VIPSPEND order by id desc";
        $local_id = Yii::$app->db->createCommand($sql)->queryOne()['id'];
        if (!$local_id) {
            $local_id = 0;
        }
        if ($crm_id == $local_id) {
            return 0;
        } else {
            $sql = "select top 1000 * from VIPSPEND where id >$local_id order by ID asc";
            $data = Yii::$app->mssql->createCommand($sql)->queryAll();
            $count = Yii::$app->db->createCommand()
                ->batchInsert('VIPSPEND', ['ID', 'VIPKO', 'VIPTYPE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'QTY', 'DTRAMT'], $data)
                ->execute();
            //post数据到指定接口
            $json_data = json_encode($data);
            $client = new Client();
            $client->request('POST', 'http://wechatcn.itezhop.com/site/push',['body'=>$json_data]);
            return $count;
        }
    }
}