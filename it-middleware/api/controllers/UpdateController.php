<?php

namespace api\controllers;

use api\models\crm\VPFILE;
use api\models\Profile;
use api\models\VipIndexs;
use app\models\UpdateVipInfo;
use Yii;

class UpdateController extends Base
{
    public function actionVipVersion()
    {
        $sql = "SELECT * FROM DB_VERSION WHERE TABLE_NAME='VVIP'";
        $version = Yii::$app->db->createCommand($sql)
            ->queryOne();
        // print_r($version);
        $query_params = [
            'TABLE' => 'VVIP',
            'START_ROW' => $version['MAX_ROWS'],
        ];
        echo $query_string = json_encode($query_params);
        $api_url = Yii::$app->params['db_api_host'];
    }

    public function actionVip()
    {
        $sql = "select count(*) as count from VIP";
        $vipcount = Yii::$app->db->createCommand($sql)->queryOne()['count'];

        $sql = "select count(*) as count from VVIP";
        $vvipcount = Yii::$app->mssql->createCommand($sql)->queryOne()['count'];
        if ($vipcount == $vvipcount) {
            return $this->response(null, 100, 'VVIP not updated');
        } else {
            $limit = $vvipcount - $vipcount;
            $sql = "select top $limit * from VVIP where VIPKO not in (select top $vipcount VIPKO from VVIP)";
            $data = Yii::$app->mssql->createCommand($sql)->queryAll();
            $ret = Yii::$app->db->createCommand()->batchInsert('VIP', ['VIPKO', 'ALTID', 'VIPNature', 'VIPType', 'VRID', 'Joindate', 'Startdate', 'Expdate', 'Discrate', 'EmailSub'], $data)->execute();
            if ($ret == $limit) {
                $date = date('Y-m-d H:i:s');
                $sql = "update DB_VERSION set MAX_ROWS=$vvipcount,BEFORE_ROWS=$vipcount,LAST_UPDATE='$date' where TABLE_NAME='VVIP'";
                Yii::$app->db->createCommand($sql)->execute();
                return $this->responsee();
            }
        }
    }

    public function actionVipinfo()
    {
        $sql = "select count(*) as count from VIP_INFO";
        $vipinfocount = Yii::$app->db->createCommand($sql)->queryOne()['count'];

        $sql = "select count(*) as count from VPFILE";
        $vpfilecount = Yii::$app->mssql->createCommand($sql)->queryOne()['count'];
        if ($vipinfocount == $vpfilecount) {
            return $this->response(null, 100, 'VPFILE not updated');
        } else {
            $limit = $vpfilecount - $vipinfocount;
            $sql = "select top $limit * from VPFILE where ALTID not in (select top $vipinfocount ALTID from VPFILE)";
            $data = Yii::$app->mssql->createCommand($sql)->queryAll();
            $ret = Yii::$app->db->createCommand()->batchInsert('VIP_INFO', ['ALTID', 'VIPName', 'Addr1', 'Addr2', 'Telno', 'Sex', 'DOB', 'Emailaddr', 'MOB'], $data)->execute();
            if ($ret == $limit) {
                $date = date('Y-m-d H:i:s');
                $sql = "update DB_VERSION set MAX_ROWS=$vpfilecount,BEFORE_ROWS=$vipinfocount,LAST_UPDATE='$date' where TABLE_NAME='VPFILE'";
                Yii::$app->db->createCommand($sql)->execute();
                return $this->response();
            }
        }
    }

