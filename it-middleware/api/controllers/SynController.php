<?php
namespace api\controllers;

use Yii;

/**
 * Site controller
 */
class SynController extends Base
{
    public function actionBplog()
    {
        // $this->response();
        $sql  = "select id from VIPBPLOG order by id desc";
        $bpid = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];

        $sql     = "select id from BPLOG order by id desc";
        $vipbpid = Yii::$app->db->createCommand($sql)->queryOne()['id'];
        if (!$vipbpid) {
            $vipbpid = 0;
        }
        if ($bpid == $vipbpid) {
            return $this->response(null, 100, 'VIPBPLOG not updated');
        } else {
            $sql  = "select ID,VIPKO,VIPTYPE,VBGROUP,VBID,EXPDATE,TXDATE,LOCKO,STDNO,MEMONO,MEMTYPE,BP,REFAMT FROM VIPBPLOG where id>$vipbpid";
            $data = Yii::$app->mssql->createCommand($sql)->queryAll();
            $ret  = Yii::$app->db->createCommand()->batchInsert('BPLOG', ['id', 'VIPKO', 'VIPTYPE', 'VBGROUP', 'VBID', 'EXPDATE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'BP', 'REFAMT'], $data)->execute();
            if ($ret) {
                return $this->response();
            }
        }
    }

    public function actionSpend()
    {
        $sql     = "select id from VIPSPEND order by id desc";
        $spendid = Yii::$app->db->createCommand($sql)->queryOne()['id'];
        if (!$spendid) {
            $spendid = 0;
        }
        $sql        = "select id from VIPSPEND order by id desc";
        $vipspendid = Yii::$app->mssql->createCommand($sql)->queryOne()['id'];
        if ($spendid == $vipspendid) {
            return $this->response(null, 100, 'VIPSPEND not updated');
        } else {
            $sql  = "select * from VIPSPEND where id >$spendid";
            $data = Yii::$app->mssql->createCommand($sql)->queryAll();
            $ret  = Yii::$app->db->createCommand()->batchInsert('VIPSPEND', ['ID', 'VIPKO', 'VIPTYPE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'QTY', 'DTRAMT'], $data)->execute();
            if ($ret) {
                return $this->response();
            }
        }
    }
}
