<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/4
 * Time: 下午6:15
 */

namespace wechat\models\logic;


use common\middleware\Queue;
use wechat\models\VipBonus;

class BonusOption
{
    /**
     * 写入到CRM系统中
     * @param $vip_code
     * @param $vip_type
     * @param $vb_group
     * @param $vbid
     * @param $exp_date
     * @param $tx_date
     * @param $memo_type
     * @param $point
     * @throws \Exception
     */
    public function addBonus($vip_code, $vip_type, $vb_group, $vbid, $exp_date, $tx_date, $memo_type, $point)
    {
        $data = [
            'vip_code' => $vip_code,
            'vip_type' => $vip_type,
            'vb_group' => $vb_group,
            'vbid' => $vbid,
            'exp_date' => $exp_date,
            'tx_date' => $tx_date,
            'memo_type' => $memo_type,
            'bp' => $point
        ];
        //将添加积分更新到队列中。
//        if (!Record::addBonus($this->vip_no, $vip_type, $vb_group, $vbid, $exp_date, $tx_date, $memo_type, $this->point)) {
        if (!Queue::sendBonus($data)) {
            throw new \Exception('添加积分到队列出错');
        } else {
            $bonus = VipBonus::find()->where(['vip_code' => $vip_code])->one();
            $bonus->bonus += $this->point;
            $bonus->save();
        }
    }

}