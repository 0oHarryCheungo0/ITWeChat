<?php

namespace wechat\models\logic;

use GuzzleHttp\Client;
use PHPUnit\Runner\Exception;
use wechat\models\VipInfomation;
use wechat\models\WechatVip;
use Yii;

class VipUser
{
    private $_must = [
        'wechat_id',
        'name',
        'sex',
        'brand',
        'type',
        'birthday',
        'phone',
        'email',
        'group',
    ];

    private $_optional = [
        'address' => '',
        'email_sub' => '',
        'interest' => '',
    ];

    private $request_url;

    private $_error = [];

    private $_data = [];

    private $_input;

    private $middleware_date;

    /**
     * 创建vip
     * @return [type] [description]
     */
    public function create($data)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$this->preCheck($data)) {
                return false;
            }
            if ($this->hasError()) {
                return false;
            } else {
                if ($this->requestMiddleware()) {
                    $this->insertVip();
                    $this->setVipInfomation();
                }
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 检查手机号在品牌下是否有会员，如果有的话，不能注册
     * @return [type] [description]
     */
    public function preCheck($data)
    {
        $this->setParams($data);
        if ($this->hasError()) {
            return false;
        }
        $phone = $this->_input['phone'];
        $brand = $this->_input['brand'];
        $isset = WechatVip::find()->where(['phone' => $phone, 'brand' => $brand])->one();
        if ($isset) {
            $this->addError('该手机已经存在会员');
            return false;
        } else {
            return true;
        }
    }

    /**
     * 插入本地数据库，绑定相关信息
     * @return [type] [description]
     */
    public function insertVip()
    {
        $insert_data = [
            'wechat_user_id' => $this->_input['wechat_id'],
            'brand' => $this->_input['brand'],
            'vip_no' => $this->middleware_date['vip_code'],
            'vip_type' => $this->middleware_date['vip_type'],
            'member_id' => $this->middleware_date['alt_id'],
            'join_date' => $this->middleware_date['join_date'],
            'exp_date' => $this->middleware_date['exp_date'],
            'update_time' => date('Y-m-d H:i:s'),
            'phone' => $this->_input['phone'],
            'name' => $this->_input['name'],
            'email' => $this->_input['email'],
            'sex' => $this->_input['sex'],
            'birthday'=>$this->_input['birthday'],
        ];
        $vip = new WechatVip();
        $vip->setAttributes($insert_data);
        if (!$vip->save()) {
            $rs = $vip->getErrors();
            print_r($rs);
            throw new \Exception("存储失败", 1);
        }
    }

    public function setVipInfomation()
    {
        $infomation = [
            'vip_no' => $this->middleware_date['vip_code'],
            'address' => $this->_input['address'],
            'marriage' => $this->_input['marriage'],
            'income' => $this->_input['income'],
            'education' => $this->_input['education'],
            'interest' => json_encode($this->_input['interest']),
            'career' => $this->_input['career'],
        ];
        $info = new VipInfomation();
        $info->setAttributes($infomation);
        if (!$info->save()) {
            throw new \Exception("存储失败", 1);
        } else {
            return true;
        }
    }

    public function setRequestUrl($url)
    {
        $this->request_url = $url;
    }

    public function requestMiddleware()
    {
        $client = new Client();
        try {
            $request = $client->request('POST', $this->request_url, ['json' => $this->_data]);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            Yii::error('请求接口错误', 'middleware');
            return false;
        }
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        if ($ret['code'] == 0) {
            $this->middleware_date = $ret['data'];
            return true;
        } else {
            Yii::error('生成会员失败', 'middleware');
            return false;
        }
    }

    public function getData()
    {
        return $this->_data;
    }

    /**
     * 格式化请求
     * @return [type] [description]
     */
    public function formater()
    {

    }

    public function setParams($data = [])
    {
        $this->_input = $data;
        $this->validate()->setOptional();
    }

    public function validate()
    {
        foreach ($this->_must as $key => $value) {
            if (!isset($this->_input[$value])) {
                $this->addError($value . '为必填');
            } else {
                $this->$value = $this->_input[$value];
            }
        }
        return $this;
    }

    public function setOptional()
    {
        foreach ($this->_optional as $key => $value) {
            if (isset($this->_input[$key])) {
                $this->_data[$key] = $this->_input[$key];
            }
        }
    }

    public function addError($error)
    {
        array_push($this->_error, $error);
    }

    public function hasError()
    {
        return $this->_error ? true : false;
    }

    public function getErrors()
    {
        return $this->_error;
    }

    public function __set($key, $value)
    {
        $this->_data[$key] = $value;
    }

}
