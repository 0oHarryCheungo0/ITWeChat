<?php

namespace wechat\controllers;

use backend\service\fans\FansList;
use common\api\NewsApi;
use common\api\SMS;
use common\middleware\Bonus;
use common\middleware\User;
use common\models\service\TmplMsg;
use common\models\WechatSlider;
use wechat\models\logic\NewVip;
use wechat\models\logic\SmsLogic;
use wechat\models\logic\VipBind;
use wechat\models\logic\VipPrizeCheck;
use wechat\models\service\VipCreater;
use wechat\models\VipInfomation;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use wechat\models\WechatRegister;
use Yii;
use yii\helpers\Url;

class LoginController extends WechatBase
{
    public function actionIndex()
    {
        if ($this->checkVip()) {
            return $this->redirect(['index/index', 'brand_id' => $this->brand_id]);
        }

        $homeUrl  = Url::to(['index/index', 'brand_id' => $this->brand_id]);
        $callback = Yii::$app->request->get('callback', false);
        if ($callback) {
            $openid = Yii::$app->session->get('openid');
            if (strpos($callback, '?')) {
                $callback = $callback . "&openid=" . $openid;
            } else {
                $callback = $callback . "?openid=" . $openid;
            }
            $homeUrl = $callback;
        }

        $lang   = Yii::$app->session->get('lang');
        $slider = WechatSlider::find()
            ->where(['brand_id' => $this->brand_id, 'status' => 1])
            ->orderBy('indexs desc')
            ->all();
        if (!$slider) {
            $slider = [];
        }
        $this->getView()->title = 'SIGN UP';
        $config['reg_url']      = Url::to(['reg', 'brand_id' => $this->brand_id]);
        $config['login_url']    = Url::to(['new-login', 'brand_id' => $this->brand_id]);
        $config['vip_home']     = $homeUrl;
        $config['code_url']     = Url::to(['get-code', 'brand_id' => $this->brand_id]);
        $config['lang_url']     = Url::to(['set-lang', 'brand_id' => $this->brand_id]);
        $config['slider']       = $slider;
        $config['lang']         = $lang;
        return $this->render('index', ['config' => $config]);
    }

    public function actionNewLogin()
    {
        $phone = Yii::$app->request->post('phone');
        $code  = Yii::$app->request->post('code');
        $area  = Yii::$app->request->post('area');
        if ($code == 0) {
            $ret = 200;
        } else {
            $ret = $this->VerifyCode($phone, $code);
        }
        $message = '短信验证成功';
        switch ($ret) {
            case 200:
                $message = '短信验证成功';
                break;
            case 300:
            case 301:
            case 302:
                $message = Yii::t('app', '手机号码不存在，或者验证码已过期');
                break;
        }
        if ($ret != 200) {
            return $this->response('', $ret, $message);
        }
        if ($this->brand_id == 1) {
            $type = 'BIT';
        } else {
            $type = 'SIT';
        }

        $click = Yii::$app->session->get('click');
        if ($click) {
            Yii::error('请求太频繁');
            return $this->response(302, '请求太频繁，请稍后再试');
        } else {
            Yii::$app->session->set('dologin', 20);
        }
       $wechat_id =  Yii::$app->session->get('wechat_id');

        $validate = $this->validateRegister($wechat_id,$phone);
        if ($validate == false){
            return $this->response('请勿重复注册',302,'请勿重复注册');
        }

       $is_bind = WechatVip::find()->where(['phone'=>$phone,'brand'=>$this->brand_id,'wechat_user_id'=>$wechat_id])->one();
       if ($is_bind){
           return $this->response(302,'此手机号码已存在会员');
       }
        $middleware = User::findUser($phone, $type, $area);
        if ($middleware === false) {
            Yii::error('=======请求错误====');
            return $this->response(302, '请求错误，请稍后再试');
        }
        if ($middleware['code'] === 0) {
            $is_old = 0;
            $msg    = Yii::t('app', '恭喜您已成功绑定，并获得200积分；完善个人资料即可再获赠100积分哦！');
            $data   = $middleware['data'];
            if (User::bindVip($data['vip'], $data['profile'], $this->brand_id)) {
                $check = VipPrizeCheck::check($data['vip']['VIPKO']);
                if ($check['bind'] == 1 && $check['profile'] == 1) {
                    $is_old = 1;
                    $msg    = Yii::t('app', '恭喜您已成功绑定！');
                } else if ($check['bind'] == 1 && $check['profile'] == 0) {
                    $is_old = 0;
                    $msg    = Yii::t('app', '恭喜您已成功绑定，完善个人资料即可再获赠100积分哦！');
                }
                /** @var WechatVip $local */
                $local = WechatVip::find()->where(['brand' => $this->brand_id, 'vip_no' => $data['vip']['VIPKO']])->one();
                $this->bindAction(Yii::$app->session->get('openid'), $local->vip_no, $local->name, $local->phone, $local->exp_date, $is_old);
                return $this->response('', 0, $msg);
            } else {
                return $this->response('', 401, "绑定会员失败");
            }
        } else {
            //新建会员
            $create = User::create($phone, $this->brand_id);
            if ($create) {
                //创建会员成功
                /** @var WechatVip $local */
                $local = WechatVip::find()->where(['brand' => $this->brand_id, 'vip_no' => $create->vip->VIPKO])->one();
                $msg   = Yii::t('app', '恭喜您已成功注册，并获得200积分；完善个人资料即可再获赠100积分哦！');
                $this->regAction(Yii::$app->session->get('openid'), $local->vip_no, $local->name, $local->phone, $local->exp_date);
                return $this->response('', 201, $msg);
            } else {
                return $this->response('', 402, '注册会员失败');
            }
        }

    }

