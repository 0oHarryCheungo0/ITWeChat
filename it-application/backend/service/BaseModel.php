<?php

namespace backend\service;

use Yii;
use yii\base\Model;

class BaseModel extends Model
{
    /**
     * 获取单条错误信息
     * @author fushl 2017-05-25
     * @return [type] [description]
     */
    public function getOneError()
    {
        $data = $this->errors;
        $error = '';
        foreach ($data as $k => $v) {
            $error = $v['0'];
            break;
        }
        return $error;
    }

    /**
     * 加载表单unserialize处理的数据
     * @author fushl 2017-05-25
     * @param  string $str [description]
     * @return [type]      [description]
     */
    public function loadAjax($str = '')
    {
        if ($this->load(self::unserializeData($str), '')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将异步表单数据处理成数组形式
     *
     * @param string $str 源数据
     * @return array $new 转换后数据
     */
    public static function unserializeData($str = '')
    {
        $array = explode('&', $str);
        $new = [];
        foreach ($array as $k => $v) {
            $arr = explode('=', $v);
            $new[$arr[0]] = $arr[1];
        }
        return $new;
    }

    /**
     * 批量插入
     * @author fushl 2017-05-26
     * @param  [type] $table [description] 需要插入的表
     * @param  [type] $rows  [description] 需要插入的列
     * @param  [type] $data  [description] 需要插入的数据
     * @return [type]        [description] false | true
     */
    public function insertAll($table, $rows, $data)
    {
        $ret = Yii::$app->db
            ->createCommand()
            ->batchInsert($table, $rows, $data)
            ->execute();
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

}
