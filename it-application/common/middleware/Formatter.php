<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/8
 * Time: 下午6:09
 */

namespace common\middleware;


class Formatter
{
    /**
     * 中间件返回值格式化，
     * @param array $records 记录数组
     * @param string $date_key 日期键值
     * @return array
     */
    public static function byMonth($records, $date_key)
    {
        //判断传入$records是否为数组，若不是数组，则返回空数组
        if (!is_array($records)) {
            return [];
        }
        //判断条件参数 $date_key是否存在，若不存在，则返回空数组
        if (!isset($records[0]) || !isset($records[0][$date_key])) {
            return [];
        }

        //按照年份，月份来处理
        $month_data = [
            'December' => [],
            'Novenber' => [],
            'October' => [],
            'September' => [],
            'August' => [],
            'July' => [],
            'June' => [],
            'May' => [],
            'April' => [],
            'March' => [],
            "February" => [],
            'January' => [],
        ];
        $total = [];
        //遍历记录，把对应月份的数据填充到数组中。
        foreach ($records as $record) {
            $total[self::whichYear($record[$date_key])][self::whichMonth($record[$date_key])][] = $record;;
        }
        return $total;
    }


    /**
     * 日期字符串转换为英文月份字段
     * @param $time_sting
     * @return false|string
     */
    public static function whichMonth($time_string)
    {
        return date('F', strtotime($time_string));
    }

    public static function whichYear($time_string)
    {
        return date('Y', strtotime($time_string));
    }

}