    public function actionDoLogin()
    {
        $phone = Yii::$app->request->post('phone');
        $code  = Yii::$app->request->post('code');
//        $area = Yii::$app->request->post('area');
        if ($code == 0) {
            $ret = 200;
        } else {
            $ret = $this->VerifyCode($phone, $code);
        }
        switch ($ret) {
            case 200:
                $message = '短信验证成功';
                break;
            case 300:
            case 301:
            case 302:
                $message = Yii::t('app', '手机号码不存在，或者验证码已过期');
                break;
            default:
                $message = '短信验证成功';
                break;
        }
        if ($ret != 200) {
            return $this->response('', $ret, $message);
        }
        if ($this->brand_id == 1) {
            $type = 'BIT';
        } else {
            $type = 'SIT';
        }
        $find = User::findByPhone($phone, $type);
        if ($find === false) {
            Yii::error('=======请求错误====');
            return $this->response(302, '请求错误，请稍后再试');
        }
        if ($find['code'] == 1001) {
            //手机号没有会员信息，立即创建一个
            $vip = new NewVip($this->brand_id);
            $vip->setPhone($phone);
            $vip->setWechatInfo(Yii::$app->session->get('wechat_id'));
            $vip_no = $vip->create();
            if ($vip_no !== false) {
                $store_id = \backend\service\fans\FansList::bindMember(Yii::$app->session->get('openid'), 0);
                VipBind::updateBind($vip_no, $this->brand_id, $store_id);
                \common\api\NewsApi::trigger(Yii::$app->session->get('wechat_id'), $this->brand_id);
                $msg = Yii::t('app', '恭喜您已成功注册，并获得200积分；完善个人资料即可再获赠100积分哦！');
                return $this->response('', 201, $msg);
            } else {
                return $this->response('', 500, '注册会员失败');
            }
        } else {
            try {
                $is_old    = 0;
                $msg       = Yii::t('app', '恭喜您已成功绑定，并获得200积分；完善个人资料即可再获赠100积分哦！');
                $find_data = $find['data'];
                //先找本地有没有
                /** @var WechatVip $local_has */
                $local_has = WechatVip::find()->where(
                    [
                        'vip_no' => $find_data['vip_code'],
                        'brand'  => $this->brand_id,
                    ]
                )
                    ->one();
                $check = VipPrizeCheck::check($find_data['vip_code']);
                if ($check['bind'] == 1 && $check['profile'] == 1) {
                    $is_old = 1;
                    $msg    = Yii::t('app', '恭喜您已成功绑定！');
                } else if ($check['bind'] == 1 && $check['profile'] == 0) {
                    $msg    = Yii::t('app', '恭喜您已成功绑定，完善个人资料即可再获赠100积分哦！');
                    $is_old = 1;
                }
                if ($local_has) {
                    //检查
                    if ($local_has->wechat_user_id != Yii::$app->session->get('wechat_id')) {
                        $local_has->wechat_user_id = Yii::$app->session->get('wechat_id');
                        $local_has->save();
                    }
                    $this->bindAction(Yii::$app->session->get('openid'), $local_has->vip_no, $local_has->name . " " . $local_has->name_last, $local_has->phone, $local_has->exp_date, $is_old);
                    return $this->response('', 0, $msg);
                }
                $info = User::getInfo($find_data['vip_code']);
                //获取到数据，本地添加一份
                $insert['wechat_user_id'] = Yii::$app->session->get('wechat_id');
                $insert['brand']          = $this->brand_id;
                $insert['member_id']      = $find_data['alt_id'];
                $insert['vip_no']         = $find_data['vip_code'];
                $insert['vip_type']       = $find_data['vip_type'];
                $insert['join_date']      = $find_data['join_date'];
                $insert['exp_date']       = $find_data['exp_date'];
                $insert['phone']          = $info['telno'];
                $insert['email']          = $info['email'];
                $insert['name']           = $info['name'];
                $insert['birthday']       = $find_data['birthday'];
                $insert['sex']            = $info['sex'] == 'M' ? 0 : 1;
                $model                    = new WechatVip();
                $model->setAttributes($insert);
                if ($model->save()) {
                    //基础消息也需要添加
                    if (!VipInfomation::find()->where(['vip_no' => $find_data['vip_code']])->one()) {
                        $vinfo             = new VipInfomation();
                        $vinfo->vip_no     = $find_data['vip_code'];
                        $vinfo->address    = $info['addr1'];
                        $vinfo->interest   = json_encode([]);
                        $vinfo->send_point = 0;
                        if ($vinfo->save()) {
                            Yii::error($vinfo->getErrors());
                        }
                    }
                    $this->bindAction(Yii::$app->session->get('openid'), $insert['vip_no'], $insert['name'], $insert['phone'], $insert['exp_date']);
                    return $this->response('', 0, $msg);
                } else {
                    return $this->response($model->getErrors(), 300, 'error');
                }
            } catch (\Exception $e) {
                return $this->response($e->getMessage(), 500, '系统错误');
            }

        }
    }

