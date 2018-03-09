<?php

namespace wechat\controllers;

use backend\models\SystemConfig;
use common\lib\Barcode;
use common\middleware\Bonus;
use common\middleware\Queue;
use common\middleware\Record;
use common\models\service\TmplMsg;
use common\models\WechatVipsActiveLogs;
use wechat\models\logic\CheckIn;
use wechat\models\service\WechatVipService;
use wechat\models\VipBonus;
use wechat\models\VipInfomation;
use wechat\models\WechatVip;
use wechat\models\logic\News;
use yii;
use yii\helpers\Url;


/**
 * 会员中心首页，所有页面均在这个控制器中实现
 * index 会员中心首页
 * profile 个人资料
 * update_profile 更新会员资料
 * record 消费记录
 * record_detail 消费记录详情
 * offer 专属优惠
 * offer_detail 专属优惠详情
 * 会员权益 跳转到链接https://member.ithk.com/cn/general/howtobeamember_china.jsp
 * @package wechat\controllers
 */
class IndexController extends VipBase
{

    /**
     * 会员中心首页
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = 'HOME';
        $vip = $this->vip;
        //$vip = WechatVip::find()->all();
//      页面跳转URL
        $urls = [ 
            'record' => Url::to(['index/record', 'brand_id' => $this->brand_id]),
            'news' => Url::to(['news/getlist', 'brand_id' => $this->brand_id]),
            'token' => Url::to(['index/token', 'brand_id' => $this->brand_id]),
            'discount' => Url::to(['discount/list', 'brand_id' => $this->brand_id]),
            'profile' => Url::to(['index/profile', 'brand_id' => $this->brand_id]),
            'check' => Url::to(['index/check-in', 'brand_id' => $this->brand_id]),
            'benefits' => Url::to(['discount/profile', 'brand_id' => $this->brand_id]),
        ];
//      个人资料百分比的统计
        $profile_percent = VipInfomation::getPercentage($vip, $vip->info);

//      未读消息数量
//        $messages = $this->getCache($this->brand_id . $vip->vip_no . 'message');
//        if ($messages === false) {
        $messages = News::getTotal(Yii::$app->session->get('wechat_id'));
//            $this->cache($this->brand_id . $vip->vip_no . 'message', $messages);
//        }
//      会员积分信息
        $points = $this->getCache($this->brand_id . $vip->vip_no . 'points');
        if (!$points) {
            $points = WechatVipService::getBonus($vip->vip_no);
            $this->cache($this->brand_id . $vip->vip_no . 'points', $points);
        }
//      会员条形码  base64_encode 格式
        $barcode = $this->getCache($this->brand_id . $vip->vip_no . 'barcode');
        if (!$barcode) {
            $barcode = Barcode::base64string($vip->vip_no);
            $this->cache($this->brand_id . $vip->vip_no . 'barcode', $barcode, 0);
        }
        //      控制器传入参数
        $assign = [
            'barcode' => $barcode,
            'messages' => $messages,
            'points' => $points,
            'urls' => $urls,
            'vip' => $vip,
            'profile_percent' => $profile_percent
        ];
        $renewkey = 'wechatvip' . $vip->vip_no . 'renew';
        $cache_time = $this->getCache($renewkey);
        if (strtotime($this->vip->bind_time) < (time() - 30)) {
            if (!$cache_time || time() - $cache_time > 1800) {
                //Queue::renewInfo($vip->vip_no);
                $this->cache($renewkey, time());
            }
        } else {
            Yii::error('新建会员30秒内暂时不刷新会员资料');
        }


        return $this->render('index', $assign);
    }


    /**
     * 会员个人资料
     * @return string
     */
    public function actionProfile()
    {
        $this->view->title = 'PERSONAL';
        $vip = WechatVip::findOne([
            'wechat_user_id' => Yii::$app->session->get('wechat_id'),
            'brand' => $this->brand_id,
        ]);
        $home_page = Url::to(['index/index', 'brand_id' => $this->brand_id]);
        $update_url = Url::to(['index/update-profile', 'brand_id' => $this->brand_id]);
        $assign = [
            'update_url' => $update_url,
            'vip' => $vip,
            'home_page' => $home_page,
            'error_mail' => Yii::t('app', '请输入正确的邮箱地址'),
            'allow_rule' => Yii::t('app', '请阅读并接受《I.T个人资料（私隐）政策声明,测试测试...》'),
        ];
        return $this->render('profile', $assign);
    }

