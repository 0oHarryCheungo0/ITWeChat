<?php

namespace backend\service;

class Export
{
    public static function pointActive($data, $name, $remark = '签到')
    {
        $objectPHPExcel = new \PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '会员姓名')
            ->setCellValue('B1', '手机号码')
            ->setCellValue('C1', '会员卡号')
            ->setCellValue('D1', 'VIP_TYPE')
            ->setCellValue('E1', 'Memebership ID')
            ->setCellValue('F1', '绑定日期')
            ->setCellValue('G1', '活动名称')
            ->setCellValue('H1', '参与日期')
            ->setCellValue('I1', '积分变化');
        $k = 1;
        foreach ($data as $v) {
            $k++;
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValueExplicit('A' . $k, $v['name'] . ' ' . $v['name_last'])
                ->setCellValueExplicit('B' . $k, $v['phone'])
                ->setCellValueExplicit('C' . $k, $v['vip_no'])
                ->setCellValue('D' . $k, $v['vip_type'])
                ->setCellValueExplicit('E' . $k, $v['member_id'])
                ->setCellValue('F' . $k, $v['bind_time'])
                ->setCellValue('G' . $k, $remark)
                ->setCellValue('H' . $k, $v['active_time'])
                ->setCellValue('I' . $k, isset($v['point']) ? $v['point'] : 100);
        }
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');

    }

    /**
     * 导出店铺粉丝
     * @param  [type] $data 导出数据
     * @param  [type] $name 表格名称
     * @return [type]       导出
     */
    public static function outputData($data, $name)
    {
        $objectPHPExcel = new \PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '店铺编号')
            ->setCellValue('B1', '门店名称')
            ->setCellValue('C1', '扫码日期')
            ->setCellValue('D1', '绑定粉丝数');

        foreach ($data as $k => $v) {
            $k++;
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['store_code'])
                ->setCellValue('B' . $k, $v['store_name'])
                ->setCellValue('C' . $k, $v['date'])
                ->setCellValue('D' . $k, $v['total']);
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');

    }

    /**
     * 店员报表导出
     * @param  object $data 导出的数据
     * @return file         导出
     */
    public static function staff($data, $dates)
    {
        \Yii::info('店员报表导出' . json_encode($data));
        $name = '店员报表导出';
        set_time_limit(300);

        $objectPHPExcel = new \PHPExcel();
        $num = count($dates);
        $objectPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:A2')
            ->mergeCells('B1:B2')
            ->mergeCells('C1:C2')
            ->mergeCells('D1:D2')
            ->mergeCells('E1:E2')
            ->mergeCells('F1:F2')
            ->setCellValue('A1', '店员ID')
            ->setCellValue('B1', '店员姓名')
            ->setCellValue('C1', '门店编号')
            ->setCellValue('D1', '门店名称')
            ->setCellValue('E1', '省份')
            ->setCellValue('F1', '城市');
        $arr = self::MergeArr(8 + $num * 5);
        // $objectPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:k1');
        //   $objectPHPExcel->setActiveSheetIndex(0)->mergeCells('L1:M1');
        //var_dump($arr);exit;
        foreach ($dates as $k => $v) {

            $objectPHPExcel->setActiveSheetIndex(0)->mergeCells($arr[($k) * 4 + 6] . '1' . ':' . $arr[($k + 1) * 4 + 6 - 1] . '1');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 4 + 6] . '1', $v);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 4 + 6] . '2', '扫码加粉数');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 4 + 7] . '2', '绑定会员数');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 4 + 8] . '2', '新会员注册');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 4 + 9] . '2', '老会员绑定');
        }

        foreach ($data as $k => $v) {
            $k = $k + 3;
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['id'])
                ->setCellValue('B' . $k, $v['staff_name'])
                ->setCellValue('C' . $k, $v['store']['store_code'])
                ->setCellValue('D' . $k, $v['store']['store_name'])
                ->setCellValue('E' . $k, $v['store']['province'])
                ->setCellValue('F' . $k, $v['store']['city']);
            foreach ($dates as $k1 => $v1) {
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 4 + 6] . $k, $v[$v1]['fans']);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 4 + 7] . $k, $v[$v1]['vip']);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 4 + 8] . $k, $v[$v1]['new']);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 4 + 9] . $k, $v[$v1]['old']);

            }
        }


        // $objectPHPExcel->setActiveSheetIndex(0)->mergeCells($s.':'.$e)
        //        ->setCellValue($h, '扫码加粉数(总数)')
        //        ->setCellValue($i, '绑定会员数(总数)')
        //        ->setCellValue($j, '新会员注册')
        //        ->setCellValue($k, '老会员绑定');


        // foreach ($data as $k => $v) {
        //     $k = $k + 3;
        //     $objectPHPExcel->setActiveSheetIndex(0)
        //         ->setCellValue('A' . $k, $v['date'])
        //         ->setCellValue('B' . $k, $v['id'])
        //         ->setCellValue('C' . $k, $v['staff_name'])
        //         ->setCellValue('D' . $k, $v['store']['store_code'])
        //         ->setCellValue('E' . $k, $v['store']['store_name'])
        //         ->setCellValue('F' . $k, $v['store']['province'])
        //         ->setCellValue('G' . $k, $v['store']['city'])
        //         ->setCellValue('H' . $k, $v['fans'])
        //         ->setCellValue('I' . $k, $v['vip'])
        //         ->setCellValue('J' . $k, $v['old'])
        //         ->setCellValue('K' . $k, $v['new']);
        // }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');
    }

    /**
     * 店铺粉丝扫码导出
     * @param  [type] $data 需导出的数组
     * @return [type]       数据
     */
    public static function storeScan($data, $dates = [])
    {
        $num = count($dates);
        $arr = self::MergeArr($num + 6);
        $name = '粉丝扫描数';
        $objectPHPExcel = new \PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '门店编号')
            ->setCellValue('B1', '门店名称')
            ->setCellValue('C1', '省份')
            ->setCellValue('D1', '城市');
        if (!empty($dates)) {
            foreach ($dates as $k => $v) {
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$k + 4] . '1', $v);
            }
        }

        $k = 2;
        foreach ($data as $k1 => $v) {
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['store_code'])
                ->setCellValue('B' . $k, $v['store_name'])
                ->setCellValue('C' . $k, $v['province'])
                ->setCellValue('D' . $k, $v['city']);
            if (!empty($dates)) {
                foreach ($dates as $k2 => $v2) {
                    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$k2 + 4] . $k, $v[$v2]);
                }
            }
            $k++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');
    }

    /**
     * 店铺会员扫码导出
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function storeMember($data, $dates)
    {

        $num = count($dates);
        $arr = self::MergeArr($num + 6);
        $name = '会员绑定数';
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '门店编号')
            ->setCellValue('B1', '门店名称')
            ->setCellValue('C1', '省份')
            ->setCellValue('D1', '城市')
            ->setCellValue('E1', 'VipType');
        if (!empty($dates)) {
            foreach ($dates as $k => $v) {
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$k + 5] . '1', $v);
            }
        }
        $k = 2;
        foreach ($data as $k1 => $v) {

            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['store_code'])
                ->setCellValue('B' . $k, $v['store_name'])
                ->setCellValue('C' . $k, $v['province'])
                ->setCellValue('D' . $k, $v['city'])
                ->setCellValue('E' . $k, $v['vip_type']);
            if (!empty($dates)) {
                foreach ($dates as $k2 => $v2) {
                    $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$k2 + 5] . $k, $v[$v2]);
                }
            }
            $k++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');
    }

    public static function MergeArr($num = 120)
    {
        $arr = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $data = [];
        if ($num <= 26) {
            for ($i = 0; $i < $num; $i++) {
                $data[] = substr($arr, $i, 1);
            }
        }

        if ($num > 26) {
            for ($i = 0; $i < $num; $i++) {
                if ($i < 26) {
                    $data[] = substr($arr, $i, 1);
                }
                if ($i >= 26 && $i < 52) {
                    $data[] = 'A' . substr($arr, $i - 26, 1);
                }
                if ($i >= 52 && $i < 78) {
                    $data[] = 'B' . substr($arr, $i - 52, 1);
                }
                if ($i >= 78 && $i < 104) {
                    $data[] = 'C' . substr($arr, $i - 78, 1);
                }
                if ($i >= 104 && $i < 130) {
                    $data[] = 'D' . substr($arr, $i - 104, 1);
                }
            }
        }
        return $data;
    }

    /**
     * 新老会员绑定资料
     *
     * @param  array $data 需要导出的数组
     * @return file        文件
     */
    public static function newOldMember($data)
    {
        $name = '新老会员绑定导出';
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_GENERAL);

        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '门店编号')
            ->setCellValue('B1', '门店名称')
            ->setCellValue('C1', '省份')
            ->setCellValue('D1', '城市')
            ->setCellValue('K1', 'Staff Code')
            ->setCellValue('F1', '会员姓名')
            ->setCellValue('G1', '手机号码')
            ->setCellValue('H1', 'open ID')
            ->setCellValue('I1', 'VIP Type')
            ->setCellValue('J1', 'MembershipID')
            ->setCellValue('E1', '绑定日期')
            ->setCellValue('L1', '入会时间')
            ->setCellValue('M1', '绑定or注册');
        $k = 2;
        foreach ($data as $k1 => $v) {
            // var_dump($v);exit;

            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['store_code'])
                ->setCellValue('B' . $k, $v['store_name'])
                ->setCellValue('C' . $k, $v['province'])
                ->setCellValue('D' . $k, $v['city'])
                ->setCellValue('K' . $k, $v['staff_id'])
                ->setCellValueExplicit('F' . $k, $v['name'])
                ->setCellValueExplicit('G' . $k, $v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('H' . $k, $v['openid'])
                ->setCellValue('I' . $k, $v['vip_type'])
                ->setCellValueExplicit('J' . $k, $v['vip_no'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('E' . $k, $v['bind_day'])
                ->setCellValue('L' . $k, $v['join_date'])
                ->setCellValue('M' . $k, empty($v['vip_type']) ? '' : ($v['is_old'] == 1 ? '绑定' : '注册'));
            $k++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');
    }

    public static function vips($data)
    {
        $name = 'VIP导出' . date('Y-m-d');
        $objectPHPExcel = new \PHPExcel();

        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'OPENID')
            ->setCellValue('B1', 'VIP卡号')
            ->setCellValue('C1', '会员类型')
            ->setCellValue('D1', 'VIP姓名')
            ->setCellValue('E1', '性别')
            ->setCellValue('F1', '微信昵称')
            ->setCellValue('G1', '手机号')
            ->setCellValue('H1', 'Email')
            ->setCellValue('I1', 'ITOKEN')
            ->setCellValue('J1', '生日')
            ->setCellValue('K1', '绑定时间')
            ->setCellValue('L1', '绑定店铺')
            ->setCellValue('M1', '省')
            ->setCellValue('N1', '市')
            ->setCellValue('O1', '店铺编号');
        foreach ($data as $k => $v) {
            $k = $k + 2;
            if ($v['store']) {
                $store = $v['store']['store_name'];
                $province = $v['store']['province'];
                $city = $v['store']['city'];
                $store_code = $v['store']['store_code'];
            } else {
                $store = "未绑定";
                $province = '-';
                $city = '-';
                $store_code = '-';
            }
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['wechat']['openid'])
                ->setCellValue('B' . $k, $v['vip_no'])
                ->setCellValue('C' . $k, $v['vip_type'])
                ->setCellValueExplicit('D' . $k, $v['name'])
                ->setCellValue('E' . $k, $v['sex'] == 0 ? '女' : '男')
                ->setCellValueExplicit('F' . $k, $v['wechat']['nickname'])
                ->setCellValue('G' . $k, $v['phone'])
                ->setCellValue('H' . $k, $v['email'])
                ->setCellValue('I' . $k, $v['bonus']['bonus'])
                ->setCellValue('J' . $k, $v['birthday'])
                ->setCellValue('K' . $k, $v['bind_time'])
                ->setCellValue('L' . $k, $store)
                ->setCellValue('M' . $k, $province)
                ->setCellValue('N' . $k, $city)
                ->setCellValue('O' . $k, $store_code);
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');
    }

    public static function activeLog($data, $type = 1)
    {
        switch ($type) {
            case 1:
                $name = '注册绑定积分活动表' . date('Y-m-d');
                break;
            case 2:
                $name = '完善资料积分活动表' . date('Y-m-d');
                break;
            default:
                $name = '签到积分活动表' . date('Y-m-d');
                break;
        }

        $objectPHPExcel = new \PHPExcel();
        $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $objectPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '积分活动名称')
            ->setCellValue('B1', '店铺名')
            ->setCellValue('C1', '店铺CODE')
            ->setCellValue('D1', '省')
            ->setCellValue('E1', '市');
        $title_index = 5;
        foreach ($data[0]['datas'] as $day_key => $day) {
            //1.先找倍数
            if ($title_index < 26) {
                $index = $string[$title_index];
            } else {
                //第27个是AA
                $times = intval(floor($title_index / 26));
                if ($times > 0) {
                    $times--;
                }
                $first = $string[$times];
                $addr = $string[intval($title_index % 26)];
                $index = $first . $addr;
            }
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($index . '1', $day_key);
            $title_index++;
        }
        $body_index = 2;
        foreach ($data as $key => $_data) {
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $body_index, $_data['remark'])
                ->setCellValue('B' . $body_index, $_data['store'])
                ->setCellValue('C' . $body_index, $_data['store_code'])
                ->setCellValue('D' . $body_index, $_data['province'])
                ->setCellValue('E' . $body_index, $_data['city']);
            $body_day_index = 5;
            foreach ($_data['datas'] as $day) {
                //1.先找倍数
                if ($body_day_index < 26) {
                    $index = $string[$body_day_index];
                } else {
                    //第27个是AA
                    $times = intval(floor($body_day_index / 26));
                    if ($times > 0) {
                        $times--;
                    }
                    $first = $string[$times];
                    $addr = $string[intval($body_day_index % 26)];
                    $index = $first . $addr;
                }
                $objectPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($index . $body_index, $day);
                $body_day_index++;
            }

            $body_index++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');
    }

    public static function PointDay($data,$dates){
        //  $num = count($dates);
        // $arr = self::MergeArr($num + 6);
        // $name = '积分报表';
        // $objectPHPExcel = new \PHPExcel();
        // $objectPHPExcel->setActiveSheetIndex(0)
        //     ->setCellValue('A1', '门店编号')
        //     ->setCellValue('B1', '门店名称')
        //     ->setCellValue('C1', '省份')
        //     ->setCellValue('D1', '城市')
        //     ->setCellValue('E1', 'vip_no');
        //    // ->setCellValue('F1', '积分值');
        // if (!empty($dates)) {
        //     foreach ($dates as $k => $v) {
        //         $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$k + 5] . '1', $v);
        //     }
        // }
        // $k = 2;
        // foreach ($data as $k1 => $v) {

        //     $objectPHPExcel->setActiveSheetIndex(0)
        //         ->setCellValue('A' . $k, $v['store']['store_code'])
        //         ->setCellValue('B' . $k, $v['store']['store_name'])
        //         ->setCellValue('C' . $k, $v['store']['province'])
        //         ->setCellValue('D' . $k, $v['store']['city'])
        //         ->setCellValue('E' . $k, $v['id']);
        //         //->setCellValue('F' . $k, $v['vip']);
        //     if (!empty($dates)) {
        //         foreach ($dates as $k2 => $v2) {
        //             $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[$k2 + 5] . $k, $v[$v2]);
        //         }
        //     }
        //     $k++;
        // }

        // header('Content-Type: application/vnd.ms-excel');
        // header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        // header('Cache-Control: max-age=0');
        // $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        // $write->save('php://output');
        \Yii::info('店员报表导出' . json_encode($data));
        $name = '积分报表导出';
        set_time_limit(300);

        $objectPHPExcel = new \PHPExcel();
        $num = count($dates);
        $objectPHPExcel->setActiveSheetIndex(0)
            ->mergeCells('A1:A2')
            ->mergeCells('B1:B2')
            ->mergeCells('C1:C2')
            ->mergeCells('D1:D2')
            ->mergeCells('E1:E2')
            ->mergeCells('F1:F2')
            ->setCellValue('A1', '门店编号')
            ->setCellValue('B1', '门店名称')
            ->setCellValue('C1', '省份')
            ->setCellValue('D1', '城市')
            ->setCellValue('E1', 'vip_no')
            ->setCellValue('F1', '手机号码');
        $arr = self::MergeArr(8 + $num * 5);
        // $objectPHPExcel->setActiveSheetIndex(0)->mergeCells('H1:k1');
        //   $objectPHPExcel->setActiveSheetIndex(0)->mergeCells('L1:M1');
        //var_dump($arr);exit;
        foreach ($dates as $k => $v) {

            $objectPHPExcel->setActiveSheetIndex(0)->mergeCells($arr[($k) * 3 + 6] . '1' . ':' . $arr[($k + 1) * 3 + 6 - 1] . '1');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 3 + 6] . '1', $v);
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 3 + 6] . '2', '绑定');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 3 + 7] . '2', '完善资料');
            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k) * 3 + 8] . '2', '签到');
           
        }
     
        foreach ($data as $k => $v) {
            $k = $k + 3;
            $objectPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $k, $v['store']['store_code'])
                ->setCellValue('B' . $k, $v['store']['store_name'])
                ->setCellValue('C' . $k, $v['store']['province'])
                ->setCellValue('D' . $k, $v['store']['city'])
                ->setCellValue('E' . $k, $v['id'])
                 ->setCellValueExplicit('F' . $k, $v['phone'], \PHPExcel_Cell_DataType::TYPE_STRING);
            foreach ($dates as $k1 => $v1) {
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 3 + 6] . $k, $v[$v1]['bind']);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 3 + 7] . $k, $v[$v1]['prefect']);
                $objectPHPExcel->setActiveSheetIndex(0)->setCellValue($arr[($k1) * 3 + 8] . $k, $v[$v1]['sign']);
            }
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $write = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $write->save('php://output');

    }

    /**
     * 读取excel中的字段
     * @param string $file 文件所在路径，若不存在，则返回空数组
     * @param bool $all 是否读取所有sheet
     * @return array
     */
    public static function readExcel($file, $all = false)
    {
        if (!is_file($file)) {
            return [];
        }
        $objPHPExcel = \PHPExcel_IOFactory::load($file);
        if ($all == true) {
            foreach ($objPHPExcel->getAllSheets() as $v) {
                $sheets[] = $v->toArray(null, true, true, true);
            }
        } else {
            $sheets = $objPHPExcel->getSheet()->toArray(null, true, true, true);
        }
        return $sheets;
    }
}