    public function actionBplog()
    {
        $sql = 'Select * from DB_VERSION where TABLE_NAME="BPLOG"';
        $db_version = Yii::$app->db->createCommand($sql)->queryOne();
        $sql = "select id,VIPKO,VIPTYPE,VBGROUP,VBID,EXPDATE,TXDATE,LOCKO,STDNO,MEMONO,MEMTYPE,BP,REFAMT FROM VIPBPLOG where id>" . $db_version['MAX_ROWS'];
        $data = Yii::$app->mssql->createCommand($sql)->queryAll();
        $ret = Yii::$app->db->createCommand()->batchInsert('BPLOG', ['id', 'VIPKO', 'VIPTYPE', 'VBGROUP', 'VBID', 'EXPDATE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'BP', 'REFAMT'], $data)->execute();
        if ($ret) {
            $MAX_ROWS = end($data)['id'];
            $date = date('Y-m-d H:i:s');
            $sql = "update DB_VERSION set MAX_ROWS=$MAX_ROWS,BEFORE_ROWS=" . $db_version['MAX_ROWS'] . ",LAST_UPDATE='$date' where TABLE_NAME='BPLOG'";
            Yii::$app->db->createCommand($sql)->execute();
            return $this->response();
        } else {
            return $this->response(null, 100, 'BPLOG not updated');
        }
    }

    public function actionVipSpend()
    {
        $sql = 'Select * from DB_VERSION where TABLE_NAME="VIPSPEND"';
        $db_version = Yii::$app->db->createCommand($sql)->queryOne();
        $sql = "select * FROM VIPSPEND where id>" . $db_version['MAX_ROWS'];
        $data = Yii::$app->mssql->createCommand($sql)->queryAll();
        $ret = Yii::$app->db->createCommand()->batchInsert('VIPSPEND', ['id', 'VIPKO', 'VIPTYPE', 'TXDATE', 'LOCKO', 'STDNO', 'MEMONO', 'MEMTYPE', 'QTY', 'DTRAMT'], $data)->execute();
        if ($ret) {
            $MAX_ROWS = end($data)['id'];
            $date = date('Y-m-d H:i:s');
            $sql = "update DB_VERSION set MAX_ROWS=$MAX_ROWS,BEFORE_ROWS=" . $db_version['MAX_ROWS'] . ",LAST_UPDATE='$date' where TABLE_NAME='VIPSPEND'";
            Yii::$app->db->createCommand($sql)->execute();
            return $this->response();
        } else {
            return $this->response(null, 100, 'VIPSPEND not updated');
        }
    }

    public function actionTest()
    {
        echo 123;
        die;
        $model = new UpdateVipInfo();
        $altid = 'T91499149';
        $vipko = 'TEST100002';
        $data = array();
        $data['VIPNature'] = 'F';
        $data['VIPType'] = 'HK';
        $data['VRID'] = 'VR16100000';
        $data['Joindate'] = date('Y-m-d H:i:s');
        $data['Startdate'] = date('Y-m-d H:i:s');
        $data['Expdate'] = date('Y-m-d H:i:s');
        $data['Discrate'] = 1;
        $data['Emailsub'] = 0;
        $data['VIPName'] = 'Fei';
        $data['Addr1'] = 'addr1';
        $data['Addr2'] = 'addr2';
        $data['Telno'] = 'T00200811';
        $data['Sex'] = 'F';
        $data['DOB'] = date('Y-m-d H:i:s');
        $data['Emailaddr'] = '123@qq.com';
        $data['MOB'] = date('m');
        $ret = $model->receiveInfo($data, $vipko, $altid);
        var_dump($ret);
    }

    public function actionSelfUpdate()
    {
        $alt_id = Yii::$app->request->get('alt_id');
        /** @var VPFILE $file */
        $file = VPFILE::find()->where(['ALTID' => $alt_id])->asArray()->one();
        //获取W_MID
        $indexs = Yii::$app->redis->incr('W_MID_INDEXS');
        $set = VipIndexs::findOne(2);
        $set->value = $indexs;
        $set->save();
        if (strpos($file['W_MID'], 'SIT') !== false) {
            $w_mid = 'WCSITCN' . sprintf("%08d", $indexs);
        } else {
            $w_mid = 'WCBITCN' . sprintf("%08d", $indexs);
        }
        $insert_data = $file;
        $insert_data['W_MID'] = $w_mid;
        $insert_data['ROWID'] = date('Y-m-d H:i:s');
        Yii::error('W_MID=' . $w_mid);
        try {
            Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
            Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
            Yii::$app->mssql->createCommand()->insert('VPFILE_W', $insert_data)->execute();
            /** @var Profile $profile */
            $profile = Profile::find()->where(['ALTID' => $alt_id])->one();
            $profile->W_MID = $w_mid;
            $profile->save();
            $this->response('修改成功');
        } catch (\Exception $e) {
            Yii::error('保存失败', $e->getMessage());
        }

    }

}