    public function actionGetCode()
    {
        $phone = Yii::$app->request->post('phone');
        $area  = Yii::$app->request->post('area');
        switch ($area) {
            case '香港':
                $type  = SMS::HK;
                $phone = "852" . $phone;
                break;
            case '台湾':
                $type  = SMS::TW;
                $phone = "886" . $phone;
                break;
            case '澳门':
                $type  = SMS::AM;
                $phone = "853" . $phone;
                break;
            default:
                $type = SMS::CHINA;
                break;
        }

        $code        = mt_rand(1000, 9999);
        $model       = new SmsLogic();
        $return_code = $model->save($phone, $code, $this->brand_id, 300, $type);
        $message     = '';
        switch ($return_code) {
            case 200:
                $message = '发送成功';
                break;
            case 300:
                $message = '手机号码已使用';
                break;
            case 100:
                $message = '发送失败';
                break;
        }
        $this->response($message, $return_code);
    }

    public function VerifyCode($phone, $code)
    {

        return SmsLogic::verifyPhone($phone, $code);

    }

    public function actionSetLang()
    {
        $lang       = Yii::$app->request->post('lang');
        $user       = WechatUser::findOne(Yii::$app->session->get('wechat_id'));
        $user->lang = $lang;
        $user->save();
        Yii::$app->session->set('lang', $lang);
        $this->response();
    }

