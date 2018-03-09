<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/19
 * Time: 下午4:23
 */

namespace api\models\logic;


use api\models\Profile;
use api\models\Vip;
use api\models\VipIndexs;
use api\models\VipInfos;
use yii;

class VipBuilder
{
    private $phone;

    private $type;

    private $VIPKO;

    private $exp;

    private $VRID;

    private $VipType;

    private $profile;

    public function __construct($phone, $type = 'BIT')
    {
        $this->phone = $phone;
        $this->type = $type;
        if ($type == 'BIT') {
            $this->VipType = 'CN_BITT0';
        } else {
            $this->VipType = 'CN_SITT0';
        }
        return true;
    }


    public function build()
    {
        if (!$this->hasProfile()) {
            //个人资料处理错误
            Yii::error('个人资料处理错误');
            return false;
        }
        $this->setVipCode();
        $vip = $this->bulidVipStruct();
        if ($vip) {
            return ['vip' => $vip, 'profile' => $this->profile];
        }
        return false;
    }


    public function setVRID($vrid)
    {
        $this->VRID = $vrid;
        return $this;
    }

    private function setVipCode()
    {
        $vipindex = Yii::$app->redis->incr('vipindex');
        $vip_code_suffix = sprintf("%08d", $vipindex);
        $vip_code = 'WX' . $vip_code_suffix;
        $set = VipIndexs::findOne(1);
        $set->value = $vipindex;
        $set->save();
        $this->VIPKO = $vip_code;

    }

    public function setExp($date = '2099-12-31 00:00:00')
    {
        $this->exp = $date;
        return $this;
    }


    private function bulidVipStruct()
    {
        $insert_data = [
            'VIPKO' => $this->VIPKO,
            'ALTID' => $this->phone,
            'VRID' => $this->VRID,
            'VIPTYPE' => $this->VipType,
            'VIPNATURE' => 'M',
            'EXPDATE' => $this->exp,
            'JOINDATE' => date('Y-m-d 00:00:00'),
            'STARTDATE' => date('Y-m-d 00:00:00'),
            'REMARK' => '',
            'EMAILSUB' => 1,
        ];
        Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
        Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
        try {
            $insert = Yii::$app->mssql->createCommand()->insert('TVIP_W', $insert_data)->execute();
            Yii::error('插入返回值为'.$insert);
            if (!$insert){
                Yii::error('添加会员失败，返回false');
                return false;
            }
            $vip = new Vip();
            $vip->VIPKO = $this->VIPKO;
            $vip->ExpDate = $this->exp;
            $vip->AltID = $this->phone;
            $vip->VIPNature = 'M';
            $vip->JoinDate = date('Y-m-d 00:00:00');
            $vip->VIPType = $this->VipType;
            $vip->StartDate = date('Y-m-d 00:00:00');
            $vip->updateTime = date('Y-m-d H:i:s');
            if (!$vip->save()) {
                Yii::error('保存会员信息失败');
                Yii::error($vip->getErrors());
                return false;
            }
            return $insert_data;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
    }


    /**
     * 所有创建会员必为ALTID=TELNO的资料
     * @return bool
     */
    public function hasProfile()
    {
        $profile = Profile::find()->where(['TELNO' => $this->phone])->one();
        if (!$profile) {
            Yii::error('local没有个人'.$this->phone.'的个人资料');
            $find = new FindVip($this->phone, $this->type);
            $find->search();
            if ($find->isBulid() == false) {
                return false;
            }
            $this->profile = $find->getProfile();
        } else {
            $this->profile = $profile;
        }
        return true;
    }

}