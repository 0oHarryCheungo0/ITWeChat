<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/28
 * Time: 上午10:21
 */

namespace api\ws;


/**
 * Class VipResponse
 * @package api\ws
 * @soap
 */
class VipResponse
{
    /**
     * @var int
     * @soap
     */
    public $status = 0;

    /**
     * @var string
     * @soap
     */
    public $message = '';

    /**
     * @var \api\ws\VipInfo
     * @soap
     */
    public $vipInfo;


    public function __get($key)
    {
        return $this->$key;
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

}