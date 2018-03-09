<?php

namespace api\response;

use api\lib\XML;

class Response
{
    public $state = 0;

    public $data = null;

    public function __get($key)
    {
        return $this->$key;
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

}
