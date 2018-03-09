<?php
namespace app\models;

use Yii;

class UpdateVipInfo
{
    public $data;
    
    public $vipko;
    
    public $altid;
    
    public $error;
    
    public function receiveInfo($data,$vipko,$altid)
    {
        $this->data = $data;
        $this->vipko = $vipko;
        $this->altid = $altid;
        
        $member_ret = $this->UpdateMember();
        $vip_ret = $this->UpdateVip();
        $info_ret = $this->UpdateInfo();
        $vvip_ret = $this->UpdateVVip();
        $file_ret = $this->UpdateVIPFILE();
        if(!$member_ret || !$info_ret || !$vvip_ret || !$file_ret){
            return $this->error;
        }else{
            return true;
        }
    }
    
    public function UpdateMember()
    {
        $member = Member::find('VIPKO=:vid',[':vid'=>$this->vipko])->one();
        $member->VIPType   = $this->data['VIPType'];
        $member->VIPName   = $this->data['VIPName'];
        $member->Addr1     = $this->data['Addr1'];
        $member->Addr2     = $this->data['Addr2'];
        $member->Telno    = $this->data['Telno'];
        $member->Sex       = $this->data['Sex'];
        $member->Emailaddr = $this->data['Emailaddr'];
        $member->DOB       = $this->data['DOB'];
        $member->MOB       = $this->data['MOB'];
        $member->Joindate  = $this->data['Joindate'];
        $member->Startdate = $this->data['Startdate'];
        $member->Expdate   = $this->data['Expdate'];
        if ($member->save()){
            return true;
        }else{
            $this->error = 'Member update failed';
            return false;
        }
    }
    
    public function UpdateVip()
    {
        $vip = Vips::find('VIPKO=:vko',[':vko'=>$this->vipko])->one();
        $vip->VIPNature = $this->data['VIPNature'];
        $vip->VIPType = $this->data['VIPType'];
        $vip->VRID = $this->data['VRID'];
        $vip->Joindate  = $this->data['Joindate'];
        $vip->Startdate = $this->data['Startdate'];
        $vip->Expdate   = $this->data['Expdate'];
        $vip->Discrate   = $this->data['Discrate'];
        $vip->EmailSub   = $this->data['Emailsub'];
        if($vip->save()){
            return true;
        }else {
            $this->error = 'VIP update failed';
            return false;
        }
    }
    
    public function UpdateInfo()
    {
        $info = VipInfos::find('ALTID')->one();
        $info->VIPName = $this->data['VIPName'];
        $info->Addr1 = $this->data['Addr1'];
        $info->Addr2 = $this->data['Addr2'];
        $info->Telno = $this->data['Telno'];
        $info->Sex = $this->data['Sex'];
        $info->DOB = $this->data['DOB'];
        $info->MOB = $this->data['MOB'];
        $info->Emailaddr = $this->data['Emailaddr'];
        if($info->save()){
            return true;
        }else {
            $this->error = 'Info update failed';
            return false;
        }
    }
    
    public function UpdateVVip()
    {
        $vipko = $this->vipko;
        $sql = "select VIPKO from TVIP where VIPKO='$vipko'";
        $ret = Yii::$app->mssql->createCommand($sql)->queryOne();
        if(empty($ret)){
            $sql = "update PVIP set VRID='".$this->data['VRID']."',VIPTYPE='".$this->data['VIPType']."',"
                  ."VIPNATURE='".$this->data['VIPNature']."',EXPDATE='".$this->data['Expdate']."',"
                  ."STARTDATE='".$this->data['Startdate']."',JOINDATE='".$this->data['Joindate']."',"
                  ."EMAILSUB='".$this->data['Emailsub']."' where VIPKO='$vipko'";
        }else{
            $sql = "update TVIP set VRID='".$this->data['VRID']."',VIPTYPE='".$this->data['VIPType']."',"
                    ."VIPNATURE='".$this->data['VIPNature']."',EXPDATE='".$this->data['Expdate']."',"
                    ."STARTDATE='".$this->data['Startdate']."',JOINDATE='".$this->data['Joindate']."',"
                    ."EMAILSUB='".$this->data['Emailsub']."' where VIPKO='$vipko'";
        }
        $ret = Yii::$app->mssql->createCommand($sql)->execute();
        if($ret){
            return true;
        }else{
            echo $sql;die;
            $this->error = 'VVIP update failed';
            return false;
        }
    }
    
    public function UpdateVIPFILE()
    {
        $sql = "select ALTID from VPFILE where ALTID='$this->altid'";
        $ret = \Yii::$app->mssql->createCommand($sql)->queryOne();
        if(empty($ret)){
            $sql = "insert into VPFILE(ALTID,VIPNAME,ADDR1,ADDR2,TELNO,SEX,DOB,EMAILADDR,MOB) "
                ."values('$this->altid','".$this->data['VIPName']."',"
                ."'".$this->data['Addr1']."','".$this->data['Addr2']."','".$this->data['Telno']."',"
                ."'".$this->data['Sex']."','".$this->data['DOB']."','".$this->data['Emailaddr']."',"
                ."'".$this->data['MOB']."');";
        }else{
            $sql = "update VPFILE set VIPNAME='".$this->data['VIPName']."',"
                ."ADDR1='".$this->data['Addr1']."',ADDR2='".$this->data['Addr2']."',"
                ."TELNO='".$this->data['Telno']."',SEX='".$this->data['Sex']."',"
                ."DOB='".$this->data['DOB']."',MOB='".$this->data['MOB']."',"
                ."EMAILADDR='".$this->data['Emailaddr']."' where ALTID='$this->altid'";
        }
        $ret = \Yii::$app->mssql->createCommand($sql)->execute();
        if($ret){
            return true;
        }else{
            $this->error = 'VIPFILE update failed';
            return false;
        }
    }
}