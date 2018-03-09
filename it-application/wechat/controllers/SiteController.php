<?php

namespace wechat\controllers;

use backend\service\fans\FansList;
use common\api\NewsApi;
use common\lib\Functions;
use common\middleware\Bonus;
use common\middleware\Queue;
use common\models\service\TmplMsg;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionPush()
    {
        $spends_json = Yii::$app->request->getRawBody();
        $spends = json_decode($spends_json, true);
        foreach ($spends as $spend) {
            Functions::formatBlank($spend);
            $vips = ['CN_VIPASS', 'CN_VPASS', 'CN_BIT', 'CN_BITT0', 'CN_IPASS', 'CN_SIT', 'CN_SITT0'];
            if (in_array($spend['VIPTYPE'], $vips)) {
                Queue::sendSpendMsg($spend);
            }
        }
    }

    public function actionRebind()
    {
        $vip_code = Yii::$app->request->get('vip_code');
        $brand_id = Yii::$app->request->get('brand_id', 1);
        /** @var WechatVip $vip */
        $vip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        if ($vip) {
            /** @var WechatUser $user */
            $user = WechatUser::find()->where(['id' => $vip->wechat_user_id])->one();
            $store_id = FansList::bindMember($user->openid, 1);
            Yii::error('这个用户绑定的store_id为' . $store_id);
            if ($store_id) {
                $vip->store_id = $store_id;
                $vip->save();
            }
            NewsApi::trigger($vip->wechat_user_id, $brand_id);
            Bonus::regAdd($brand_id, $vip_code);
            $tips = "亲爱的会员，您已成功绑定会员卡，并获赠200积分。";
            $tmpl_id = Yii::$app->params['template_id'][$brand_id]['bind'];
            $url = Url::to(['index/index', 'brand_id' => $brand_id], true);
            $datas = [
                'first' => $tips,
                'keyword1' => $vip_code,
                'keyword2' => date("Y-m-d"),
                'remark' => "登记姓名：{$vip->name}\n登记手机号：{$vip->phone}\n有效期：{$vip->exp_date}\n请进入”会员中心“完善会员资料，领取积分奖励",
            ];
            TmplMsg::sendMsg($tmpl_id, $user->openid, $url, $datas);
        }


    }
}
