<?php

namespace api\models\service;

use \api\models\crm\VPFILE;
use api\models\crm\VVIP;
use api\models\TVIPW;
use api\models\VipIndexs;
use \api\models\VipInfos;
use api\models\Vips;
use api\models\VPFILEW;
use yii;

class VipReg
{
    public static function reg($data)
    {
        $alt_id = self::addInfo($data);
        return self::addVip($alt_id, $data);
    }

    public static function validatePhone($data)
    {
        $phone = $data['TELNO'];
        //本地没有手机号，检查服务器是否有，服务器上也没有，则生成一个
        $local = VipInfos::find()->where(['telno' => $phone])->one();
        if ($local) {
            return $local->alt_id;
        }
        $crm = VPFILE::find()->where(['TELNO' => $phone])->one();
        if ($crm) {
            return $crm->ALTID;
        }
        return $data['TELNO'];
    }

    public static function addInfo($data)
    {
        //添加info
        $alt_id = self::validatePhone($data);
        $insert_data = [
            'ALTID' => $alt_id,
            'WECHATID' => $data['WECHAT_ID'],
            'ADDR1' => $data['ADDR1'],
            'ADDR2' => '',
            'DOB' => $data['DOB'],
            'MOB' => date('m', strtotime($data['DOB'])),
            'VIPNAME' => $data['VIPNAME'],
            'TELNO' => $data['TELNO'],
            'SEX' => $data['SEX'],
            'EMAILADDR' => $data['EMAIL'],
            'ROWID' => date('Y-m-d H:i:s'),
        ];
        Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
        Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
        try {
            Yii::$app->mssql->createCommand()->insert('VPFILE_W', $insert_data)->execute();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new \Exception('保存详细信息失败' . $e->getMessage());
        }
        return $alt_id;

    }

    public static function checkVipCode()
    {
        $vipindex = Yii::$app->redis->incr('vipindex');
        $vip_code_suffix = sprintf("%08d", $vipindex);
        $vip_code = 'WX' . $vip_code_suffix;
        $set = VipIndexs::findOne(1);
        $set->value = $vipindex;
        $set->save();
        return $vip_code;

    }

    public static function addVip($alt_id, $data)
    {

        $vip_code = self::checkVipCode();
        $insert_data = [
            'VIPKO' => $vip_code,
            'ALTID' => $alt_id,
            'VRID' => $data['VRPREFIX'],
            'VIPTYPE' => $data['VIPTYPE'],
            'VIPNATURE' => 'M',
            'EXPDATE' => $data['EXPDATE'],
            'JOINDATE' => date('Y-m-d 00:00:00'),
            'STARTDATE' => date('Y-m-d 00:00:00'),
            'REMARK' => '',
            'EMAILSUB' => $data['EMAILSUB'],
        ];

        Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
        Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
        try {
            Yii::$app->mssql->createCommand()->insert('TVIP_W', $insert_data)->execute();
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new \Exception('添加会员失败');
        }
        $vips = new Vips();
        $vips->vip_code = $vip_code;
        $vips->alt_id = (string)$alt_id;
        $vips->vip_type = $data['VIPTYPE'];
        $vips->update_time = date('Y-m-d 00:00:00');
        $vips->state = 1;
        $vips->reflect = 0;
        $vips->join_date = date('Y-m-d 00:00:00');
        $vips->exp_date = $data['EXPDATE'];
        $vips->group = $data['GROUP'];
        if (!$vips->save()) {
            Yii::error($vips->getErrors());
        }

        if (!VipInfos::find()->where(['alt_id' => $alt_id])->one()) {
            Yii::error('==========添加VIPINFO==========');
            $info = new VipInfos();
            $info->alt_id = (string)$alt_id;
            $info->name = $data['VIPNAME'];
            $info->DOB = $data['DOB'];
            $info->telno = (string)$data['TELNO'];
            $info->sex = $data['SEX'];
            $info->addr1 = $data['ADDR1'];
            $info->email = $data['EMAIL'];
            if (!$info->save()) {
                Yii::error($info->getErrors());
            }
        }
        return [
            'vip_code' => $vip_code,
            'alt_id' => $alt_id,
            'vip_type' => $data['VIPTYPE'],
            'join_date' => $insert_data['JOINDATE'],
            'exp_date' => $insert_data['EXPDATE']
        ];

    }
}
