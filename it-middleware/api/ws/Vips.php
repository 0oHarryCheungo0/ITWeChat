<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/22
 * Time: 下午2:53
 */

namespace api\ws;

/**
 * Class Vips
 * @package api\ws
 * @soap
 */
class Vips
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $return = ['name'=>''];


    public function __set($key,$value){
        $this->key = $value;
    }

    public function getResponse()
    {
        $this->return['name'] = $this->name;
        return $this->return;
    }

}