    /**
     * 会员资讯
     * @return string
     */
    public function actionNews()
    {
        $this->view->title = 'NEWS';
        $urls = [
            'news' => Url::to(['index/news', 'brand_id' => $this->brand_id]),
            'point-news' => Url::to(['index/point-news', 'brand_id' => $this->brand_id]),
        ];
        $assign = [
            'urls' => $urls,
        ];
        return $this->render('news', $assign);
    }

    /**
     * 积分资讯
     * @return string
     */
    public function actionPointNews()
    {
        $urls = [
            'news' => Url::to(['index/news', 'brand_id' => $this->brand_id]),
            'point-news' => Url::to(['index/point-news', 'brand_id' => $this->brand_id]),
        ];
        $assign = [
            'urls' => $urls,
        ];
        return $this->render('pointnews', $assign);
    }

    /**
     * 获得积分明细
     * @return string
     */
    public function actionToken()
    {
        $this->view->title = 'I.TOKEN';
        $vip_no = $this->vip->vip_no;
        $urls = [
            'token' => Url::to(['index/token', 'brand_id' => $this->brand_id]),
            'token-out' => Url::to(['index/token-out', 'brand_id' => $this->brand_id]),
        ];
        $token = Bonus::get($vip_no);
        $spend = Bonus::spend($vip_no);
        $points = WechatVipService::getBonus($vip_no);
        $assign = [
            'urls' => $urls,
            'token' => $token,
            'points' => $points,
            'spend' => $spend,
        ];
        return $this->render('token', $assign);
    }

    /**
     * 更新个人资料
     * @return string;
     */
    public function actionUpdateProfile()
    {
        $datas = Yii::$app->request->post();
        $vip_no = $this->vip->vip_no;
        /** @var WechatVip $vip */
        $vip = WechatVip::find()->where(['vip_no' => $vip_no])->one();
        $vips = WechatVip::find()->where(['member_id' => $vip->member_id])->all();
        foreach ($vips as $_vip) {
            $_vip->setAttributes($datas);
            $_vip->update_time = date('Y-m-d H:i:s');
            $_vip->save();
            if ($_vip->vip_no == $vip_no) {
                //取已经保存过的vip的值，避免更新vip失败;
                $vip = $_vip;
            }
        }

        /** @var VipInfomation $infos */
        $infos = VipInfomation::find()->where(['altid' => $vip->member_id])->one();
        if (isset($datas['interest'])) {
            $datas['interest'] = implode(',', $datas['interest']);
        } else {
            $datas['interest'] = '';
        }
        $infos->setAttributes($datas);
        if ($infos->save()) {
//            Yii::$app->session->set('vip', $vip);
            $key = 'brand_id' . $this->brand_id . 'wechat_id' . Yii::$app->session->get('wechat_id');
            $this->cache($key, false);
            //Queue::updateVip($vip_no);
            $vip = WechatVip::find()->where(['vip_no' => $vip_no])->one();
            if (VipInfomation::canGetBonus($vip, $infos) === true) {
                Bonus::profileAdd($this->brand_id, $vip_no);
                $this->profileFinishSend();
                $this->cache($this->brand_id . $vip->vip_no . 'points', false);
                return $this->response('', 100, Yii::t('app', '您已完成所有资料填写，恭喜获赠100积分。'));
            } else {
                if (!WechatVipsActiveLogs::find()->where(['types' => 2, 'vip_code' => $vip->vip_no])->one()) {
                    return $this->response('', 101, Yii::t('app', '更新成功！完成所有资料填写就能获赠100积分哦~ 真的要现在离开吗？'));
                } else {
                    return $this->response();
                }

            }
        } else {
            return $this->response($vip->getErrors(), 400, '修改错误');
        }
    }

