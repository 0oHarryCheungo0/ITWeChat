<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/6
 * Time: 下午2:32
 */

namespace api\ws;

/**
 * Class createResponse
 * @package api\ws
 * @soap
 */
class createResponse
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

    public function __get($key)
    {
        return $this->$key;
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

}