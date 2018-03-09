<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/13
 * Time: 下午2:21
 */

namespace wechat\models\logic;

use backend\models\SystemConfig;
use common\middleware\Queue;
use common\models\service\TmplMsg;
use EasyWeChat\Url\Url;
use wechat\models\VipBonus;
use wechat\models\VipCheckIn;
use wechat\models\VipCheckInLogs;
use yii;

/**
 * 签到类
 * @package wechat\models\logic
 */
class CheckIn
{
    //判断条件
    private $rules;

    //vip_no
    private $vip_no;

    //签到记录  instance of VipCheckIn
    private $history;

    //clone of VipCheckIn
    private $_old;

    //签到日志  instance of VipCheckInLogs
    private $_log;

    //签到积分
    private $point = 0;

    private $returnstr = '';

    private $brand_id = 1;

    /**
     * 签到入口
     * @throws \Exception
     */
    public function doCheck()
    {
        if (!$this->rules)
            throw new \Exception('规则没有设置');
        $this->getHistory();
    }

    /**
     * 设置Vip_no
     * @param $vip_no
     * @return $this
     */
    public function setVip($vip_no)
    {
        $this->vip_no = $vip_no;
        return $this;
    }

    public function getPoint()
    {
        return $this->point;
    }

    public function getReturn()
    {
        return $this->returnstr;
    }

    /**
     * 获取签到记录
     */
    public function getHistory()
    {
        $this->history = VipCheckIn::findOne(['vip_no' => $this->vip_no]);
        if (!$this->history) {
            return $this->firstCheckIn();
        } else {
            $this->calPoint();
            $this->_old = $this->history;
            return $this;
        }
    }

    /**
     * 计算应当获取的积分
     * @throws \Exception
     */
    public function calPoint()
    {
        if ($this->history->last_check_in == date('Y-m-d')) {
            throw new \Exception(Yii::t('app', '今天已经签到过了哦~连续签到7天即可获赠50积分哦~'));
        }
        if ($this->history->last_check_in == date('Y-m-d', strtotime('-1 day'))) {
            $duration = $this->history->duration;
        } else {
            $duration = 0;
        }
        Yii::error('duration为' . $duration, 'CHECK_IN');
        $base = $this->rules['base'];
        if ($duration == 0) {
            $this->point = $base;
            $this->returnstr = Yii::t('app', '签到成功！已获得') . $this->point . Yii::t('app', '积分，连续签到7天即可获赠50积分哦~');
        } else {
            $config = $this->rules['config'];
            krsort($config);
            foreach ($config as $day => $point) {
                if (($duration + 1) % $day == 0) {
                    $this->point = $base + $point;
                    $this->returnstr = Yii::t('app', '恭喜您已连续签到') . ($duration + 1) . Yii::t('app', '天，本次获赠') . $this->point . Yii::t('app', '积分');
                    break;
                } else {
                    $this->point = $base;
                    $this->returnstr = Yii::t('app', '您已连续签到') . ($duration + 1) . Yii::t('app', '天，本次获赠') . $this->point . Yii::t('app', '积分，连续签到7天即可获赠50积分哦~');
                }
            }
        }
        Yii::error('获得积分' . $this->point, 'CHECK_IN');
        $this->history->last_check_in = date('Y-m-d');
        $this->history->duration = $duration + 1;
        if ($this->history->history < $this->history->duration) {
            $this->history->history = $this->history->duration;
        }
        $this->writeLog();

    }

    /**
     * 通过brand_id来查询积分获取条件
     * @param bool $brand_id
     * @throws \Exception
     */
    public function setRule($brand_id = false)
    {
        if (!$brand_id)
            throw new \Exception('设置品牌ID错误');
        $rule = SystemConfig::getCheckInConfig($brand_id);
        $this->rules = $rule;
        $this->brand_id = $brand_id;
    }

    /**
     * 第一次签到，需要生成签到记录
     */
    private function firstCheckIn()
    {
        //获取到最基础的积分规则
        $this->point = $this->rules['base'];
        //创建一个用户，并插入积分
        $check_in = new VipCheckIn();
        $check_in->vip_no = $this->vip_no;
        $check_in->history = 1;
        $check_in->last_check_in = date('Y-m-d');
        $check_in->duration = 1;
        $this->history = $check_in;
        $this->writeLog();
        $this->returnstr = Yii::t('app', '签到成功！已获得') . $this->point . Yii::t('app', '积分，连续签到7天即可获赠50积分哦~');
        return true;
    }

    /**
     * 写入签到日志
     */
    private function writeLog()
    {
        $log = new VipCheckInLogs();
        $log->vip_no = $this->vip_no;
        $log->check_time = date('Y-m-d H:i:s');
        $log->point = $this->point;
        $this->_log = $log;
    }

    /**
     * 写入到CRM系统中
     * @param $vip_type
     * @param $vb_group
     * @param $vbid
     * @param $exp_date
     * @param $tx_date
     * @param $memo_type
     * @throws \Exception
     */
    public function setCRM($vip_type, $vb_group, $vbid, $exp_date, $tx_date, $memo_type)
    {
        $data = [
            'vip_code' => $this->vip_no,
            'vip_type' => $vip_type,
            'vb_group' => $vb_group,
            'vbid' => $vbid,
            'exp_date' => $exp_date,
            'tx_date' => $tx_date,
            'memo_type' => $memo_type,
            'bp' => $this->point
        ];
        //将添加积分更新到队列中。
//        if (!Record::addBonus($this->vip_no, $vip_type, $vb_group, $vbid, $exp_date, $tx_date, $memo_type, $this->point)) {
        if (!Queue::sendBonus($data)) {
            throw new \Exception('添加积分到队列出错');
        } else {
            $bonus = VipBonus::find()->where(['vip_code' => $this->vip_no])->one();
            $bonus->bonus += $this->point;
            $bonus->update_time = time();
            $bonus->save();
            $this->history->save();
            $this->_log->save();
            if ($this->point > 0) {
                $this->sendNotice($bonus);
            }

        }
    }

    public function sendNotice($bonus)
    {
        $openid = Yii::$app->session->get('openid');
        $tmpl_id = Yii::$app->params['template_id'][$this->brand_id]['check_in'];
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $this->brand_id], true);
        $datas = [
            'first' => '亲爱的会员 ，您的签到积分已到账。',
            'keyword1' => $this->point,
            'keyword2' => $bonus->bonus,
            'remark' => "更多会员权益，请进入“会员中心”查看",
        ];
        TmplMsg::sendMsg($tmpl_id, $openid, $url, $datas);
    }


}