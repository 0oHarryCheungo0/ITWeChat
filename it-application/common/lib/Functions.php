<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/8/28
 * Time: 下午4:56
 */

namespace common\lib;


class Functions
{

    /**
     * 去除CRM数据库中多余字段的空格
     * @param  [type] &$arrays [description]
     */
    public static function formatBlank(&$arrays)
    {

        foreach ($arrays as $key => $value) {
            if (is_string($value)) {
                $arrays[$key] = trim($value);
            }
        }
    }

}