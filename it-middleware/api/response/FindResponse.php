<?php
namespace api\response;

class FindResponse extends Response
{
    public function __construct($data, $code = 0, $msg = 'SUCCESS')
    {
        $this->_data = $data;
        $this->_code = $code;
        $this->_msg  = $msg;
    }

}
