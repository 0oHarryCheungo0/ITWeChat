<?php

namespace api\models\logic;

/**
 * vip获取规则类
 * 规则说明
 * 1.检查手机号对应的ALTID
 * 2.对所有ALTID进行查询，查找出VIPKO
 * 3.根据VIPType,返回等级最高，最新的VIPKO和ALTID
 */

use api\exceptions\VipRuleException as Exception;
use api\lib\Functions;
use api\models\crm\VPFILE;
use api\models\logic\VipLogic;
use api\models\VipInfos;

class VipRules
{
    //手机号
    public $phone;

    //alt_id
    public $altIds;

    //VipType
    public $type;

    //会员卡号
    public $vipCodes;

    //VipType 等级排序
    private $_indexs;

    //VipType 包含type类型
    private $_types;

    //查询后的会员信息结果
    private $vip;

    //原始VIP数据
    private $original_vip;

    //以VipType 为key 的VIPKO序列
    private $_type_array = [];

    //错误信息
    private $_error;

    private $_errorcode;

    public $checkLocal = true;

    /**
     * 分析手机号是否存在viptype的会员
     * @param  string $phone 手机号码
     * @param  string $type VIPType
     * @return bool        是否成功
     */
    public function analysis($phone, $type)
    {
        try {
            $this->setPhone($phone);
            $this->setType($type);
            if ($this->checkLocal == true) {
                $local = $this->findAtLocal();
                if (false !== $local) {
                    $this->vip = $local;
                    return true;
                }
                \Yii::info('本地没有找到会员信息，开始到CRM数据库中进行查找');
            }
            $this->getAltId();
            $this->toTypeArray();
            $this->getRuleOfVip();
            return true;
        } catch (Exception $e) {
            $this->_error = $e->getMessage();
            $this->_errorcode = $e->getCode();
            return false;
        }
    }

    /**
     * 设置需要查找的号码
     * @param string $phone 手机号码
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * 设置vipType,检查是否合法
     * @param [type] $type [description]
     */
    public function setType($type)
    {
        $type = strtoupper($type);
        if (false == VipLogic::validateType($type)) {
            throw new Exception("请求会员类型错误", 1003);

        }
        $this->type = $type;
        $this->_indexs = VipLogic::getLevels($type);
        $this->_types = VipLogic::getTypes($type);
        return $this;
    }

    /**
     * 现在本地找，看是有会员数据，若有的话，直接返回，没有，则在CRM数据库中进行查找
     * @return [type] [description]
     */
    public function findAtLocal()
    {
        $local = VipInfos::getByPhone($this->phone, $this->_types);
        if ($local !== false) {
            $ret['vip_code'] = $local['vip_code'];
            $ret['alt_id'] = $local['alt_id'];
            $ret['vip_type'] = $local['vip_type'];
            $ret['join_date'] = $local['join_date'];
            $ret['exp_date'] = $local['exp_date'];
        } else {
            $ret = false;
        }
        return $ret;
    }

    /**
     * 获取手机号下所有的type为$this->type的会员数据
     * @return [type] [description]
     */
    public function getAltId()
    {
        $this->altIds = VPFILE::getByPhone($this->phone, $this->_types);
        if (false == $this->altIds) {
            throw new Exception("该手机号未注册过会员", 1001);
        }
        // \Yii::error($this->altIds);
    }

    /**
     * 将altids 根据viptype为key值，压入 $this->_type_array 数组中
     * @return array
     */
    public function toTypeArray()
    {
        foreach ($this->_types as $key) {
            foreach ($this->altIds as $file) {
                if (trim($file['VIPType']) == $key) {
                    $this->_type_array[$key][] = $file;
                }
            }
        }
        return $this->_type_array;
    }

    /**
     * 获取VIP规则
     * @return bool
     */
    public function getRuleOfVip()
    {
        $tops = false;
        foreach ($this->_indexs as $key) {
            if (isset($this->_type_array[$key])) {
                $tops = $this->_type_array[$key];
                \Yii::info('最高等级为' . $key);
                break;
            }
        }
        if ($tops == false) {
            throw new Exception("没有匹配的会员信息", 1002);
        }
        $member = $tops[0];
        foreach ($tops as $key => $value) {
            $member_date = strtotime(trim($member['JoinDate']));
            $current_date = strtotime(trim($value['JoinDate']));
            if ($current_date >= $member_date) {
                $member = $value;
            }
        }
        Functions::formatBlank($member);
        $this->original_vip = $member;
        $this->vip['vip_code'] = $member['VIPKO'];
        $this->vip['alt_id'] = $member['AltID'];
        $this->vip['vip_type'] = $member['VIPType'];
        $this->vip['join_date'] = $member['JoinDate'];
        $this->vip['exp_date'] = $member['ExpDate'];
        return true;
    }

    /**
     * 获取vip数据信息
     * @return array vip基础数组
     */
    public function getVip()
    {
        return $this->vip;
    }

    /**
     * 获取查询错误信息
     * @return string 错误内容
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * 获取错误代码
     * @return int 错误代码
     */
    public function getCode()
    {
        return $this->_errorcode;
    }

    public function getAltIdasArray()
    {
        $ids = [];
        foreach ($this->altIds as $key => $v) {
            $ids[] = trim($v['AltID']);
        }
        $altids = array_unique($ids);
        return $altids;
    }

    public function getTypes()
    {
        return $this->_types;
    }

    public function getOriginalVip()
    {
        return $this->original_vip;
    }

}
