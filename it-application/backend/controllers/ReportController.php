<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/14
 * Time: 上午11:41
 */

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Staff;
use backend\models\Store;
use backend\service\Export;
use common\models\WechatVipsActiveLogs;
use wechat\models\VipCheckInLogs;
use wechat\models\WechatVip;
use yii;

class ReportController extends AdminBaseController
{
    const FINISH_PROFILE_TYPE = 2;

    const CHECK_IN_TYPE = 3;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 积分报表导出
     * @param int $type
     */
    public function actionPointExport($type = self::CHECK_IN_TYPE)
    {
        $range = Yii::$app->request->get('range');

        $range_arr = explode(' - ', $range);

        list($start, $end) = $range_arr;
        $start             = $start . ' 00:00:00';
        $end               = $end . ' 23:59:59';
        $brand_id          = $this->brand_id;

        $between = 'between "' . $start . '" and "' . $end . '"';
        switch ($type) {
            case self::FINISH_PROFILE_TYPE:
                $sql     = "SELECT member_id,vip.vip_no,vip_type,bind_time,name,name_last,phone,logs.create_time as active_time,remark FROM wechat_vip vip INNER JOIN wechat_vips_active_logs logs on(logs.vip_code=vip.vip_no) JOIN vip_infomation info on(info.altid=vip.member_id) where vip.brand=" . $brand_id . ' and logs.types=2 and logs.create_time ' . $between;
                $command = Yii::$app->db->createCommand($sql);
                $res     = $command->queryAll();
                Export::pointActive($res, '完成资料赠送积分导出', '完成资料填写');
            case self::CHECK_IN_TYPE:
                $sql     = "SELECT member_id,vip.vip_no,vip_type,bind_time,name,name_last,phone,logs.check_time as active_time,logs.point FROM wechat_vip vip INNER JOIN vip_check_in_logs logs ON(logs.vip_no=vip.vip_no) JOIN vip_infomation info on(info.altid=vip.member_id) where vip.brand=" . $brand_id . " and logs.check_time " . $between;
                $command = Yii::$app->db->createCommand($sql);
                $res     = $command->queryAll();
                Export::pointActive($res, '签到赠送积分导出', '签到');
                break;
        }

    }

    public function actionActivePoint()
    {
        $type  = Yii::$app->request->get('type', 1);
        $range = Yii::$app->request->get('range');

        $range_arr = explode(' - ', $range);

        list($start, $end) = $range_arr;
        $days              = [$start];
        $next              = date('Y-m-d', strtotime($start . '+1 day'));
        if ($start != $end) {
            while (true) {
                $days[] = $next;
                if ($next == $end) {
                    break;
                } else {
                    $next = date('Y-m-d', strtotime($next . "+1 day"));
                }
            }
            $days[] = $end;
        }
        $stores = Store::find()->where(['brand_id' => $this->brand_id])->asArray()->all();
        $final  = [];
        foreach ($stores as $store) {
            $vips = WechatVip::find()->select('vip_no')->where(['store_id' => $store['id']])->asArray()->all();
            if (!$vips) {
                foreach ($days as $day) {
                    $dates[$day] = 0;
                }
            } else {
                $_vip = [];
                foreach ($vips as $vip) {
                    $_vip[] = $vip['vip_no'];
                }
                foreach ($days as $key => $day) {
                    $dates[$day] = WechatVipsActiveLogs::find()
                        ->where(['in', 'vip_code', $_vip])
                        ->andWhere(['types' => $type])
                        ->andWhere(['between', 'create_time', $day . ' 00:00:00', $day . ' 23:59:59'])
                        ->count();
                }
            }
            //统计
            $final[] = [
                'store'      => $store['store_name'],
                'store_id'   => $store['id'],
                'store_code' => $store['store_code'],
                'remark'     => $type == 1 ? '注册绑定' : '完善资料',
                'province'   => $store['province'],
                'city'       => $store['city'],
                'datas'      => $dates,
            ];

            //统计日期段内的每一天的数据
        }
        //final里面还需要加上未绑定的
        $_unbind_vip = [];
        $vips        = WechatVip::find()->select('vip_no')->where(['store_id' => null])->asArray()->all();
        foreach ($vips as $vip) {
            $_unbind_vip[] = $vip['vip_no'];
        }
        foreach ($days as $key => $day) {
            $dates[$day] = WechatVipsActiveLogs::find()
                ->where(['in', 'vip_code', $_unbind_vip])
                ->andWhere(['types' => $type])
                ->andWhere(['between', 'create_time', $day . ' 00:00:00', $day . ' 23:59:59'])
                ->count();
        }
        $final[] = [
            'store'      => '未绑定用户',
            'store_id'   => '-',
            'store_code' => '-',
            'remark'     => $type == 1 ? '注册绑定' : '完善资料',
            'province'   => '-',
            'city'       => '-',
            'datas'      => $dates,
        ];
        Export::activeLog($final, $type);

    }

