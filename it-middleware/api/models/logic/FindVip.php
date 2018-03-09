<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/18
 * Time: 下午5:11
 */

namespace api\models\logic;


use api\lib\Functions;
use api\models\crm\VPFILE;
use api\models\crm\VVIP;
use api\models\Profile;
use api\models\Vip;
use api\models\VipIndexs;
use yii;

class FindVip
{
    private $phone;

    private $type;

    private $CRMProfiles = [];

    private $profile;

    private $vip;

    private $W_MID;

    private $isBuildProfile = false;

    private $wechat_id;

    /** @var bool 是否有ALTID=TELNO的记录，如果有，则为VPFILE数组 */
    private $rightProfile = false;

    private $name = '';

    private $sex = 'F';

    private $country_code = '+86';

    public function __construct($phone, $type = 'BIT', $name = 'WECHAT_USER', $sex = 'F', $area = '中国', $wechat_id = 1)
    {
        $this->phone = $phone;
        $this->type = $type;
        $this->name = $name;
        $this->sex = $sex;
        $this->wechat_id = $wechat_id;
        switch ($area) {
            case '中国':
                $this->country_code = '+86';
                break;
            case '香港':
                $this->country_code = '+852';
                break;
            case '澳门':
                $this->country_code = '+853';
                break;
            case '台湾':
                $this->country_code = '+886';
                break;
            default:
                $this->country_code = '+86';
        }
        return true;
    }

    public function search()
    {
        $this->searchProcess();
    }

    /**
     * 搜索会员卡
     * @return bool
     */
    private function searchProcess()
    {
        $crmFinalVip = $this->isCRMVip();
        //本地没有该VIP,去CRM查找
        if (!$crmFinalVip) {
            Yii::error('CRM中没有数据');
            //CRM数据库中也没有会员信息，说明真的没有会员，需要新建一个VPFILE。
            $this->notFindToBuild();
            return false;
        } else {
            Yii::error('CRM中有数据');
            $this->setResult($crmFinalVip);
        }
    }


    /**
     * 如果没有找到会员，则新建一个会员PROFILE
     * 新建规则，如果VPFILE里面有ALTID=TELNO的记录，则不处理VPFILE记录，
     * 若没有，则新建一个ALTID=TELNO的记录
     */
    private function notFindToBuild()
    {
        $this->getVipIndexs();
        if (!$this->rightProfile) {
            Yii::error('没有rightProfile，需要新建');
            //新建ALTID=TELNO的记录
            $profile = $this->buildProfile();
            if (!$profile) {
                return false;
            } else {
                $this->profile = $profile;
                $this->vip = null;
            }
        } else {
            Yii::error('有rightProfile，更新或其他操作');
            $this->profile = $this->rightProfile;
            $profile = Profile::find()->where(['ALTID' => $this->phone])->one();
            if (!$profile) {
                $profile = new Profile();
            }
            $profile->setAttributes($this->profile);
            if (!$profile->save()) {
                Yii::error($profile->getErrors());
            }
            $this->vip = null;
        }
    }

    private function getVipIndexs()
    {
        $indexs = Yii::$app->redis->incr('W_MID_INDEXS');
        $set = VipIndexs::findOne(2);
        $set->value = $indexs;
        $set->save();
        $this->W_MID = $indexs;
        return $indexs;
    }

    private function buildProfile()
    {
        if ($this->type = 'BIT') {
            $type_string = 'WCBITCN' . sprintf("%08d", $this->W_MID);;
        } else {
            $type_string = 'WCSITCN' . sprintf("%08d", $this->W_MID);;
        }
        $insert_data = [
            'ALTID' => $this->phone,
            'VIPNAME' => $this->name,
            'TELNO' => $this->phone,
            'SEX' => $this->sex,
            'DOB' => '2017-01-01 00:00:00',
            'ROWID' => date('Y-m-d H:i:s'),
            'W_MID' => $type_string,
            'MOB' => '1',
            'W_DOB' => '1',
            'W_YOB' => '2017',
            'WECHATID' => $this->wechat_id,
            'W_COUNTRYKO' => $this->country_code,
        ];
        Yii::$app->mssql->createCommand("SET ANSI_NULLS ON;")->execute();
        Yii::$app->mssql->createCommand("SET ANSI_WARNINGS ON;")->execute();
        try {
            $insert = Yii::$app->mssql->createCommand()->insert('VPFILE_W', $insert_data)->execute();
            if (!$insert) {
                return false;
            }
            $profile = Profile::find()->where(['ALTID' => $this->phone])->one();
            if (!$profile) {
                $profile = new Profile();
            }
            $profile->setAttributes($insert_data);
            if (!$profile->save()) {
                Yii::error($profile->getErrors());
                return false;
            }
            $this->isBuildProfile = true;
            return $insert_data;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            return false;
        }
    }

    public function isBulid()
    {
        return $this->isBuildProfile;
    }

    /**
     * 设置会员属性
     * @param $vip
     * @return bool
     */
    private function setResult($vip)
    {
        $this->vip = $vip;
        $this->profile = $this->CRMProfiles[$vip['AltID']];

        //更新本地记录或者新增记录
        /** @var Vip $vip */
        $vip = Vip::find()->where(['VIPKO' => $vip['VIPKO']])->one();
        if (!$vip) {
            $vip = new Vip;
        }
        $vip->setAttributes($this->vip);
        $vip->updateTime = date('Y-m-d H:i:s');
        if (!$vip->save()) {
            Yii::error($vip->getErrors());
        }

        $profile = Profile::find()->where(['ALTID' => $vip['AltID']])->one();
        if (!$profile) {
            $profile = new Profile();
        }
        $profile->setAttributes($this->profile);

        if (!$profile->save()) {
            Yii::error($profile->getErrors());
        }
        return true;
    }

