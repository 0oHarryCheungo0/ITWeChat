<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/21
 * Time: 下午6:05
 */

namespace backend\models;


use common\models\BrandBonusConfig;
use common\models\BrandVipConfig;
use common\models\CheckInRule;

class SystemConfig
{
    static $default_check_in = [1 => 1];

    public static function getVipConfig($brand_id)
    {
        $config = BrandVipConfig::find()->where(['brand_id' => $brand_id])->one();
        if (!$config) {
            $config = new BrandVipConfig();
            $config->brand_id = $brand_id;
            $config->vr_prefix = 'WX';
            $config->reg_point = 0;
            $config->finish_profile = 0;
            $config->exp_time = 1;
            $config->save();
        }
        return $config;
    }

    public static function getCheckInConfig($brand_id)
    {
        $rule = CheckInRule::find()->where(['brand_id' => $brand_id])->one();
        if (!$rule) {
            $config = json_encode(self::$default_check_in);
            $rule = new CheckInRule();
            $rule->update_time = date('Y-m-d H:i:s');
            $rule->brand_id = $brand_id;
            $rule->config = $config;
            $rule->save();
            $ret = ['base' => 1, 'config' => self::$default_check_in];
        } else {
            $ret = ['base' => $rule->base, 'config' => json_decode($rule->config, true)];
        }
        return $ret;
    }

    public static function saveVipConfig($brand_id, $config)
    {
        $brand = BrandVipConfig::find()->where(['brand_id' => $brand_id])->one();
        $brand->vr_prefix = $config['vrid'];
        $brand->exp_time = $config['exp'];
        $brand->reg_point = $config['point'];
        $brand->finish_profile = $config['finish'];
        if (!$brand->save()) {
            throw new \Exception('保存失败');
        }
        return true;
    }

    public static function saveCheckInConfig($brand_id, $base = 1, $config = [])
    {
        if (!$config) {
            $config = self::$default_check_in;
        }
        $rule_arr = [];
        //检查数组是否符合规则
        foreach ($config as $item) {
            if ($item['day'] <= 1) {
                throw new \Exception('周期不能小于2');
            }
            if ($item['point'] <= 0) {
                throw new \Exception('奖励积分不能小于0');
            }
            if (isset($rule_arr[$item['day']])) {
                if ($rule_arr[$item['day']] < $item['point']) {
                    $rule_arr[$item['day']] = $item['point'];
                } else {
                    continue;
                }
            } else {
                $rule_arr[$item['day']] = $item['point'];
            }
        }
        krsort($rule_arr);
        $rule = CheckInRule::find()->where(['brand_id' => $brand_id])->one();
        $rule->base = $base;
        $rule->update_time = date('Y-m-d H:i:s');
        $rule->config = json_encode($rule_arr);
        if ($rule->save() == false) {
            throw new \Exception('保存失败');
        }
    }

    public static function getBonusConfig($brand_id, $type)
    {
        $config = BrandBonusConfig::find()->where(['brand_id' => $brand_id, 'type' => $type])->one();
        if (!$config) {
            $config = new BrandBonusConfig();
            $config->brand_id = $brand_id;
            $config->update_time = date('Y-m-d H:i:s');
            $config->vbgroup = 'WXBONUS';
            $config->type = $type;
            $config->vb_prefix = 'WX';
            $config->exp = 0;
            $config->save();
        }
        return $config;
    }

    public static function saveBonusConfig($brand_id, $type, $vb_prefix, $vbgroup, $exp)
    {
        $config = BrandBonusConfig::find()->where(['brand_id' => $brand_id, 'type' => $type])->one();
        $config->vb_prefix = $vb_prefix;
        $config->vbgroup = $vbgroup;
        $config->exp = $exp;
        if ($config->save() == false) {
            throw new \Exception('保存失败');
        }
    }

}