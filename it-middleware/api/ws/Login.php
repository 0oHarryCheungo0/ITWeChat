<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/28
 * Time: 上午11:19
 */

namespace api\ws;

use yii;

class Login
{
    /**
     * @var string
     */
    public $account;

    /**
     * @var string
     */
    public $pwd;

    public function __construct($account = false, $pwd = false)
    {
        $this->account = $account;
        $this->pwd = $pwd;
    }

    public function checkLogin()
    {
        if (!$this->account || !$this->pwd) {
            Yii::error('登录信息错误:user->' . $this->pwd . ';pwd->' . $this->pwd, 'ERROR');
            return 1001;
        }

        if ($this->account == '1') {
            Yii::error($this->account . '账号被禁用', 'ERROR');
            return 1002;
        }
        //TODO 查询数据库，获取用户，并记录访问信息
        return true;

    }
}