    public function actionCheck()
    {

        $this->response('', 1, '没有手机号码信息');
    }

    public function actionReg()
    {
        $phone = Yii::$app->request->get('phone');
        if (!$phone) {
            return $this->redirect(['login/index', 'brand_id' => $this->brand_id]);
        }
        if ($this->checkVip()) {
            return $this->redirect(['index/index', 'brand_id' => $this->brand_id]);
        }
        if ($this->brand_id == 1) {
            $base_info = ['vip_type' => 'CN_BITT0', 'phone' => $phone];
        } else {
            $base_info = ['vip_type' => 'CN_SITT0', 'phone' => $phone];
        }

        $reg_url  = Url::to(['reg-vip', 'brand_id' => $this->brand_id]);
        $vip_home = Url::to(['index/index', 'brand_id' => $this->brand_id]);
        return $this->render('reg', ['reg_url' => $reg_url, 'base_info' => $base_info, 'vip_home' => $vip_home]);
    }

    /**
     * 注册成为会员
     * 1.本地创建记录，wechat_vip ，vip_infomation
     * 2.将打包好的数据传输给中间件
     * 3.中间件返回内容包括vip_no,member_id
     * 4.将vip_no,member_id更新到新增记录中。
     * @return string [description]
     */
    public function actionRegVip()
    {
        if (!Yii::$app->request->isAjax) {
            $this->response('', 500, '请求方式错误');
        }
        $post = Yii::$app->request->post();
        try {
            $creater = new VipCreater($post);
            $creater->getConfig($this->brand_id)
                ->setWechatId(Yii::$app->session->get('wechat_id'))
                ->setBrand($this->brand_id)
                ->setRequestUrl(Yii::$app->params['middleware_reg']);
            $creater->buildPost()->createVip();
            $this->response();
        } catch (\Exception $e) {
            $this->response('', 500, $e->getMessage());
        }
        return false;
    }

    private function bindAction($openid, $vip_code, $name, $phone, $exp_date, $is_old = 0)
    {
        $store_id = FansList::bindMember(Yii::$app->session->get('openid'), 1);
        Yii::error('这个用户绑定的store_id为' . $store_id);
        if ($store_id) {
            $vip           = WechatVip::find()->where(['vip_no' => $vip_code])->one();
            $vip->store_id = $store_id;
            $vip->save();
        }
        NewsApi::trigger(Yii::$app->session->get('wechat_id'), $this->brand_id);
        Bonus::regAdd($this->brand_id, $vip_code);
        if ($is_old == 0) {
            $tips = "亲爱的会员，您已成功绑定会员卡，并获赠200积分。";
        } else {
            $tips = '亲爱的会员，您已成功绑定会员卡。';
        }
        $tmpl_id = Yii::$app->params['template_id'][$this->brand_id]['bind'];
//        $url = Url::to(['index/index', 'brand_id' => $this->brand_id], true);
        $url = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn&utm_medium=social&utm_campaign=wechatvipbinding';
        if ($this->brand_id == 2) {
            $type = 'SIT';
            $url  = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn_SIT&utm_medium=social&utm_campaign=wechatvipbinding';
        } else {
            // $url = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn_BIT&utm_medium=social&utm_campaign=wechatvipbinding';
            $url = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn_BIT&utm_medium=social&utm_campaign=wechatvipbinding';
        }

        if ($this->brand_id == 2) {
            $datas = [
                'first'    => $tips,
                'keyword1' => $vip_code,
                'keyword2' => date("Y-m-d"),
//            'remark' => "登记姓名：{$name}\n登记手机号：{$phone}\n有效期：{$exp_date}\n请进入”会员中心“完善会员资料，领取积分奖励",
                'remark'   => "登记姓名：{$name}\n登记手机号：{$phone}\n有效期：{$exp_date}\n点击注册成为ITeSHOP会员 , 快人一步紧贴官方商城最新优惠及活动资讯!
",
            ];

        } else {
            $datas = [
                'first'    => $tips,
                'keyword1' => $vip_code,
                'keyword2' => date("Y-m-d"),
//            'remark' => "登记姓名：{$name}\n登记手机号：{$phone}\n有效期：{$exp_date}\n请进入”会员中心“完善会员资料，领取积分奖励",
                'remark'   => "登记姓名：{$name}\n登记手机号：{$phone}\n有效期：{$exp_date}\n点击注册成为ITeSHOP会员 , 快人一步紧贴官方商城最新优惠及活动资讯!
",
            ];

        }

        TmplMsg::sendMsg($tmpl_id, $openid, $url, $datas);
    }

