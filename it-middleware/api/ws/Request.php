<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/28
 * Time: 上午10:41
 */

namespace api\ws;


class Request
{
    /**
     * @var string
     * @soap
     */
    public $id = '';

    /**
     * @var string
     * @soap
     */
    public $pwd = '';


    protected function checkLogin()
    {
        if ($this->id =='' || $this->pwd==''){
            return false;
        }
    }
}