<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/28
 * Time: 上午10:39
 */

namespace api\ws;

use api\lib\XML;

class Response
{

    /**
     * @var int
     * @soap
     */
    public $code = 0;

    /**
     * @var string
     * @soap
     */
    public $response = '';

    /**
     * @var string
     * @soap
     */
    public $message = '';

    protected function toXML($data)
    {
        return XML::build($data, 'response');
    }


    protected function parseResponse($data)
    {
        $this->response = $data;
        if (is_string($this->response)) {
            return $this->response;
        } else if (is_array($this->response)) {
            return $this->response = $this->toXML($this->response);
        } else {
            return null;
        }

    }

    protected function codeMap()
    {
        switch ($this->code) {
            case 1001:
                $this->message = '登录信息错误';
                break;
            case 1002:
                $this->message = '账号已被禁用';
                break;
            default:
                $this->message = 'SUCCESS';
        }
        return $this;
    }
}