    /**
     * 消费记录
     * @return string
     */
    public function actionRecord()
    {
        $this->view->title = 'RECORD';
        $vip_no = $this->vip->vip_no;
        $key = $vip_no . 'record';
        $logs = $this->getCache($key);
        if ($logs === false) {
            $logs = Record::getRecord($vip_no);
            $this->cache($key, $logs, 20);
        }
        $assign['logs'] = $logs;
        $assign['home'] = Url::to(['index/index', 'brand_id' => $this->brand_id]);
        return $this->render('record', $assign);
    }


    /**
     * 消费记录详情
     * @return string
     */
    public function actionRecordDetail()
    {
        $this->view->title = 'DETAIL';
        $spend_id = Yii::$app->request->get('id');
        $assign['detail'] = Record::getRecordById($spend_id);
        return $this->render('recorddetail', $assign);
    }


    public function actionRecordDetail2()
    {
        $this->view->title = 'DETAIL';
        $spend_id = Yii::$app->request->get('id');
        $assign['detail'] = Record::getRecordById($spend_id);
        return $this->render('recorddetail2', $assign);
    }


    /**
     * 优惠信息
     * @return string
     */
    public function actionOffer()
    {
        $this->view->title = 'OFFER';
        return $this->render('offer');
    }

    /**
     * 优惠信息详情
     * @return string
     */
    public function actionOfferDetail()
    {
        $this->view->title = '优惠详情';
        return $this->render('offerDetail');
    }

    public function actionOut()
    {
        $this->resetSessions();
    }


    /**
     * 用户每日签到管理
     * @return string
     */
    public function actionCheckIn()
    {
        $brand_config = SystemConfig::getBonusConfig($this->brand_id, 0);

        if ($brand_config['exp'] == 0) {
            $exp_date = '2099-12-31 00:00:00';
        } else {
            $later = $brand_config['exp'] + 1;
            $str = '+' . $later . ' month';
            $more_1day_month = date('Y-m', strtotime($str) - 1);
            $exp_date = date('Y-m-d', strtotime($more_1day_month) - (60 * 60 * 24));
        }
        $vbid = $brand_config['vb_prefix'];
        try {
            $check = new CheckIn();
            $check->setVip($this->vip->vip_no);
            $check->setRule($this->brand_id);
            $check->doCheck();
            $check->setCRM($this->vip->vip_type, $brand_config['vbgroup'], $vbid, $exp_date, date('Y-m-d'), 'SS');
            $this->cache($this->brand_id . $this->vip->vip_no . 'points', null);
            $result = ['point' => $check->getPoint(), 'str' => $check->getReturn()];
            return $this->response($result);
        } catch (\Exception $e) {
            return $this->response('', 500, $e->getMessage());
        }
    }

    public function profileFinishSend()
    {
        $openid = Yii::$app->session->get('openid');
        $tmpl_id = Yii::$app->params['template_id'][$this->brand_id]['profile'];
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $this->brand_id], true);
        /** @var VipBonus $bonus */
        $bonus = VipBonus::find()->where(['vip_code' => $this->vip->vip_no])->one();
        $datas = [
            'first' => '亲爱的会员 ，您的个人资料已完善成功。',
            'keyword1' => $this->vip->vip_no,
            'keyword2' => "100",
            'keyword3' => $bonus->bonus,
            'keyword4' => '完善资料获赠积分',
            'remark' => "更多会员权益，请进入“会员中心”查看",
        ];
        TmplMsg::sendMsg($tmpl_id, $openid, $url, $datas);
    }

}
