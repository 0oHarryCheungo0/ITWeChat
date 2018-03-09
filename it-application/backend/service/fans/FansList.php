<?php

namespace backend\service\fans;

use backend\models\Staff;
use common\models\MemberRelation;
use common\models\WechatScanLog;
use wechat\models\WechatUser;
use wechat\models\WechatVip;
use Yii;

class FansList
{

    public static function getList($limit, $offset, $type = [])
    {
        $brand_id = Yii::$app->session->get('brand_id');

        //->select('member_relation.id,store.store_name,member_relation.store_id,member_relation.scan_time,member_relation.staff_id,staff.staff_name,store.store_code,staff.staff_code,wechat_user.openid,member_relation.type')
        $rows  = MemberRelation::find()->where(['member_relation.brand_id' => $brand_id])->joinWith('user')->joinWith('store')->joinWith('staff');
        $total = 0;
        if (!empty($type)) {

            if (!empty($type['store_name'])) {
                $rows = $rows->andWhere(['like', 'store.store_code', $type['store_name']]);
                $total = $rows->count();
            }

            if (!empty($type['staff_name'])) {
                $rows = $rows->andWhere(['like', 'staff.staff_code', $type['staff_name']]);
                $total = $rows->count();
            }

        }

        //$total = $rows->count();
        if ($total == 0) {
            $total = MemberRelation::find()->where(['brand_id' => $brand_id])->count();
        }

        $rows = $rows->limit($limit)->offset($offset)->asArray()->all();
        return ['total' => $total, 'rows' => $rows];
    }

    public static function bindMember($openid, $is_old = 0)
    {
        //判断是否有关注
        $log = WechatScanLog::find()->where(['openid' => $openid])->orderBy('id desc')->one();
        if (empty($log)) {
            return false;

        }
        $scan_key  = $log['scan_key'];
        $scan_date = $log['scan_date'];
        $staff     = Staff::find()->where(['key' => $scan_key])->one();

        if (!empty($staff)) {
            $user = WechatUser::find()->where(['openid' => $openid])->one();
            if (!empty($user)) {
                $wechat_user_id = $user->id;
                $vip            = WechatVip::find()->where(['wechat_user_id' => $wechat_user_id])->one();
                if (!empty($vip)) {
                    $vip_no    = $vip->vip_no;
                    $vip_phone = $vip->phone;
                    $relation  = MemberRelation::find()->where(['openid' => $openid])->one();
                    $relation1 = MemberRelation::find()->where(['member_id' => $vip_no])->one();
                    if (empty($relation) && empty($relation1)) {
                        $model            = new MemberRelation();
                        $model->store_id  = $staff->store_id;
                        $model->staff_id  = $staff->id;
                        $model->is_old    = $is_old;
                        $model->openid    = $openid;
                        $model->brand_id  = $staff->brand_id;
                        $model->member_id = $vip_no;
                        $model->phone     = $vip_phone;
                        // $model->bind_time = time();
                        $model->join_time = time();
                        $model->scan_time = strtotime($log->scan_date);
                        $model->scan_day  = date('Y-m-d', strtotime($log->scan_date));
                        $model->bind_day  = date('Y-m-d');
                        $model->type      = 1;
                        $is_save          = $model->save();
                        if ($is_save) {
                            $log->is_syn = 1;
                            $l_save      = $log->save();
                            if ($l_save) {
                                $add = Yii::$app->db->createCommand()->update('staff_counts', ['vip_count' => new \yii\db\Expression('vip_count+1')], 'staff_id=' . $staff->id)->execute();
                                if ($add) {
                                    return $staff->store_id;
                                } else {
                                    return false;
                                }

                            } else {
                                return false;
                            }

                        } else {
                            return false;
                        }
                    } else {
                        Yii::info('已绑定关系');
                        return false;
                    }

                } else {
                    Yii::info('会员数据不存在');
                    return false;
                }
            } else {
                Yii::info('用户数据不存在');
                return false;
            }
        } else {
            Yii::info('店员数据不存在');
            return false;
        }

    }

    public static function addFans($scan_key, $openid, $scan_date)
    {
        return true;
    }
    public static function addNews($scan_key, $openid, $scan_date)
    {
        return true;
    }

}
