<?php

namespace api\models\service;

use api\models\BPLOG;
use api\models\VIPBPLOGW;
use yii;

class BPlogService
{


    public static function getOne($vip_code, $memono)
    {
        $log = BPLOG::find()
            ->where(['VIPKO' => $vip_code, 'MEMONO' => $memono])
            ->andWhere(['>', 'BP', 0])
            ->one();
        if ($log) {
            return $log->BP;
        } else {
            return 0;
        }
    }

    public static function getUsedByDay($day)
    {
        $vip_types = [
            'CN_SITT0',
            'CN_SIT',
            'CN_IPASS',
            'CN_BITT0',
            'CN_BIT',
            'CN_VPASS',
            'CN_VIPASS',
        ];
        return $spends = BPLOG::find()
            ->select(['VIPKO', 'TXDATE', 'LOCKO', 'MEMTYPE', 'BP','MEMONO'])
            ->where(['TXDATE' => $day . " 00:00:00"])
            ->andWhere(['<', 'BP', 0])
            ->andWhere(['in', 'VIPTYPE', $vip_types])
            ->asArray()
            ->all();
    }

    //SELECT SUM(BP) as BP,EXPDATE,VIPKO FROM VIPBPLOG WHERE VIPKO=:VIPKO GROUP BY VIPKO,EXPDATE
    public static function getLogs($vip_code)
    {
        $exp_date = date('Y-m-d 00:00:00');
        $will_exp_date = date('Y-m-d 00:00:00', strtotime('+3 months'));
        $ret['bp'] = BPLOG::find()
            ->where(['VIPKO' => $vip_code])
            ->andWhere(['>', 'EXPDATE', $exp_date])
            ->sum("BP");
        if (!$ret['bp']) {
            $ret['bp'] = 0;
        }
        $ret['exp_in_3month'] = BPLOG::find()
            ->where(['VIPKO' => $vip_code])
            ->andWhere(['between', 'EXPDATE', $exp_date, $will_exp_date])
            ->sum("BP");
        $will_exp_30day_date = date('Y-m-d 00:00:00', strtotime('+30 days'));
        $ret['exp_in_30day'] = BPLOG::find()
            ->where(['VIPKO' => $vip_code])
            ->andWhere(['between', 'EXPDATE', $exp_date, $will_exp_30day_date])
            ->sum("BP");

        if (!$ret['exp_in_30day']) {
            $ret['exp_in_30day'] = 0;
        }
        if (!$ret['exp_in_3month']) {
            $ret['exp_in_3month'] = 0;
        }
        return $ret;
    }

    /**
     * 添加积分记录
     * @param $insert_data
     * @return bool
     * @throws \Exception
     */
    public static function add($insert_data)
    {
//        $bp = new VIPBPLOGW();
//        $bp->setAttributes($insert_data);
//        if (!$bp->save()) {
//            throw new \Exception('积分写入失败'.json_encode($bp->getErrors()));
//        }
//        return true;

        Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
        Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
        $insert = Yii::$app->mssql->createCommand()->insert('VIPBPLOG_W', $insert_data)->execute();
        if (!$insert) {
            throw new \Exception('积分写入失败');
        }
        return true;
    }


    /**
     * 积分获取记录
     * @param $vip_code
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRecord($vip_code)
    {
        return BPLOG::find()->where(['VIPKO' => $vip_code])
            ->andWhere('BP > :BP', [':BP' => 0])
            ->orderBy('ID desc')
            ->all();
    }

    /**
     * 积分使用记录
     * @param $vip_code
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function spendRecord($vip_code)
    {
        return BPLOG::find()
            ->where(['VIPKO' => $vip_code])
            ->andWhere('BP < :BP', [':BP' => 0])
            ->orderBy('ID desc')
            ->all();
    }

    /**
     * 积分记录详情
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function detail($id)
    {
        return BPLOG::findOne(['id' => $id]);
    }


}