    /**
     * 根据签到，导出周期性报表
     */
    public function actionActiveLog()
    {
        $range = Yii::$app->request->get('range');

        $range_arr = explode(' - ', $range);

        list($start, $end) = $range_arr;
        $days              = [$start];
        $next              = date('Y-m-d', strtotime($start . '+1 day'));
        if ($start != $end) {
            while (true) {
                $days[] = $next;
                if ($next == $end) {
                    break;
                } else {
                    $next = date('Y-m-d', strtotime($next . "+1 day"));
                }
            }
            $days[] = $end;
        }
        $stores = Store::find()->where(['brand_id' => $this->brand_id])->asArray()->all();
        $final  = [];
        foreach ($stores as $store) {
            $vips = WechatVip::find()->select('vip_no')->where(['store_id' => $store['id']])->asArray()->all();
            if (!$vips) {
                foreach ($days as $day) {
                    $dates[$day] = 0;
                }
            } else {
                $_vip = [];
                foreach ($vips as $vip) {
                    $_vip[] = $vip['vip_no'];
                }
                foreach ($days as $key => $day) {
                    $dates[$day] = VipCheckInLogs::find()
                        ->where(['in', 'vip_no', $_vip])
                        ->andWhere(['between', 'check_time', $day . ' 00:00:00', $day . ' 23:59:59'])
                        ->count();
                }
            }
            //统计
            $final[] = [
                'store'      => $store['store_name'],
                'store_id'   => $store['id'],
                'store_code' => $store['store_code'],
                'brand'      => 'IT',
                'remark'     => '积分签到',
                'province'   => $store['province'],
                'city'       => $store['city'],
                'datas'      => $dates,
            ];

            //统计日期段内的每一天的数据
        }
        //final里面还需要加上未绑定的
        $_unbind_vip = [];
        $vips        = WechatVip::find()->select('vip_no')->where(['store_id' => null])->asArray()->all();
        foreach ($vips as $vip) {
            $_unbind_vip[] = $vip['vip_no'];
        }
        foreach ($days as $key => $day) {
            $dates[$day] = VipCheckInLogs::find()
                ->where(['in', 'vip_no', $_unbind_vip])
                ->andWhere(['between', 'check_time', $day . ' 00:00:00', $day . ' 23:59:59'])
                ->count();
        }
        $final[] = [
            'store'      => '未绑定用户',
            'store_id'   => '-',
            'store_code' => '-',
            'brand'      => 'IT',
            'remark'     => '积分签到',
            'province'   => '-',
            'city'       => '-',
            'datas'      => $dates,
        ];
        Export::activeLog($final, 3);

    }