    private function regAction($openid, $vip_code, $name, $phone, $exp_date)
    {
        $store_id = FansList::bindMember(Yii::$app->session->get('openid'), 0);
        Yii::error('这个用户绑定的store_id为' . $store_id);
        if ($store_id) {
            $vip           = WechatVip::find()->where(['vip_no' => $vip_code])->one();
            $vip->store_id = $store_id;
            $vip->save();
        }
        NewsApi::trigger(Yii::$app->session->get('wechat_id'), $this->brand_id);
        Bonus::regAdd($this->brand_id, $vip_code);
        $tmpl_id = Yii::$app->params['template_id'][$this->brand_id]['reg'];
//        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $this->brand_id], true);
        $url  = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn&utm_medium=social&utm_campaign=wechatvipbinding';
        $type = 'IT';
        if ($this->brand_id == 2) {
            $type = 'SIT';
            $url  = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn_SIT&utm_medium=social&utm_campaign=wechatvipbinding';
        } else {
            // $url = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn_BIT&utm_medium=social&utm_campaign=wechatvipbinding';
            $url = 'https://member-cn.iteshop.com/member/register?ts=wechatvipbinding&utm_source=wechat_cn_BIT&utm_medium=social&utm_campaign=wechatvipbinding';
        }

        if ($this->brand_id == 2) {
            $datas = [
                'first'      => '亲爱的会员，您已成功注册会员卡，并获赠200积分。',
                'cardNumber' => $vip_code,
                'address'    => $type,
                'VIPName'    => $name,
                'VIPPhone'   => $phone,
                'expDate'    => date('Y-m-d', strtotime($exp_date)),
//            'remark' => '请进入”会员中心“完善会员资料，领取积分奖励',
                'remark'     => '点击注册成为ITeSHOP会员 , 快人一步紧贴官方商城最新优惠及活动资讯!
',
            ];
        } else {
            $datas = [
                'first'      => '亲爱的会员，您已成功注册会员卡，并获赠200积分。',
                'cardNumber' => $vip_code,
                'address'    => $type,
                'VIPName'    => $name,
                'VIPPhone'   => $phone,
                'expDate'    => date('Y-m-d', strtotime($exp_date)),
//            'remark' => '请进入”会员中心“完善会员资料，领取积分奖励',
                'remark'     => '点击注册成为ITeSHOP会员 , 快人一步紧贴官方商城最新优惠及活动资讯!
',
            ];
        }

        TmplMsg::sendMsg($tmpl_id, $openid, $url, $datas);
    }

    public function validateRegister($wechat_user_id ,$phone ){

        $expire_time = time()-300;

        $result = WechatRegister::find()->where(['wechat_user_id'=>$wechat_user_id,'phone'=>$phone])->andWhere(['>','create_time',$expire_time])->one();

        if (!empty($result)){

            \Yii::error('用户在五分钟之内存在注册行为不让通过'.$phone);
            return false;
        }else{

            $model = new WechatRegister();
            $model->wechat_user_id = $wechat_user_id;
            $model->phone = $phone;
            $model->create_time = time();
            $is_save = $model->save();

            if ($is_save){
                \Yii::error('新用户注册成功'.$phone);
                return true;
            }else{
                \Yii::error('系统数据注册异常'.$phone);
                return false;
            }
        }
    }

    public function actionTest(){
        return $this->renderPartial('test');
    }
}
