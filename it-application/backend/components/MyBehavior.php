<?php
namespace backend\components;

use backend\models\AdminUser;
use yii\base\Behavior;

class MyBehavior extends Behavior
{
    public $prop1;

    private $_prop2;

    public function getProp2()
    {
        return $this->_prop2;
    }

    public function setProp2($value)
    {
        $this->_prop2 = $value;
    }

    /**
     * 将异步表单数据处理成数组形式
     *
     * @param string $str 源数据
     * @return array $new 转换后数据
     */
    public function unserializeData($str = '')
    {
        $array = explode('&', $str);

        $new = [];
        foreach ($array as $k => $v) {
            $arr = explode('=', $v);
            //var_dump($arr);
            $new[$arr[0]] = $arr[1];
        }

        return $new;
    }

    public function API($data, $code = '', $error = '')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['data' => $data, 'code' => $code, 'error' => $error];
    }

    //获取当前公众号的brand_id;
    public static function Brand_id()
    {
        $id = \yii::$app->user->id;
        return AdminUser::findOne(["id" => $id])['brand_id'];
    }

    /**
     * [getOneError description]
     * @author fushl 2017-05-25
     * @param  array $data [description]
     * @return str      [description]
     */
    public function getOneError($data = [])
    {
        $error = '';
        foreach ($data as $k => $v) {
            $error = $v['0'];
            break;
        }
        return $error;
    }
}
