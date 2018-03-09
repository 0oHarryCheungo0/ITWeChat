<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/28
 * Time: 上午10:43
 */

namespace api\ws;


class VipQuery extends Request
{
    /**
     * @var string
     * @soap
     */
    public $vip_code = '';


    public function findVip($vip_code)
    {

        $vip = \api\models\Vips::find()
            ->where(['vip_code' => $vip_code])
            ->asArray()
            ->one();
        if (!$vip) {
            return ['code' => 404];
        } else {
            if ($vip['state'] == 1) {
                return ['code' => 0, 'vip' => $vip];
            } else {
                return ['code' => 302, 'vip' => $this->getReflectVip($vip)];
            }
        }
        return $response;
    }

    /**
     * 通过reflect参数，找到最新的会员卡号
     * @return array 最新的会员资料
     */
    public function getReflectVip($vip)
    {
        $ref_id = $vip['reflect'];
        Yii::error('递归寻找最新数据，当前 reflect => ' . $vip['reflect']);
        $next_vip = \api\models\Vips::find()->where(['id' => $ref_id])->asArray()->one();
        if ($next_vip['state'] == 1) {
            return $next_vip;
        } else {
            Yii::error('找到了vip_code' . $next_vip->vip_code . '，reflect => ' . $next_vip['reflect']);
            Yii::error('next_vip也不是最终结果，所以讲当前的 reflect => ' . $vip->reflect . '指向' . $next_vip['reflect']);
            $vip->reflect = $next_vip['reflect'];
            $vip->save();
            return $this->getReflectVip($next_vip);
        }
    }

}