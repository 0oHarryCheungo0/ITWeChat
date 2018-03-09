<?php

namespace backend\service\staff;

use backend\models\Staff;
use backend\models\Store;
use Yii;

class StaffLogic
{
    public static function getStaffInfo($key)
    {
        return Staff::find()->select(['id', 'staff_code', 'staff_name', 'store_id', 'brand_id'])->where(['key' => $key])->asArray()->one();
    }

    /**
     * 员工列表数
     * @param  [type] $limit    [description]
     * @param  [type] $offset   [description]
     * @param  [type] $search   [description]
     * @param  [type] $store_id [description]
     * @return [type]           [description]
     */
    public function getList($limit, $offset, $search, $store_id)
    {
        $query = Staff::find()->with('store')->with('counts')->where(['staff.brand_id' => Yii::$app->session->get('brand_id')])->asArray();

        if (!empty($search)) {

            if (is_numeric($search)) {
                $query = $query->andwhere(['like', 'staff_code', $search]);
            } else {
                $query = $query->andwhere(['like', 'staff_name', $search]);
            }

        }

        if (!empty($store_id)) {
            $query = $query->andwhere(['staff.store_id' => $store_id]);
        } else {
            $query = $query->andwhere(true);
        }

        $total = $query->count();
        $data  = $query->offset($offset)->limit($limit)->all();

        return ['total' => $total, 'rows' => $data];
    }

    public function getScanList($staff_id, $limit, $offset, $search)
    {
        $rows = Staff::find()->where(['id' => $staff_id])->with('scan')->with('user');
        if (!empty($search)) {
            //$rows->andWhere([])
        }

        $total = $rows->count();
        $rows  = $rows->limit($limit)->offset($offset)->asArray()->all();
        return ['total' => $total, 'rows' => $rows];
    }

    /**
     * 导出员工报表
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function exportStaff($data)
    {
        $store_id = '';
        $search   = '';
        if (isset($data['search'])){
            $search = $data['search'];
        }

        $query = Staff::find()->with('store')->with('counts')->with('fans')->where(['staff.brand_id' => Yii::$app->session->get('brand_id')])->asArray();

        if (!empty($search)) {

            if (is_numeric($search)) {
                $query = $query->andwhere(['like', 'staff_code', $search]);
            } else {
                $query = $query->andwhere(['like', 'staff_name', $search]);
            }

        }

        if (!empty($store_id)) {
            $query = $query->andwhere(['staff.store_id' => $store_id]);
        } else {
            $query = $query->andwhere(true);
        }
        $data = $query->all();
        foreach ($data as $k => $v) {
            $new = 0;
            $old = 0;
            if (!empty($v['fans'])) {

                foreach ($v['fans'] as $k1 => $v1) {
                    if ($v1['is_old'] == 1) {
                        $old++;
                    } else {
                        $new++;
                    }
                }

            }
            $data[$k]['fans']['old'] = $old;
            $data[$k]['fans']['new'] = $new;
        }
        \backend\service\Export::staff($data);

    }

    /**
     * [getScan description]
     * @param  integer $type 为1时为粉丝关注数，为2是为会员关注数
     * @return [type]        [description]
     */
    public function getScan($data, $type)
    {
        $data= json_decode($data,true);
        $brand_id = Yii::$app->session->get('brand_id');

        if ($type != 0) {
            //导出会员数据
            if (isset($data['search'])&&!empty($data['search'])){
                $ret = Yii::$app->db->createCommand('select store.*,count(member_relation.id) as bind_count,wechat_vip.vip_type as vip_type,member_relation.bind_day as bind_day from store left join member_relation on store.id=member_relation.store_id left join wechat_vip on member_relation.member_id=wechat_vip.vip_no where store.brand_id=' . $brand_id . ' and store.store_code like "%'.$data["search"].'%" group by bind_day,store_name,vip_type')->queryAll();

            }else{
                $ret = Yii::$app->db->createCommand('select store.*,count(member_relation.id) as bind_count,wechat_vip.vip_type as vip_type,member_relation.bind_day as bind_day from store left join member_relation on store.id=member_relation.store_id left join wechat_vip on member_relation.member_id=wechat_vip.vip_no where store.brand_id=' . $brand_id . ' group by bind_day,store_name,vip_type')->queryAll();
            }
            
        } else {

           if (isset($data['search'])&&!empty($data['search'])){
            //导出粉丝数据
            $sql     = "select *,DATE_FORMAT(wechat_scan_log.scan_date , '%Y-%m-%d') as scan_day,count(distinct(wechat_scan_log.openid)) as scan_count  from store left join staff on store.id=staff.store_id left join wechat_scan_log on staff.key=wechat_scan_log.scan_key where store.brand_id=" . $brand_id . " and store.store_code like '%".$data['search']."%' group by scan_day,store_code,openid,subscribe";

           }else{

            //导出粉丝数据
            $sql     = "select *,DATE_FORMAT(wechat_scan_log.scan_date , '%Y-%m-%d') as scan_day,count(distinct(wechat_scan_log.openid)) as scan_count  from store left join staff on store.id=staff.store_id left join wechat_scan_log on staff.key=wechat_scan_log.scan_key where store.brand_id=" . $brand_id . " group by scan_day,store_code,openid,subscribe";
           }
        
            $ret     = Yii::$app->db->createCommand($sql)->queryAll();
            $openids = [];
            foreach ($ret as $k => $v) {
                if ($v['subscribe'] != null && $v['subscribe'] != 1) {
                    unset($ret[$k]);
                } else {
                    if (!empty($v['openid'])) {
                        foreach ($ret as $k1 => $v1) {
                            if ($v1['store_code'] == $v['store_code'] && $v1['scan_count'] == 0) {
                                unset($ret[$k1]);
                            }
                        }
                        $openids[] = $v['openid'];
                    }
                }
            }
            $times = array_count_values($openids);
            foreach ($times as $k => $v) {
                if ($v > 1) {
                    $i = 1;
                    //出现超过一次
                    foreach ($ret as $k1 => $v1) {
                        if ($v1['openid'] == $k) {
                            if ($i != 1) {
                                unset($ret[$k1]);
                            }
                            $i++;
                        }
                    }
                }
            }
            //是会员则注销
            foreach ($ret as $k => $v) {
                if ($v['is_syn'] == 1) {
                    unset($ret[$k]);
                }
            }

            $data = [];
            $c    = count($ret);
            $i    = 1;
            foreach ($ret as $k => $v) {
                $data[$c - $i] = $v;
                $i++;
            }

        }

        if ($type == 0) {
            \backend\service\Export::storeScan($data);
        } else {
            \backend\service\Export::storeMember($ret);
        }

    }

}
