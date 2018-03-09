<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/28
 * Time: 下午2:33
 */

namespace api\ws;


class VipInfo
{
    /**
     * @var string
     * @soap
     */
    public $TELNO;

    /**
     * @var string
     * @soap
     */
    public $CountryCode;

    /**
     * @var string
     * @soap
     */
    public $Mobile;

    /**
     * @var string
     * @soap
     */
    public $VIPName;

    /**
     * @var string
     * @soap
     */
    public $FirstName;

    /**
     * @var string
     * @soap
     */
    public $LastName;

    /**
     * @var string
     * @soap
     */
    public $Sex;


    /**
     * @var string
     * @soap
     */
    public $Emailaddr;

    /**
     * @var string
     * @soap
     */
    public $eComDOB;

    /**
     * @var string
     * @soap
     */
    public $MOB;

    /**
     * @var string
     * @soap
     */
    public $YOB;

    /**
     * @var string
     * @soap
     */
    public $DOB;

    /**
     * @var string
     * @soap
     */
    public $Addr1;

    /**
     * @var string
     * @soap
     */
    public $Addr2;

    /**
     * @var string
     * @soap
     */
    public $Country;

    /**
     * @var string
     * @soap
     */
    public $Province;

    /**
     * @var string
     * @soap
     */
    public $City;
    /**
     * @var string
     * @soap
     */
    public $District;
    /**
     * @var string
     * @soap
     */
    public $DetailAddress;

    /**
     * @var string
     * @soap
     */
    public $eComEmailSub;

    /**
     * @var string
     * @soap
     */
    public $EmailSub;

    /**
     * @var string
     * @soap
     */
    public $VIPKO;

    /**
     * @var string
     * @soap
     */
    public $VIPType;

    /**
     * @var string
     * @soap
     */
    public $JoinDate;
    /**
     * @var string
     * @soap
     */
    public $StartDate;
    /**
     * @var string
     * @soap
     */
    public $ExpDate;


    function __set($name, $value)
    {
        $this->$name = $value;
    }
}