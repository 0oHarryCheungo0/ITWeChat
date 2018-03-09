<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/7/17
 * Time: 上午10:50
 */

namespace common\vips;


class VIPStruct
{
    public $VIPKO;

    public $ALTID;

    public $VIPNature;

    public $VIPType;

    public $VRID;

    public $Joindate;

    public $StartDate;

    public $Expdate;

    public $Discrate;

    public $EmailSub;

    /**
     * VIPStruct constructor.
     * @param $VIPKO
     */
    public function __construct($VIPKO)
    {
        $this->VIPKO = $VIPKO;
    }

    /**
     * @return mixed
     */
    public function getALTID()
    {
        return $this->ALTID;
    }

    /**
     * @param mixed $ALTID
     */
    public function setALTID($ALTID)
    {
        $this->ALTID = $ALTID;
    }

    /**
     * @return mixed
     */
    public function getVIPNature()
    {
        return $this->VIPNature;
    }

    /**
     * @param mixed $VIPNature
     */
    public function setVIPNature($VIPNature)
    {
        $this->VIPNature = $VIPNature;
    }

    /**
     * @return mixed
     */
    public function getVIPType()
    {
        return $this->VIPType;
    }

    /**
     * @param mixed $VIPType
     */
    public function setVIPType($VIPType)
    {
        $this->VIPType = $VIPType;
    }

    /**
     * @return mixed
     */
    public function getVRID()
    {
        return $this->VRID;
    }

    /**
     * @param mixed $VRID
     */
    public function setVRID($VRID)
    {
        $this->VRID = $VRID;
    }

    /**
     * @return mixed
     */
    public function getJoindate()
    {
        return $this->Joindate;
    }

    /**
     * @param mixed $Joindate
     */
    public function setJoindate($Joindate)
    {
        $this->Joindate = $Joindate;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->StartDate;
    }

    /**
     * @param mixed $StartDate
     */
    public function setStartDate($StartDate)
    {
        $this->StartDate = $StartDate;
    }

    /**
     * @return mixed
     */
    public function getExpdate()
    {
        return $this->Expdate;
    }

    /**
     * @param mixed $Expdate
     */
    public function setExpdate($Expdate)
    {
        $this->Expdate = $Expdate;
    }

    /**
     * @return mixed
     */
    public function getDiscrate()
    {
        return $this->Discrate;
    }

    /**
     * @param mixed $Discrate
     */
    public function setDiscrate($Discrate)
    {
        $this->Discrate = $Discrate;
    }

    /**
     * @return mixed
     */
    public function getEmailSub()
    {
        return $this->EmailSub;
    }

    /**
     * @param mixed $EmailSub
     */
    public function setEmailSub($EmailSub)
    {
        $this->EmailSub = $EmailSub;
    }



}