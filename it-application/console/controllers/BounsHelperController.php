<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/9/25
 * Time: 上午11:51
 */

namespace console\controllers;


use backend\models\SystemConfig;
use common\middleware\Queue;
use common\models\BrandVipConfig;
use common\models\WechatVipsActiveLogs;
use wechat\models\service\WechatVipService;
use wechat\models\VipBonus;
use wechat\models\WechatVip;
use yii\console\Controller;

class BounsHelperController extends Controller
{

    public function actionBit()
    {
        $bits = [
            'PT05200463',
            'PT01702430',
            'PT04601353',
            'PT02500335',
            'PT02200275',
            'PAJ0200041',
            'PT04600268',
            'PT03100447',
            'PT03301337',
            'PT05201113',
            'PT03100421',
            'PT05100102',
            'PT05201157',
            'PT05400165',
            'PT03000204',
            'PT06300090',
            'PT03100215',
            'PT05200642',
            'PT05101679',
            'PT05200965',
            'PT02201585',
            'PT05101135',
            'PT05201214',
            'PT05100862',
            'PT03100332',
            'PT05101023',
            'PT05100237',
            'PT05100528',
            'PT06300258',
            'PT05100315',
            'PT06300224',
            'PT05201279',
            'PT01202608',
        ];
        $bits = [];
        $brand_config = SystemConfig::getBonusConfig(1, 1);
        /** @var BrandVipConfig $config */
        foreach ($bits as $bit) {
            echo "开始处理{$bit}的积分请求\n";
            $data = $this->sendPoint($brand_config, $bit);
            $data_str = var_export($data, true);
            echo "请求参数为:\n{$data_str}\n";

            $is_log = WechatVipsActiveLogs::find()->where(['types' => 1, 'vip_code' => $bit])->one();
            if (!$is_log) {
                echo "没有记录，新建赠送记录\n";
                $log = new WechatVipsActiveLogs();
                $log->vip_code = $bit;
                $log->types = 1;
                $log->create_time = date('Y-m-d H:i:s');
                $log->remark = '绑定赠送积分';
                $log->save();
            }

            if (!Queue::sendBonus($data)) {
                echo "插入队列失败\n";
            } else {
                /** @var VipBonus $old */
                $old = WechatVipService::getBonus($bit);
                /** @var VipBonus $bonus */
                $bonus = VipBonus::find()->where(['vip_code' => $bit])->one();
                $bonus->bonus += 200;
                $bonus->save();
            }
            echo "处理完成\n\n";
        }

    }

    public function actionSit()
    {
        $sits = [
            'PI01700837',
            'PT02901326',
            'PT02300088',
            'PI00300546',
            'PI00300031',
            'PI00302101',
            'PT02900750',
            'PT02901995',
            'PT02601320',
            'PI00802633',
            'PI01402017',
            'PI00801161',
            'PI00801791',
            'PI03500186',
            'PT02900293',
            'PT04104691',
            'PT02902000',
            'PI00302377',
            'PT04000099',
            'PI00800969',
            'PI01401960',
            'PI00801173',
            'PT04704113',
            'PI03100261',
            'PI03100096',
            'PI03100248',
            'PI00802469',
            'PT01400404',
            'PI01003461',
            'PT04501515',
            'PI00302200',
            'WX00001255',
        ];
        $sits = [];
        $brand_config = SystemConfig::getBonusConfig(2, 1);
        /** @var BrandVipConfig $config */
        foreach ($sits as $sit) {
            echo "开始处理{$sit}的积分请求\n";
            $data = $this->sendPoint($brand_config, $sit);
            $is_log = WechatVipsActiveLogs::find()->where(['types' => 1, 'vip_code' => $sit])->one();
            if (!$is_log) {
                echo "没有记录，新建赠送记录\n";
                $log = new WechatVipsActiveLogs();
                $log->vip_code = $sit;
                $log->types = 1;
                $log->create_time = date('Y-m-d H:i:s');
                $log->remark = '绑定赠送积分';
                $log->save();
            }

            if (!Queue::sendBonus($data)) {
                echo "插入队列失败\n";
            } else {
                /** @var VipBonus $old */
                $old = WechatVipService::getBonus($sit);
                /** @var VipBonus $bonus */
                $bonus = VipBonus::find()->where(['vip_code' => $sit])->one();
                $bonus->bonus += 200;
                $bonus->save();
            }
            echo "处理完成\n\n";
        }
    }


    private function sendPoint($brand_config, $vip_code)
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
            'bp' => 200,
        ];
        return $data;
    }


}