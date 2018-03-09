<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/20
 * Time: 上午11:05
 */

namespace wechat\controllers;

use backend\models\Store;
use backend\models\WechatGroupMessage;
use backend\models\WechatGroupVips;
use backend\service\discount\DiscountForm;
use backend\service\news\NewsLogic;
use common\api\BrandApi;
use common\middleware\Queue;
use common\middleware\Record;
use common\middleware\User;
use common\models\WechatGroups;
use common\models\WechatResource;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use GuzzleHttp\Client;
use wechat\models\logic\DiscountLogic;
use wechat\models\logic\NewsPointLogic;
use wechat\models\service\WechatVipService;
use wechat\models\VipBonus;
use wechat\models\VipInfomation;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use yii;
use yii\web\Controller;

class QueueController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionBonus()
    {
        $data = Yii::$app->request->post();
        if ($data) {
            $rs = Record::addBonus($data);
            if ($rs) {
                Yii::error('处理积分写入成功');
                sleep(5);
                //插入成功3秒后请求同步积分记录表;
                Queue::syncBonus();
            } else {
                Yii::error('处理积分失败');
            }
        }

    }

    /**
     * 发布积分资讯
     * @return [type] [description]
     */
    public function actionHandle()
    {
        $id = Yii::$app->request->get('id');
        $models = new NewsLogic();
        $models->send($id);
    }

    /**
     * 发布专属优惠
     * @return [type] [description]
     */
    public function actionDiscount()
    {
        $id = Yii::$app->request->get('id');
        Yii::info('执行发布限时优惠');
        $model = new DiscountForm();
        $model->send($id);
        Yii::info('发布限时优惠成功');
    }

    /**
     * 信息初始化
     * @return [type] [description]
     */
    private function check($uid, $brand_id)
    {
        $news = new \wechat\models\logic\News();
        $logic = new DisCountLogic();
        $result = WechatVip::find()->where(['wechat_user_id' => $uid, 'brand' => $brand_id])->asArray()->one();

        $vip_type = $result['vip_type'];
        $expire_date = $result['exp_date'];
        $birthday = $result['birthday'];
        $rank = $news->getMemberRank($vip_type, $brand_id);
        $change = $news->checkVipChange($vip_type, $brand_id, $uid);
        if ($change) {
            $news->getUserRank($uid, $brand_id, $rank);
            $logic->memberDiscount($uid, $brand_id, $rank);
        }
        $news->memberExpire($uid, $brand_id, $vip_type, $expire_date);
        $point = new NewsPointLogic();
        //签到赢取积分
        $point->sign($uid, $brand_id);
        $point->prefected($uid, $brand_id);
        $logic->checkBirth($uid, $brand_id, $vip_type, $birthday);
    }

    public function actionSign()
    {
        $brand_id = Yii::$app->request->get('brand_id', 1);
        $result = WechatVip::find()->where(['brand' => $brand_id])->asArray()->all();
        $point = new NewsPointLogic();
        //签到赢取积分
        foreach ($result as $k => $v) {
            $point->sign($v['vip_no'], $brand_id);
        }

    }

    public function actionPrefect()
    {
        $brand_id = Yii::$app->request->get('brand_id', 1);
        $result = WechatVip::find()->where(['brand' => $brand_id])->asArray()->all();
        $point = new NewsPointLogic();
        //完善资料赢取积分
        foreach ($result as $k => $v) {

            $point->prefected($v['vip_no'], $brand_id);
        }
    }

    /**
     * 系统初始化
     * @return [type] [description]
     */
    public function actionReset()
    {
        $brand_id = Yii::$app->request->get('brand_id');
        $ret = WechatVip::find()->where(['brand' => $brand_id])->all();
        foreach ($ret as $k => $v) {
            $this->check($v->wechat_user_id, $brand_id);
        }
    }

    /**
     * 发布等级优惠
     * @return [type] [description]
     */
    public function actionRelase()
    {
        $news_id = Yii::$app->request->get('news_id');
        $vip_no = Yii::$app->request->get('vip_no');
        $brand_id = Yii::$app->request->get('brand_id');
        $result = DisCountLogic::sendRank($brand_id, $vip_no, $news_id);
    }

    /**
     * 发布生日月优惠
     * @return [type] [description]
     */
    public function actionBirthday()
    {
        $news_id = Yii::$app->request->get('news_id');
        $vip_no = Yii::$app->request->get('vip_no');
        $brand_id = Yii::$app->request->get('brand_id');
        $result = DisCountLogic::sendBirth($brand_id, $vip_no, $news_id);
    }

    /**
     * 发布等级资讯
     * @return [type] [description]
     */
    public function actionNewsrank()
    {
        $news_id = Yii::$app->request->get('news_id');
        $vip_no = Yii::$app->request->get('vip_no');
        $brand_id = Yii::$app->request->get('brand_id');
        $result = \wechat\models\logic\News::sendNews($brand_id, $vip_no, $news_id);

    }

    public function actionNewsexpire()
    {
        $news_id = Yii::$app->request->get('news_id');
        $vip_no = Yii::$app->request->get('vip_no');
        $brand_id = Yii::$app->request->get('brand_id');
        $result = \wechat\models\logic\News::sendExpireNews($brand_id, $vip_no, $news_id);

    }

    public function actionGroupSender()
    {
        $send_id = Yii::$app->request->post('send_id');
        $message = WechatGroupMessage::findOne($send_id);
        $group = WechatGroups::findOne($message->group_id);
        $success = $error = 0;
        if ($message->status == 1) {
            $brand_config = BrandApi::getBrandById($group->brand_id);
            $wechat = Yii::$app->wechat->app($brand_config);
            $groups = WechatGroupVips::findAll(['group_id' => $message->group_id]);
            if ($message->msg_type == 0) {
                $content = new Text(['content' => $message->msg_text]);

            } else {
                $ids = json_decode($message->resource_ids, true);
                if (!$ids) {
                    return '';
                }
                $resources = WechatResource::find()
                    ->where(['status' => 1])
                    ->andWhere(['in', 'id', $ids])
                    ->all();
                foreach ($resources as $resource) {
                    $content[] = new News([
                        'title' => $resource->title,
                        'description' => $resource->description,
                        'url' => $resource->url,
                        'image' => $resource->image,
                    ]);
                }
            }
            foreach ($groups as $user) {
                try {
                    $wechat->staff->message($content)->to($user->openid)->send();
                    $success++;
                } catch (\Exception $e) {
                    Yii::error('发送错误' . $e->getMessage(), 'ERROR');
                    $error++;
                }
            }
        }
        $message->success_num = $success;
        $message->fail_num = $error;
        $message->finish_date = date('Y-m-d H:i:s');
        $message->status = 2;
        $message->save();
    }

    public function actionUpdateVip()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        /** @var WechatVip $vip */
        $vip = WechatVip::find()->where(['vip_no' => $vip_code])->with('info', 'wechat')->asArray()->one();
        $params = [
            'vip_code' => $vip_code,
            'name' => $vip['name'],
            'sex' => $vip['sex'] == 1 ? 'M' : 'F',
            'addr1' => $vip['info']['addr1'],
            'email' => $vip['email'],
            'alt_id' => $vip['member_id'],
            'birthday' => $vip['birthday'],
            'W_FNAME' => $vip['name_first'],
            'W_LNAME' => $vip['name_last'],
            'W_COUNTRY' => $vip['info']['area'],
            'W_PROVINCE' => $vip['info']['province'],
            'W_CITY' => $vip['info']['city'],
            'W_DISTRICT' => $vip['info']['town'],
            'W_DTL_ADDR' => $vip['info']['addr1'],
            'W_SALARY' => $vip['info']['income'],
            'W_OCCUPATION' => $vip['info']['career'],
            'W_EDUCATION' => $vip['info']['education'],
            'W_INTERESTS' => $vip['info']['interest'],
            'WECHAT_ID' => $vip['wechat']['openid'],
            'W_M_STATUS' => $vip['info']['marriage'],
        ];
        $client = new Client();
        $url = Yii::$app->params['middleware_user_update'];
        $response = $client->request('POST', $url, ['form_params' => $params]);
        $return = $response->getBody()->getContents();
        print_r($return);
