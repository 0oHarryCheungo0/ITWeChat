<?php

namespace api\models\service;

use api\models\crm\VPFILE;
use api\models\crm\VVIP;
use api\models\logic\VipRules;
use api\models\VipInfos;
use api\models\Vips;
use api\response\Response;
use Yii;

class VipService
{
    /**
     * 根据vip_code，找到会员信息
     * @param $vip_code
     * @return Response
     */
    public static function findVip($vip_code)
    {
        $response = new Response;
        $vip = Vips::find()
            ->where(['vip_code' => $vip_code])
            ->one();
        if (!$vip) {
            $response->state = 404;
        } else {
            if ($vip->state == 1) {
                $response->data = $vip;
            } else {
                $response->data = self::getReflectVip($vip);
                $response->state = 302;
            }
        }
        return $response;
    }

    /**
     * 通过reflect参数，找到最新的会员卡号
     * @param Vips $vip
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getReflectVip(Vips $vip)
    {
        $ref_id = $vip->reflect;
        Yii::error('递归寻找最新数据，当前 reflect => ' . $vip->reflect);
        $next_vip = Vips::find()->where(['id' => $ref_id])->one();
        if ($next_vip->state == 1) {
            return $next_vip;
        } else {
            Yii::error('找到了vip_code' . $next_vip->vip_code . '，reflect => ' . $next_vip->reflect);
            Yii::error('next_vip也不是最终结果，所以讲当前的 reflect => ' . $vip->reflect . '指向' . $next_vip->reflect);
            $vip->reflect = $next_vip->reflect;
            $vip->save();
            return self::getReflectVip($next_vip);
        }
    }

    /**
     * 同步查找到的vip到本地
     * @param  VipRules $VipRules VipRules 返回对象
     * @param  string $type VIP类型
     * @return bool        是否同步成功
     */
    public static function synVip(VipRules $VipRules, $type)
    {
        $vip = $VipRules->getVip();
        $is_set_info = VipInfos::find()->where(['alt_id' => $vip['alt_id']])->one();
        Yii::error('查看本地是否有这个ALT_ID');
        if (!$is_set_info) {
            Yii::error('本地没有ALT_ID.准备新增，调用CRM中的VPFILE数据');
            $file = VPFILE::find()->where(['ALTID' => $vip['alt_id']])->one();
            self::addInfo($file);
        }
        $local_vip = Vips::find()->where(['vip_code' => $vip['vip_code']])->one();
        if (!$local_vip) {
            Yii::error('本地新增vip');
            $vip = $VipRules->getOriginalVip();
            return self::addVip($vip, $type);
        } else {
            Yii::error('本地有这个vip');
            if ($local_vip->state == 1) {
                Yii::error('本地VIP状态正常，不需要进行处理');
                return true;
            } else {
                $reflect_record = Vips::find()->where(['id' => $local_vip->reflect])->one();
                Yii::error('先找到本地VIP  reflect 指向的VIP记录' . $reflect_record->vip_code);
                Yii::error('将所有指向reflect_record的记录都指向目前这个local_vip' . $local_vip->vip_code);
                Vips::updateAll(['reflect' => $local_vip->id], ['reflect' => $reflect_record->id]);
                $reflect_record->state = 2;
                $reflect_record->reflect = $local_vip->id;
                $reflect_record->save();
                Yii::error('更新reflect_record');
                $local_vip->state = 1;
                $local_vip->reflect = 0;
                $local_vip->join_date = '';
                $local_vip->exp_date = '';
                $local_vip->update_time = date('Y-m-d H:i:s');
                $local_vip->save();
            }
        }
    }

    public static function addInfo(VPFILE $file)
    {
        Yii::error('本地新增一个ALT_ID');
        $has = VipInfos::find()->where(['alt_id'=>$file->ALTID])->one();
        if ($has){
            return true;
        }
        $info = new VipInfos();
        $info->alt_id = trim($file->ALTID);
        $info->name = trim($file->VIPNAME);
        $info->DOB = trim($file->DOB);
        $info->addr1 = trim($file->ADDR1);
        $info->addr2 = trim($file->ADDR2);
        $info->telno = trim($file->TELNO);
        $info->sex = trim($file->SEX);
        $info->email = trim($file->EMAILADDR);
        $info->W_FNAME= trim($file->W_FNAME);
        $info->W_LNAME = trim($file->W_LNAME);
        $info->W_DOB = trim($file->W_DOB);
        $info->W_YOB = trim($file->W_YOB);
        $info->W_COUNTRY = trim($file->W_COUNTRY);
        $info->W_PROVINCE=trim($file->W_PROVINCE);
        $info->W_CITY = trim($file->W_CITY);
        $info->W_DISTRICT = trim($file->W_DISTRICT);
        $info->W_DTL_ADDR = trim($file->W_DTL_ADDR);
        $info->W_SALARY = trim($file->W_SALARY);
        $info->W_OCCUPATION = trim($file->W_OCCUPATION);
        $info->W_EDUCATION = trim($file->W_EDUCATION);
        $info->W_INTERESTS = trim($file->W_INTERESTS);
        $info->W_COUNTRYKO = trim($file->W_COUNTRYKO);
        $info->save();
    }

    public static function addVIp($vip, $type)
    {
        Yii::error('本地新增一个VIP');
        $model = new Vips();
        $model->alt_id = trim($vip['AltID']);
        $model->vip_code = trim($vip['VIPKO']);
        $model->vip_type = trim($vip['VIPType']);
        $model->update_time = date('Y-m-d H:i:s');
        $model->group = strtoupper($type);
        $model->state = 1;
        $model->reflect = 0;
        $model->join_date = $vip['JoinDate'];
        $model->exp_date = $vip['ExpDate'];
        if (!$model->save()) {
            Yii::error('error' . $model->getErrors());
            return false;
        }
        return $model;
    }

    public static function getCRMVIP($vip_code,$type='WS')
    {
        $has = VVIP::find()->where(['VIPKO' => $vip_code])->one();
        if (!$has) {
            return false;
        } else {
            $vip = self::addVIp($has,$type);
            if ($vip){
                self::checkAltID($vip->alt_id);
                return $vip;
            } else {
                return false;
            }
        }
    }

    public static function checkAltID($alt_id)
    {
        $info = VipInfos::find()->where(['alt_id'=>$alt_id])->one();
        if (!$info){
            $info = VPFILE::find()->where(['ALTID'=>$alt_id])->one();
            self::addInfo($info);
        } else {
            return true;
        }
    }

}
