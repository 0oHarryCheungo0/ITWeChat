<?php
/**
 * @desc 更新并获取vip用户信息
 * @author OF-G40-M70
 */
namespace api\models;

use Yii;

class GetVipInfo
{
    
    /**
     * @desc 通过vipko查找用户，有则返回用户数据，无则返回空数组
     * @param unknown $vipko
     * @return array
     */
    public function GetVipInfoByVIPKO($vipko)
    {
        $statu_code = $this->CheckVIPExist($vipko);
        if($statu_code==300){
            return [];
        }elseif ($statu_code==100){
            $this->DownInfo($vipko);
            $this->UpdateMemberTable($vipko);
        }elseif ($statu_code==200){
            if (!$this->CheckVIPLastTime($vipko)){
                $this->DownInfo($vipko);
                $this->UpdateMemberTable($vipko);
            }
        }
        return $this->FindMember($vipko);
    }
    
    //检查用户是否存在
    public function CheckVIPExist($vipko)
    {
        $member = Member::find('VIPKO=:ko',[':ko'=>$vipko])->one();
        $vip = Vips::find('VIPKO=:ko',[':ko'=>$vipko])->asArray()->one();
        $sql = "select * from VVIP where VIPKO='$vipko'";
        $vvip = Yii::$app->mssql->createCommand($sql)->queryOne();
        if(empty($vvip)){
            return 300;//mssql没有该用户信息
        }elseif (empty($member) && empty($vip)){
            return 100;//要从mssql更新用户信息
        }elseif (empty($member) && !empty($vip)){
            return 100;//要从mssql更新用户信息
        }elseif (!empty($member) && !empty($vip)){
            return 200;//判断时间是否需要更新用户信息
        }
    }
    
    //检查用户最后更新的时间
    public function CheckVIPLastTime($vipko)
    {
        $sql = "select LAST_UPDATE from VIP_UPDATE where VIPKO='$vipko'";
        $vipupdate = \Yii::$app->db->createCommand($sql)->queryOne();
        if(!$vipupdate) return false;
        $lasttime = time()-strtotime($vipupdate['LAST_UPDATE']);
        if($lasttime>=Yii::$app->params['vipinfo-time']){
            return false;
        }else{
            return true;//没到指定时间不用更新
        }
    }
    
    //在mssql下载并更新用户数据
    public function DownInfo($vipko)
    {
        $sql = "select * from VVIP where VIPKO='$vipko'";
        $vipdata = Yii::$app->mssql->createCommand($sql)->queryOne();
        if(!empty($vipdata)){
            $altid = $vipdata['AltID'];
            $vip = Vips::find('VIPKO=:ko',[':ko'=>$vipko])->one();
            if(!$vip){
                $vip = new Vips();
            }
            $vip->VIPKO     = $vipko;
            $vip->ALTID     = $altid;
            $vip->VIPNature = $vipdata['VIPNature'];
            $vip->VIPType   = $vipdata['VIPType'];
            $vip->VRID      = $vipdata['VRID'];
            $vip->Joindate  = $vipdata['JoinDate'];
            $vip->Startdate = $vipdata['StartDate'];
            $vip->Expdate   = $vipdata['ExpDate'];
            $vip->Discrate  = $vipdata['DiscRate'];
            $vip->EmailSub  = $vipdata['EmailSub'];
            $ret = $vip->save();
            
            $sql = "select * from VPFILE where ALTID='$altid'";
            $infodata = Yii::$app->mssql->createCommand($sql)->queryOne();
            if(!empty($infodata)){
                $vipinfo = VipInfos::find('ALTID=:aid',[':aid'=>$altid])->one();
                if(!$vipinfo){
                    $vipinfo = new VipInfos();
                }
                $vipinfo->ALTID     = $infodata['ALTID'];
                $vipinfo->VIPName   = $infodata['VIPNAME'];
                $vipinfo->Addr1     = $infodata['ADDR1'];
                $vipinfo->Addr2     = $infodata['ADDR2'];
                $vipinfo->Emailaddr = $infodata['EMAILADDR'];
                $vipinfo->MOB       = $infodata['MOB'];
                $vipinfo->DOB       = $infodata['DOB'];
                $vipinfo->Telno     = $infodata['TELNO'];
                $vipinfo->Sex       = $infodata['SEX'];
                $ret = $vipinfo->save();
            }
            return true;
        }else{
            return false;
        }
    }
    
    //更新member表用户信息
    public function UpdateMemberTable($vipko)
    {
        $member = Member::find('VIPKO=:ko',[':ko'=>$vipko])->one();
        if(!$member){
            $member = new Member();
        }
        $vip = Vips::find('VIPKO=:ko',[':ko'=>$vipko])->asArray()->one();
        $vipinfo = VipInfos::find('ALTID=:aid',[':ko'=>$vip['ALTID']])->asArray()->one();
        $member->VIPKO     = $vip['VIPKO'];
        $member->ALTID     = $vip['ALTID'];
        $member->VIPType   = $vip['VIPType'];
        if(!empty($vipinfo)){
            $member->Telno     = $vipinfo['Telno'];
            $member->Sex       = $vipinfo['Sex'];
            $member->Addr1     = $vipinfo['Addr1'];
            $member->Addr2     = $vipinfo['Addr2'];
            $member->DOB       = $vipinfo['DOB'];
            $member->MOB       = $vipinfo['MOB'];
            $member->VIPName   = $vipinfo['VIPName'];
            $member->Emailaddr = $vipinfo['Emailaddr'];
        }
        $member->Joindate  = $vip['Joindate'];
        $member->Startdate = $vip['Startdate'];
        $member->Expdate   = $vip['Expdate'];
        if($member->save()){
            $sql = "select id from VIP_UPDATE where VIPKO='$vipko'";
            $ret = \Yii::$app->db->createCommand($sql)->queryOne();
            if(!$ret){
                $sql = "insert into VIP_UPDATE(VIPKO,LAST_UPDATE)values('$vipko','".date('Y-m-d H:i:s')."')";
            }else{
                $sql = "update VIP_UPDATE set LAST_UPDATE='".date('Y-m-d H:i:s')."' where VIPKO='$vipko'";
            }
            Yii::$app->db->createCommand($sql)->execute();
            return true;
        }else{
            return false;
        }
    }
    
    //获取用户信息
    public function FindMember($vipko)
    {
        $member = Member::find('VIPKO=:ko',[':ko'=>$vipko])->asArray()->one();
        return $member;
    }
}