    public function actionSelectDate($type = 1)
    {

        switch ($type) {
            case 1:
                $report = Yii::$app->request->get('report', 1);
                switch ($report) {
                    case 3:
                        $request_url = yii\helpers\Url::to(['report/active-log']);
                        break;
                    case 1:
                        $request_url = yii\helpers\Url::to(['report/active-point', 'type' => 1]);
                        break;
                    case 2:
                        $request_url = yii\helpers\Url::to(['report/active-point', 'type' => 2]);
                        break;
                }

                break;
            case 2:
                $search = Yii::$app->request->get('data');
                if ($search == 'undefined') {
                    $search = 0;
                }
                $request_url = yii\helpers\Url::to(['report/exportmember', 'data' => $search]);
                break;
            case 3:
                $search = Yii::$app->request->get('data');
                $n_type = Yii::$app->request->get('n_type');
                if ($search == 'undefined') {
                    $search = 0;
                }
                $request_url = yii\helpers\Url::to(['report/scanexport', 'data' => $search, 'type' => $n_type]);
                break;
            case 4:
                $search = Yii::$app->request->get('data');
                if ($search == 'undefined') {
                    $search = 0;
                }
                $request_url = yii\helpers\Url::to(['report/export', 'data' => $search]);
                break;
            case 5:
                //签到积分导出
                $request_url = yii\helpers\Url::to(['report/point-export', 'type' => self::CHECK_IN_TYPE]);
                break;
            case 6:
                $request_url = yii\helpers\Url::to(['report/point-export', 'type' => self::FINISH_PROFILE_TYPE]);
                break;
            case 7:
                $search = Yii::$app->request->get('data');
                $n_type = Yii::$app->request->get('n_type');
                if ($search == 'undefined') {
                    $search = 0;
                }
                $request_url = yii\helpers\Url::to(['report/point-day', 'data' => $search, 'type' => $n_type]);
                break;
        }
        $this->layout = 'layui2';
        return $this->render('selectdate', ['request_url' => $request_url]);

    }

    /**
     * 扫码加粉导出
     * @return [type] [description]
     */
    public function actionScanexport()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $search   = Yii::$app->request->get('data');
        $type     = Yii::$app->request->get('type');
        $range    = Yii::$app->request->get('range');

        $range_arr             = explode(' - ', $range);
        list($s_date, $e_date) = $range_arr;
        if (!empty($search)) {
            $store = Store::find()->where(['like', 'store_code', $search])->andWhere(['brand_id' => $brand_id])->all();
        } else {
            $store = Store::find()->where(['brand_id' => $brand_id])->all();
        }
        $dates = $this->quantum($s_date, $e_date);
        if ($type == 1) {
            //绑定会员数
            $sql    = "select member_relation.phone,wechat_vip.phone,member_relation.bind_day as bind_day,member_relation.store_id as store_id,member_relation.member_id as member_id,wechat_vip.vip_type as vip_type,count(DISTINCT(wechat_vip.phone)) as num from member_relation left join wechat_vip on member_relation.phone=wechat_vip.phone where member_relation.brand_id=" . $brand_id . " and  member_relation.bind_day between '" . $s_date . " 00:00:00" . "' and '" . $e_date . " 23:59:59" . "' group by bind_day,vip_type,store_id";
            $member = Yii::$app->db->createCommand($sql)->queryAll();

            $vip_types = $this->getVipData();

            $export_member = [];
            $i             = 0;
            if (!empty($vip_types)) {

                foreach ($store as $k => $v) {
                    foreach ($vip_types as $k1 => $v1) {
                        $export_member[$i]['store_code'] = $v['store_code'];
                        $export_member[$i]['store_name'] = $v['store_name'];
                        $export_member[$i]['province']   = $v['province'];
                        $export_member[$i]['city']       = $v['city'];
                        $export_member[$i]['vip_type']   = $v1;
                        foreach ($dates as $k2 => $v2) {
                            $export_member[$i][$v2] = 0;
                            if (!empty($member)) {
                                foreach ($member as $k3 => $v3) {
                                    if ($v3['store_id'] == $v['id'] && $v3['vip_type'] == $v1 && $v3['bind_day'] == $v2) {
                                        //绑定数
                                        $export_member[$i][$v2] = $v3['num'];
                                    }

                                }

                            }

                        }
                        $i++;
                    }
                    $i++;
                }
                $num = count($dates);
                \backend\service\Export::storeMember($export_member, $dates);
            } else {
                throw new \Exception('请联系超级管理员配置VipType');
            }

        }

