<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/12
 * Time: 上午11:41
 */

namespace api\controllers;


use api\models\service\CrontabServer;
use api\models\VipIndexs;
use yii;

class CrontabController extends Base
{
    public function actionBplog()
    {
//        ini_set('memory_limit', '512M');
//        try {
//            $update = CrontabServer::BPLOG();
//            if ($update != 0) {
//                Yii::warning('更新了' . $update . '条积分数据');
//            }
//        } catch (\Exception $e) {
//            Yii::error('更新数据出现异常' . $e->getMessage());
//            echo 'ERROR';
//        }
    }

    public function actionSpend()
    {
        ini_set('memory_limit', '512M');
        try {
            $bplog = CrontabServer::BPLOG();
            $update = CrontabServer::SPEND();
            if ($bplog != 0){
                Yii::warning('更新了' . $bplog . '条积分记录');
            }
            if ($update != 0) {
                Yii::warning('更新了' . $update . '条消费记录');
            }
        } catch (\Exception $e) {
            Yii::error('更新数据出现异常' . $e->getMessage());
            echo 'ERROR';
        }
    }


    /**
     * 同步redis的VIPIndex
     */
    public function actionReindex()
    {
        $indexs = VipIndexs::findOne(1);
        $local = $indexs->value;
        $redis_index = Yii::$app->redis->get('vipindex');
        if (!$redis_index) {
            Yii::$app->redis->set('vipindex', $local);
        }
        if ($redis_index > $local){
            $indexs->value = $redis_index;
            $indexs->save();
        }
    }

    public function actionTest(){
        $sql = "select top 1 id from VIPBPLOG order by id desc";
        $crm_id = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];
        echo $crm_id;exit;
    }

}