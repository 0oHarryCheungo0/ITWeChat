<?php

/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/2
 * Time: 下午12:15
 */

namespace common\middleware;

use backend\models\SystemConfig;
use common\models\BrandBonusConfig;
use common\models\BrandVipConfig;
use common\models\WechatVipsActiveLogs;
use GuzzleHttp\Client;
use wechat\models\service\WechatVipService;
use wechat\models\VipBonus;
use wechat\models\WechatVip;
use Yii;

class Bonus
{
    public static function query($vip_code)
    {
        $url = Yii::$app->params['middleware_bonus_query'];
        $client = new Client();
        $request = $client->request('GET', $url, ['query' => ['vip_code' => $vip_code, 'access_token' => 1]]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        if ($ret['code'] == 0) {
            return $ret['data'];
        } else {
            return ['bp' => 0, 'exp_in_3month' => 0];
        }
    }

    public static function get($vip_code)
    {
        $url = Yii::$app->params['middleware_bonus_get'];
        $client = new Client();
        $request = $client->request('GET', $url, ['query' => ['vip_code' => $vip_code, 'access_token' => 1]]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        if ($ret['code'] == 0) {
            return Formatter::byMonth($ret['data'], 'TXDATE');
        } else {
            return [];
        }
    }

    public static function spend($vip_code)
    {
        $url = Yii::$app->params['middleware_bonus_spend'];
        $client = new Client();
        $request = $client->request('GET', $url, ['query' => ['vip_code' => $vip_code, 'access_token' => 1]]);
        $return = $request->getBody()->getContents();
        $ret = json_decode($return, true);
        if ($ret['code'] == 0) {
            return Formatter::byMonth($ret['data'], 'TXDATE');
        } else {
            return [];
        }
    }

    public static function profileAdd($brand_id, $vip_code)
    {
        $brand_config = SystemConfig::getBonusConfig($brand_id, 2);
        /** @var BrandVipConfig $config */
        $config = BrandVipConfig::find()->where(['brand_id' => $brand_id])->one();
        $point = $config->finish_profile;
        self::setPoint($brand_config, $vip_code, $point);
        return true;
    }

    public static function regAdd($brand_id, $vip_code)
    {
        //检查是否有记录
        $is_log = WechatVipsActiveLogs::find()->where(['types' => 1, 'vip_code' => $vip_code])->one();
        if ($is_log) {
            return true;
        }
        $brand_config = SystemConfig::getBonusConfig($brand_id, 1);
        /** @var BrandVipConfig $config */
        $config = BrandVipConfig::find()->where(['brand_id' => $brand_id])->one();
        $point = $config->reg_point;
        self::setPoint($brand_config, $vip_code, $point);
        $log = new WechatVipsActiveLogs();
        $log->vip_code = $vip_code;
        $log->types = 1;
        $log->create_time = date('Y-m-d H:i:s');
        $log->remark = '绑定赠送积分';
        $log->save();
        return true;
    }

    public static function tmpPoint($brand_id,$vip_code){
        $brand_config = SystemConfig::getBonusConfig($brand_id, 1);
        /** @var BrandVipConfig $config */
        $config = BrandVipConfig::find()->where(['brand_id' => $brand_id])->one();
        $point = $config->reg_point;
        $result=self::setPoint($brand_config, $vip_code, $point);
        if ($result){
            \Yii::error('新增积分成功'.$vip_code);
            return true;
        }else{
            return false;
        }
    }

    public static function setPoint($brand_config, $vip_code, $point = 0)
    {
        //创建vip;
        if ($brand_config['exp'] == 0) {
            $exp_date = '2099-12-31 00:00:00';
        } else {
            $later = $brand_config['exp'] + 1;
            $str = '+' . $later . ' month';
            $more_1day_month = date('Y-m', strtotime($str) - 1);
            $exp_date = date('Y-m-d', strtotime($more_1day_month) - (60 * 60 * 24));
        }
        $vbid = $brand_config['vb_prefix'];
        $vbgroup = $brand_config['vbgroup'];
        /** @var WechatVip $vip */
        $vip = WechatVip::find()->where(['vip_no' => $vip_code])->one();
        $data = [
            'vip_code' => $vip->vip_no,
            'vip_type' => $vip->vip_type,
            'vb_group' => $vbgroup,
            'vbid' => $vbid,
            'exp_date' => $exp_date,
            'tx_date' => date('Y-m-d'),
            'memo_type' => 'ss',
            'bp' => $point
        ];
        if (!Queue::sendBonus($data)) {
            return false;
        } else {
            /** @var VipBonus $old */
            $old = WechatVipService::getBonus($vip_code);
            $bonus = VipBonus::find()->where(['vip_code' => $vip_code])->one();
            $bonus->bonus += $point;
            $bonus->save();
            return true;
        }

    }

}