        if ($type == 0) {
            //扫码加粉
            $sql  = "select count(distinct(wechat_scan_log.id)) as num,FROM_UNIXTIME(UNIX_TIMESTAMP(wechat_scan_log.scan_date),'%Y-%m-%d') as scan_day,wechat_scan_log.scan_date,wechat_scan_log.subscribe,wechat_scan_log.openid,wechat_scan_log.scan_key,staff.brand_id,staff.store_id from wechat_scan_log join staff on staff.key=wechat_scan_log.scan_key where wechat_scan_log.subscribe=1 and wechat_scan_log.scan_date between '" . $s_date . " 00:00:00" . "' and  '" . $e_date . " 23:59:59" . "' and staff.brand_id=" . $brand_id . " group by scan_day,staff.store_id";
            $fans = Yii::$app->db->createCommand($sql)->queryAll();

            $export_fans = [];
            $i           = 0;

            foreach ($store as $k1 => $v1) {
                $export_fans[$i]['store_code'] = $v1['store_code'];
                $export_fans[$i]['store_name'] = $v1['store_name'];
                $export_fans[$i]['province']   = $v1['province'];
                $export_fans[$i]['city']       = $v1['city'];
                foreach ($dates as $k => $v) {
                    $export_fans[$i][$v] = 0;
                    if (!empty($fans)) {
                        foreach ($fans as $k3 => $v3) {
                            if ($v3['scan_day'] == $v && $v3['store_id'] == $v1['id']) {
                                $export_fans[$i][$v] = $v3['num'];
                            }
                        }
                    }
                }
                $i++;
            }

            \backend\service\Export::storeScan($export_fans, $dates);

        }

    }

    /**
     * 获取vip_type分组
     * @return [type] [description]
     */
    private function getVipData()
    {
        $brand = Brand::getMemberRank();
        $new   = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        return $new;
    }

    /**
     * 根据起始时间获取时间段
     * @param  [type] $s_date 开始时间XXXX-XX-XX
     * @param  [type] $e_date 结束时间XXXX-XX-XX
     * @return array  $dates  时间段内每一天日期
     */
    private function quantum($s_date, $e_date)
    {
        $dates = [];

        $j = 0;
        for ($i = strtotime($s_date); $i <= strtotime($e_date); $i += 86400) {
            $dates[] = date("Y-m-d", $i);

        }
        return $dates;
    }

    /**
     * 会员报表导出
     * @return [type] [description]
     */
    public function actionExport()
    {
        $search = Yii::$app->request->get('data');
        $range  = Yii::$app->request->get('range');

        $range_arr = explode(' - ', $range);

        list($s_date, $e_date) = $range_arr;
        $brand_id              = Yii::$app->session->get('brand_id');
        //获取起始截止日期
        $dates = [];
        // $s_date = $data['s_time'];
        // $e_date = $data['e_time'];
        // $Date1  = $s_date; /*格式 年-月-日 或 年-月-日 时:分:秒*/
        // $Date2  = $e_date;
        $j = 0;
        for ($i = strtotime($s_date); $i <= strtotime($e_date); $i += 86400) {
            $dates[] = date("Y-m-d", $i);

        }

        //查询需要导出的店铺
        $stores = [];
        if ($search) {
            $store = Store::find()->where(['brand_id' => $brand_id])->andwhere(['like', 'store_code', $search])->all();
        } else {
            $store = Store::find()->where(['brand_id' => $brand_id])->all();
        }

        foreach ($store as $k => $v) {
            $stores[] = $v['id'];
        }

        $staffs = Staff::find()->where(['brand_id' => $brand_id])->andWhere(['in', 'store_id', $stores])->with('store')->orderBy('store_id')->asArray()->all();
        set_time_limit(300);
        ini_set('memory_limit', '500M');
        //查询新老会员数
        $sql    = "select brand_id,store_id,staff_id,bind_day,count(id) as vip,sum(case when is_old=0 then 1 else 0 end) as new,sum(case when is_old=1 then 1 else 0 end) as old from member_relation where brand_id=" . $brand_id . " and bind_day BETWEEN '" . $s_date . " 00:00:00" . "' and '" . $e_date . " 23:59:59" . "' group by staff_id,bind_day";
        $member = Yii::$app->db->createCommand($sql)->queryAll();

        //查询绑定粉丝数
        $sql  = "select *,FROM_UNIXTIME(UNIX_TIMESTAMP(scan_date),'%Y-%m-%d') as day,count(DISTINCT(openid)) as num from wechat_scan_log where subscribe=1 and scan_date between '" . $s_date . " 00:00:00" . "' and '" . $e_date . " 23:59:59" . "'  group by day,id ";
        $fans = Yii::$app->db->createCommand($sql)->queryAll();

        $export = [];

        //拼装数据
        $i = 0;
        foreach ($staffs as $k1 => $v1) {
            $export[$i]['id']                  = $v1['staff_code'];
            $export[$i]['staff_name']          = $v1['staff_name'];
            $export[$i]['store']['store_code'] = $v1['store']['store_code'];
            $export[$i]['store']['store_name'] = $v1['store']['store_name'];
            $export[$i]['store']['province']   = $v1['store']['province'];
            $export[$i]['store']['city']       = $v1['store']['city'];
            foreach ($dates as $k => $v) {
                $export[$i][$v]['vip']  = 0;
                $export[$i][$v]['new']  = 0;
                $export[$i][$v]['old']  = 0;
                $export[$i][$v]['fans'] = 0;
                if (!empty($member)) {
                    foreach ($member as $k2 => $v2) {
                        if ($v2['staff_id'] == $v1['id'] && $v2['bind_day'] == $v) {
                            $export[$i][$v]['vip'] = $v2['vip'];
                            $export[$i][$v]['new'] = $v2['new'];
                            $export[$i][$v]['old'] = $v2['old'];
                        }
                    }

                }
                if (!empty($fans)) {
                    foreach ($fans as $k2 => $v2) {
                        if ($v2['scan_key'] == $v1['key'] && $v2['day'] == $v) {
                            $export[$i][$v]['fans'] += $v2['num'];
                        }
                    }
                }
            }
            $i++;
        }
        // var_dump($export);exit;

        // foreach ($dates as $k => $v) {

        //     foreach ($staffs as $k1 => $v1) {
        //         $export[$i]['date']                = $v;
        //         $export[$i]['id']                  = $v1['staff_code'];
        //         $export[$i]['staff_name']          = $v1['staff_name'];
        //         $export[$i]['store']['store_code'] = $v1['store']['store_code'];
        //         $export[$i]['store']['store_name'] = $v1['store']['store_name'];
        //         $export[$i]['store']['province']   = $v1['store']['province'];
        //         $export[$i]['store']['city']       = $v1['store']['city'];
        //         $export[$i]['vip']                 = 0;
        //         $export[$i]['new']                 = 0;
        //         $export[$i]['old']                 = 0;
        //         $export[$i]['fans']                = 0;
        //         if (!empty($member)) {
        //             foreach ($member as $k2 => $v2) {
        //                 if ($v2['staff_id'] == $v1['id'] && $v2['bind_day'] == $v) {
        //                     $export[$i]['vip'] = $v2['vip'];
        //                     $export[$i]['new'] = $v2['new'];
        //                     $export[$i]['old'] = $v2['old'];
        //                 }
        //             }

        //         }

        //         if (!empty($fans)) {
        //             foreach ($fans as $k2 => $v2) {
        //                 if ($v2['scan_key'] == $v1['key'] && $v2['day'] == $v) {
        //                     $export[$i]['fans'] = $v2['num'];
        //                 }
        //             }
        //         }

        //         $i++;
        //     }
        //     $i++;
        // }
        \backend\service\Export::staff($export, $dates);

    }

    /**
     * 新老会员导出
     * @return [type] [description]
     */
    public function actionExportmember()
    {
        $search = Yii::$app->request->get('data');
        $range  = Yii::$app->request->get('range');

        $range_arr = explode(' - ', $range);

        list($s_date, $e_date) = $range_arr;
        $brand_id              = Yii::$app->session->get('brand_id');
        //获取起始截止日期
        $dates = [];
        $j     = 0;
        for ($i = strtotime($s_date); $i <= strtotime($e_date); $i += 86400) {
            $dates[] = date("Y-m-d", $i);
        }

        if ($search) {
            $store = Store::find()->where(['brand_id' => $brand_id])->andwhere(['like', 'store_code', $search])->all();
        } else {
            $store = Store::find()->where(['brand_id' => $brand_id])->all();
        }

        $sql    = 'select member_relation.openid,member_relation.member_id as vip_no,member_relation.bind_day as bind_day,member_relation.store_id as store_id,wechat_vip.vip_type as vip_type,wechat_vip.`name`,wechat_vip.phone,wechat_vip.member_id,wechat_vip.join_date,member_relation.is_old,member_relation.staff_id from member_relation join wechat_vip on member_relation.phone=wechat_vip.phone where member_relation.brand_id=' . $brand_id . ' and  member_relation.bind_day between "' . $s_date . ' 00:00:00' . '"  and "' . $e_date . ' 23:59:59' . '"  group by wechat_vip.vip_no';
        $member = Yii::$app->db->createCommand($sql)->queryAll();
        $export = [];
        $i      = 0;
        set_time_limit(300);
        ini_set('memory_limit', '500M');
        foreach ($dates as $k => $v) {
            foreach ($store as $k1 => $v1) {
                $export[$i]['store_name'] = $v1['store_name'];
                $export[$i]['store_code'] = $v1['store_code'];
                $export[$i]['province']   = $v1['province'];
                $export[$i]['city']       = $v1['city'];
                $export[$i]['staff_id']   = '';
                $export[$i]['name']       = '';
                $export[$i]['phone']      = '';
                $export[$i]['openid']     = '';
                $export[$i]['vip_type']   = '';
                $export[$i]['member_id']  = '';
                $export[$i]['vip_no']     = '';
                $export[$i]['join_date']  = '';
                $export[$i]['is_old']     = '';
                $export[$i]['bind_day']   = $v;
                if (!empty($member)) {
                    foreach ($member as $k2 => $v2) {
                        if ($v2['store_id'] == $v1['id'] && $v2['bind_day'] == $v) {
                            $export[$i]['store_name'] = $v1['store_name'];
                            $export[$i]['store_code'] = $v1['store_code'];
                            $export[$i]['province']   = $v1['province'];
                            $export[$i]['city']       = $v1['city'];
                            $export[$i]['staff_id']   = $v2['staff_id'];
                            $export[$i]['name']       = $v2['name'];
                            $export[$i]['phone']      = $v2['phone'];
                            $export[$i]['openid']     = $v2['openid'];
                            $export[$i]['vip_type']   = $v2['vip_type'];
                            $export[$i]['member_id']  = $v2['member_id'];
                            $export[$i]['vip_no']     = $v2['vip_no'];
                            $export[$i]['join_date']  = $v2['join_date'];
                            $export[$i]['is_old']     = $v2['is_old'];
                            $export[$i]['bind_day']   = $v;
                            $i++;
                        }
                    }
                }
                $i++;
            }
            $i++;
        }
        \backend\service\Export::newOldMember($export);
        var_dump($export);
        exit;

    }

    public function actionPointDay()
    {
        $search = Yii::$app->request->get('data');
        $range  = Yii::$app->request->get('range');

        $range_arr = explode(' - ', $range);

        list($s_date, $e_date) = $range_arr;
        $brand_id              = Yii::$app->session->get('brand_id');
        //获取起始截止日期
        $dates = [];
        // $s_date = $data['s_time'];
        // $e_date = $data['e_time'];
        // $Date1  = $s_date; /*格式 年-月-日 或 年-月-日 时:分:秒*/
        // $Date2  = $e_date;
        $j = 0;
        for ($i = strtotime($s_date); $i <= strtotime($e_date); $i += 86400) {
            $dates[] = date("Y-m-d", $i);

        }
        $store_list = '';
        //查询需要导出的店铺
        $stores = [];
        if ($search) {
            $store = Store::find()->where(['brand_id' => $brand_id])->andwhere(['like', 'store_code', $search])->all();
        } else {
            $store = Store::find()->where(['brand_id' => $brand_id])->all();
        }

        foreach ($store as $k => $v) {
            $store_list .= $v['id'] . ',';
            $stores[] = $v['id'];
        }

        $store_list = trim($store_list, ',');

        $staffs = WechatVip::find()->where(['brand' => $brand_id])->andWhere(['in', 'store_id', $stores])->with('store')->orderBy('store_id')->asArray()->all();

         set_time_limit(300);
        ini_set('memory_limit', '500M');
        //绑定跟完善资料
        $sql    = "select wechat_vip.vip_no as vip_no,wechat_vips_active_logs.types as type, FROM_UNIXTIME(UNIX_TIMESTAMP(wechat_vips_active_logs.create_time),'%Y-%m-%d') as create_time from store join wechat_vip on store.id=wechat_vip.store_id join wechat_vips_active_logs on wechat_vips_active_logs.vip_code=wechat_vip.vip_no where store.brand_id=" . $brand_id . "  and store.id in (" . $store_list . ") and wechat_vips_active_logs.create_time between '" . $s_date . " 00:00:00" . "' and '" . $e_date . " 23:59:59" . "'";
        $member = Yii::$app->db->createCommand($sql)->queryAll();

        //查询签到
        $sql  = "select wechat_vip.vip_no,vip_check_in_logs.point,FROM_UNIXTIME(UNIX_TIMESTAMP(vip_check_in_logs.check_time),'%Y-%m-%d') as check_time from store join wechat_vip on store.id=wechat_vip.store_id join vip_check_in_logs on vip_check_in_logs.vip_no=wechat_vip.vip_no where store.brand_id=" . $brand_id . "  and store.id in (" . $store_list . ") and check_time between '" . $s_date . " 00:00:00" . "' and '" . $e_date . " 23:59:59" . "'";
        $fans = Yii::$app->db->createCommand($sql)->queryAll();

        $export = [];

        //拼装数据
        $i = 0;
        foreach ($staffs as $k1 => $v1) {
            $export[$i]['id']                  = $v1['vip_no'];
            $export[$i]['phone']                = $v1['phone'];
            $export[$i]['store']['store_code'] = $v1['store']['store_code'];
            $export[$i]['store']['store_name'] = $v1['store']['store_name'];
            $export[$i]['store']['province']   = $v1['store']['province'];
            $export[$i]['store']['city']       = $v1['store']['city'];
            foreach ($dates as $k => $v) {
                $export[$i][$v]['sign']    = 0;
                $export[$i][$v]['prefect'] = 0;
                $export[$i][$v]['bind']    = 0;
                if (!empty($member)) {
                    foreach ($member as $k2 => $v2) {
                        if ($v2['vip_no'] == $v1['vip_no'] && $v2['create_time'] == $v) {
                            if ($v2['type'] ==1){
                                $export[$i][$v]['prefect'] = 100;
                            }else{
                                 $export[$i][$v]['bind']    = 200;
                            }

                        }
                    }

                }
                if (!empty($fans)) {
                    foreach ($fans as $k2 => $v2) {
                        if ($v2['vip_no'] == $v1['vip_no'] && $v2['check_time'] == $v) {

                            //echo $export[$i][$v];exit;
                            $export[$i][$v]['sign']    = ($v2['point']);

                        }
                    }
                }
            }
            $i++;
        }

        \backend\service\Export::PointDay($export, $dates);

    }
}
