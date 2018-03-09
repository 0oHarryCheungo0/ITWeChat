<?php
namespace api\lib;

class Functions
{

    /**
     * 去除CRM数据库中多余字段的空格
     * @param  [type] &$arrays [description]
     * @return [type]          [description]
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