//        var_dump($response);
    }

    public function actionRenewInfo()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        /** @var WechatVip $wechatvip */
        $wechatvip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if (strtotime($wechatvip->update_time) > (time() - 300)) {
            $type = $wechatvip->brand == 1 ? 'BIT' : "SIT";
            $_user = WechatUser::find()->where(['id'=>$wechatvip->wechat_user_id])->one();
            if (!empty($_user)){
                $openid = $_user->openid;
                Yii::error('刷新信息openid'.$openid);
            }else{
                Yii::error('刷新信息openid不存在');
                $openid = '';
            }
            $middleware = User::renewUser($wechatvip->phone, $type,$openid);
            if ($middleware === false) {
                Yii::error('=======请求错误====');
            } else {
                if ($middleware['code'] === 0) {
                    $res = $middleware['data'];
                    $vip = $res['vip'];
                    $profile = $res['profile'];
                    $wechatvip->vip_no = $vip['VIPKO'];
                    $wechatvip->vip_type = $vip['VIPType'];
                    $wechatvip->join_date = $vip['JoinDate'];
                    $wechatvip->exp_date = $vip['ExpDate'];
                    $wechatvip->phone = $profile['TELNO'];
                    $wechatvip->name = $profile['VIPNAME'];
                    $wechatvip->email = $profile['EMAILADDR'];
                    $wechatvip->sex = $profile['SEX'] == 'F' ? 0 : 1;
                    $wechatvip->birthday = $profile['DOB'];
                    $wechatvip->name_first = $profile['W_FNAME'] == null ? $profile['VIPNAME'] : $profile['W_FNAME'];
                    $wechatvip->name_last = $profile['W_LNAME'];
                    $wechatvip->update_time = date('Y-m-d H:i:s');
                    $wechatvip->save();
                    $vipProfile = VipInfomation::find()->where(['altid' => $profile['ALTID']])->one();
                    if (!$vipProfile) {
                        $vipProfile = new VipInfomation();
                        $vipProfile->altid = $profile['ALTID'];
                    }
                    $vipProfile->addr1 = $profile["ADDR1"];
                    $vipProfile->addr2 = $profile['ADDR2'];
                    $vipProfile->income = $profile['W_SALARY'];
                    $vipProfile->education = $profile['W_EDUCATION'];
                    $vipProfile->interest = $profile['W_INTERESTS'];
                    $vipProfile->career = $profile['W_OCCUPATION'];
                    $vipProfile->area = $profile['W_COUNTRY'];
                    $vipProfile->province = $profile['W_PROVINCE'];
                    $vipProfile->city = $profile['W_CITY'];
                    $vipProfile->town = $profile['W_DISTRICT'];
                    $vipProfile->save();
                    Yii::error('更新数据成功');
                } else {
                    Yii::error('接口返回值为' . $middleware['code']);
                }
            }
        } else {
            Yii::error('时间未到');
        }
    }

    /**
     * 导入店员队列处理
     * @return [type] [description]
     */
    public function actionImportStaff()
    {
        $request = Yii::$app->request;
        $brand_id = $request->get('brand_id');
        $store_id = $request->get('store_id');
        $staff_name = $request->get('staff_name');
        $staff_code = $request->get('code');
        $extra = $request->get('extra');
        $staffs = \backend\models\Staff::find()->where(['staff_code' => $staff_code])->one();
        $form = new \backend\service\staff\Form();
        if (!empty($staffs)) {
            Yii::error('编辑店员' . $staff_code);
            $data['id'] = $staffs->id;
            $data['store_id'] = $store_id;
            $data['staff_code'] = $staff_code;
            $data['staff_name'] = $staff_name;
            $data['extra'] = $extra;
            $form->load($data, '');
            $form->save($brand_id);
        } else {
            Yii::error('新增店员');
            $data['store_id'] = $store_id;
            $data['staff_code'] = $staff_code;
            $data['staff_name'] = $staff_name;
            $data['extra'] = $extra;
            $form->load($data, '');
            $form->save($brand_id);
        }

    }

    public function actionSpend()
    {
        $vip_code = Yii::$app->request->get('VIPKO');
        $date = Yii::$app->request->get('TXDATE');
        $store = Yii::$app->request->get('LOCKO');
        $type = Yii::$app->request->get('MEMTYPE');
        $money = Yii::$app->request->get('DTRAMT');
        $memono = Yii::$app->request->get('MEMONO');
        $isvip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if (!$isvip) {
            exit;
        }

        switch ($type) {
            case 'SS':
                $type_string = '消费';
                break;
            case 'EX':
                $type_string = '换货';
                break;
            case 'RF':
                $type_string = '退货';
                break;
            case 'SQ';
                $type_string = '员工折扣';
                break;
            case 'GC';
                $type_string = '礼品卡';
                break;
            case 'GV';
                $type_string = '礼品券';
                break;
        }
        $config = BrandApi::getBrandById($isvip->brand);
        $wechat = Yii::$app->wechat->app($config);
        $templateId = Yii::$app->params['template_id'][$isvip->brand]['spend'];
        $notice = $wechat->notice;
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $isvip->brand], true);
        $store_record = Store::find()->where(['store_code' => $store])->one();
        if (!$store_record) {
            $store_name = $store;
        } else {
            $store_name = $store_record->store_name;
        }
        //获得积分情况
        $point_url = Yii::$app->params['point_search'] . "?vip_code=" . $vip_code . '&memono=' . $memono;
        $point_json = file_get_contents($point_url);
        $point_array = json_decode($point_json, true);
        if ($point_array == 0) {
            Yii::error('获得积分为0，不推送');
            return false;
        } else {
            $point = $point_array['data'];
        }

        $datas = [
            'first' => '亲爱的会员 ，您已消费成功。',
            'time' => date('Y-m-d', strtotime($date)),
            'org' => $store_name,
            'type' => $type_string,
            'money' => $money,
            'point' => intval($point),
            'remark' => '积分详情，请进入“会员中心”查看',
        ];
        Yii::error('=======推送模板消息==========');
        Yii::error($datas);
        $wechatuser = WechatUser::find()->where(['id' => $isvip->wechat_user_id])->one();
        try {
            $send = $notice->uses($templateId)
                ->withUrl($url)
                ->andData($datas)
                ->andReceiver($wechatuser->openid)
                ->send();
            Yii::error('OK,发送成功');
            return true;
        } catch (\Exception $e) {
            Yii::error('Error,发送失败');
            echo $e->getMessage();
            return false;
        }

    }

    public function actionPoint()
    {
        Yii::error('===========进入处理==========');
        $vip_code = Yii::$app->request->get('VIPKO');
        $date = Yii::$app->request->get('TXDATE');
//        $store = Yii::$app->request->get('LOCKO');
        $money = Yii::$app->request->get('BP');
        /** @var WechatVip $isvip */
        $isvip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if (!$isvip) {
            Yii::error('===========没有==========');
            exit;
        }
        $config = BrandApi::getBrandById($isvip->brand);
        $wechat = Yii::$app->wechat->app($config);
        $templateId = Yii::$app->params['template_id'][$isvip->brand]['use_point'];
        $notice = $wechat->notice;
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $isvip->brand], true);
//        $store_record = Store::find()->where(['store_code' => $store])->one();
//        if (!$store_record) {
//            $store_name = $store;
//        } else {
//            $store_name = $store_record->store_name;
//        }

        $money = -intval($money);
        /** @var VipBonus $bonus */
        $bonus = VipBonus::find()->where(['vip_code' => $isvip->vip_no])->one();
        $right_bonus = intval($bonus->bonus);
        $exp_bonus = intval($bonus->exp_bonus);
        $datas = [
            'first' => '亲爱的会员 ，您使用积分消费成功',
            'XM' => $isvip->name,
            'KH' => $isvip->vip_no,
            'CONTENTS' => "您于{$date}在门店消费{$money}积分,目前您的积分余额是{$right_bonus},30天内到期积分{$exp_bonus}",
            'remark' => '积分详情，请进入"会员中心"查看',
        ];
        Yii::error('=======推送模板消息==========');
        Yii::error($datas);
        $wechatuser = WechatUser::find()->where(['id' => $isvip->wechat_user_id])->one();
        try {
            $send = $notice->uses($templateId)
                ->withUrl($url)
                ->andData($datas)
                ->andReceiver($wechatuser->openid)
                ->send();
            Yii::error('OK,发送成功');
            return true;
        } catch (\Exception $e) {
            Yii::error('Error,发送失败');
            echo $e->getMessage();
            return false;
        }

    }

    public function actionExpPoint()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        /** @var WechatVip $isvip */
        $isvip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if (!$isvip) {
            return '没有会员';
        }
        $bonus = WechatVipService::getBonus($vip_code);
        if ($bonus->exp_bonus == 0) {
            Yii::error('过期积分为0，不需要推送');
            return '过期积分为0，不需要推送';
        }

        $config = BrandApi::getBrandById($isvip->brand);
        $wechat = Yii::$app->wechat->app($config);
        $templateId = Yii::$app->params['template_id'][$isvip->brand]['exp_point'];
        $notice = $wechat->notice;
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $isvip->brand], true);


        $datas = [
            'first' => '亲爱的会员 ，您有一笔积分即将到期',
            'keyword1' => $isvip->vip_no,
            'keyword2' => intval($bonus->exp_bonus),
            'keyword3' => date('Y-m-d', strtotime("+30 days")),
            'keyword4' => intval($bonus->bonus),
            'remark' => '积分详情，请进入"会员中心"查看',
        ];
        /** @var WechatUser $wechatuser */
        $wechatuser = WechatUser::find()->where(['id' => $isvip->wechat_user_id])->one();
        try {
            $send = $notice->uses($templateId)
                ->withUrl($url)
                ->andData($datas)
                ->andReceiver($wechatuser->openid)
                ->send();
            Yii::error('OK,发送成功');
            return true;
        } catch (\Exception $e) {
            Yii::error('Error,发送失败');
            echo $e->getMessage();
            return false;
        }
    }

    public function actionUpgard()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        /** @var WechatVip $isvip */
        $isvip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if (!$isvip) {
            return '没有会员';
        }

        $bonus = WechatVipService::getBonus($vip_code);
        $config = BrandApi::getBrandById($isvip->brand);
        $wechat = Yii::$app->wechat->app($config);
        $templateId = Yii::$app->params['template_id'][$isvip->brand]['exp_point'];
        $notice = $wechat->notice;
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $isvip->brand], true);
        $datas = [
            'first' => '亲爱的会员 ，恭喜您已成功升级为' . $isvip->vip_type . '会员。',
            'keyword1' => $isvip->vip_no,
            'keyword2' => $isvip->exp_date,
            'remark' => "积分余额：" . $bonus->bonus . "\n30天内到期积分：" . $bonus->exp_bonus . "\n更多会员权益，请进入“会员中心”查看",
        ];
        /** @var WechatUser $wechatuser */
        $wechatuser = WechatUser::find()->where(['id' => $isvip->wechat_user_id])->one();
        try {
            $send = $notice->uses($templateId)
                ->withUrl($url)
                ->andData($datas)
                ->andReceiver($wechatuser->openid)
                ->send();
            Yii::error('OK,发送成功');
            return true;
        } catch (\Exception $e) {
            Yii::error('Error,发送失败');
            echo $e->getMessage();
            return false;
        }
    }

    public function actionContinue()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        /** @var WechatVip $isvip */
        $isvip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if (!$isvip) {
            return '没有会员';
        }

        $bonus = WechatVipService::getBonus($vip_code);
        $config = BrandApi::getBrandById($isvip->brand);
        $wechat = Yii::$app->wechat->app($config);
        $templateId = Yii::$app->params['template_id'][$isvip->brand]['add_exp'];
        $notice = $wechat->notice;
        $url = yii\helpers\Url::to(['index/index', 'brand_id' => $isvip->brand], true);
        $datas = [
            'first' => '亲爱的会员 ，您已成功延续当前会籍',
            'keyword1' => $isvip->vip_type,
            'keyword2' => $isvip->exp_date,
            'remark' => "积分余额：" . $bonus->bonus . "\n30天内到期积分：" . $bonus->exp_bonus . "\n更多会员权益，请进入“会员中心”查看",
        ];
        /** @var WechatUser $wechatuser */
        $wechatuser = WechatUser::find()->where(['id' => $isvip->wechat_user_id])->one();
        try {
            $send = $notice->uses($templateId)
                ->withUrl($url)
                ->andData($datas)
                ->andReceiver($wechatuser->openid)
                ->send();
            Yii::error('OK,发送成功');
            return true;
        } catch (\Exception $e) {
            Yii::error('Error,发送失败');
            echo $e->getMessage();
            return false;
        }

    }

}