    public function getProfile()
    {
        Yii::error('===========PROFILE=============');
        Yii::error($this->profile);
        return $this->profile;
    }


    /**
     * 获取最终的查询结果
     * @return array|bool
     */
    public function getResult()
    {
        if ($this->vip && $this->profile) {
            $res = ['vip' => $this->vip, 'profile' => $this->profile];
        } else {
            $res = false;
        }
        return $res;
    }

    /**
     * 获取不同品牌VIP对应的VIPTYPE情况
     * @return array
     */
    private function getVipTypes()
    {
        if ($this->type == 'BIT') {
            return ['CN_VIPASS', 'CN_VPASS', 'CN_BIT', 'CN_BITT0'];
        } else {
            return ['CN_VIPASS', 'CN_IPASS', 'CN_SIT', 'CN_SITT0',];
        }
    }

    /**
     * 是否在中间件中存在这个VIP
     */
    public function isMidVip()
    {
        $vpfiles = Profile::find()->where(['TELNO' => $this->phone])->asArray()->all();
        if (!$vpfiles) {
            return false;
        }
        $ids = [];
        foreach ($vpfiles as $vpfile) {
            array_push($ids, $vpfile['ALTID']);
        }
        return false;
    }

    /**
     * 是否在CRM数据库中存在VIP
     * @return bool
     */
    public function isCRMVip()
    {
        /** @var VPFILE $personalIds */
        $vpfiles = VPFILE::find()->where(['TELNO' => $this->phone])->asArray()->all();
        if (!$vpfiles) {
            Yii::info('CRM里面没有PROFILE资料');
            //VPFILE中没有手机号码相关记录，该用户肯定没有注册过，需要新建VPFILE记录
            return false;
        } else {
            $ids = [];
            //手机号码中存在1个或多个personalid，找到这些personalid对应的VIP
            foreach ($vpfiles as $vpfile) {
                Functions::formatBlank($vpfile);
                if ($vpfile['ALTID'] == $vpfile['TELNO']) {
                    $this->rightProfile = $vpfile;
                }
                $this->CRMProfiles[$vpfile['ALTID']] = $vpfile;
                //将vpfiles组合成ids数组，在后续操作中用in查询
                array_push($ids, $vpfile['ALTID']);
            }
            $types = $this->getVipTypes();
            $vips = VVIP::find()
                ->where(['in', 'ALTID', $ids])
                ->andWhere(['in', 'VIPType', $types])
                ->andWhere(['>', 'ExpDate', date('Y-m-d') . ' 00:00:00.000'])
                ->asArray()->all();
            if (!$vips) {
                //有VPFILE记录，但是没有大小IT的VIP,该情况写入ALTID为手机号的记录到VPFILE中
                return false;
            } else {
                //找到1条VVIP或VIP的记录
                if (count($vips) == 1) {
                    //只有一条VIP记录，那就只有这个VIP
                    Yii::error('只有一条会员记录，直接返回', 'LOG');
                    Functions::formatBlank($vips[0]);
                    $finalVip = $vips[0];
                } else {
                    $finalVip = $this->findHigestVip($vips);
                }
                if (!$finalVip) {
                    return false;
                } else {
                    return $finalVip;
                }
            }
        }
    }

    /**
     * 找最高等级、最新的VIP
     * @param $vips
     * @return null
     */
    private function findHigestVip($vips)
    {

        $higestVip = null;
        $finalVip = null;
        foreach ($this->getVipTypes() as $vipType) {
            Yii::error("=================================", 'LOG');
            Yii::error('当前遍历会员VIPTYPE为' . $vipType, 'LOG');
            Yii::error("=================================", 'LOG');
            foreach ($vips as $vip) {
                Functions::formatBlank($vip);
                Yii::error('管理会员卡' . $vip['VIPKO'] . "vip type 为" . $vip['VIPType'], 'LOG');
                if ($vip['VIPType'] == $vipType) {
                    Yii::error('找到一个VipType=' . $vipType . "的会员卡" . $vip['VIPKO'], 'LOG');
                    if ($finalVip == null) {
                        $finalVip = $vip;
                    } else if (strtotime($vip['JoinDate']) > strtotime($finalVip['JoinDate'])) {
                        Yii::error($vip['VIPKO'] . "的JoinDate > " . $finalVip['VIPKO'] . "的JoinDate，finalVip替换为最新的");
                        $finalVip = $vip;
                    } else {
                        Yii::error($vip['VIPKO'] . "的JoinDate < " . $finalVip['VIPKO'] . "的JoinDate，finalVip不进行处理");
                    }
                }
            }
            if ($finalVip != null) {
                break;
            }
        }
        if ($finalVip == null) {
            return false;
        } else {
            return $finalVip;
        }

    }


    public function findProfile()
    {
        /** @var Profile $localProfile */
        $localProfile = Profile::find()->where(['TELNO' => $this->phone])->asArray()->one();
        if ($localProfile) {
            //本地有记录
        } else {
            //本地没有会员资料记录
            $this->findCRMPhone();
        }
    }

    /**
     * 查找CRM 中 VPFILE数据
     */
    private function findCRMPhone()
    {
        $crm = VPFILE::find()->where(['TELNO' => $this->phone])->asArray()->all();
        if (!$crm) {
            $this->addNewProfile();
        }
    }


    /**
     * 添加 VPFILE_W
     * 如果CRM没有数据，则添加一条VPFILE数据。
     */
    private function addNewProfile()
    {
        $initProfile = [
            'ALTID' => $this->phone,
            'VIPNAME' => 'WECHAT',
            'ROWID' => date('Y-m-d H:i:s'),
        ];
        Yii::error("====================新增CRM初始数据");
        Yii::error($initProfile);
    }
}