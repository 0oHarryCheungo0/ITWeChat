<?php 
namespace wechat\controllers;

use Yii;
use common\models\WechatScanLog;
use common\api\WxPushApi;

class TestController extends VipBase{
	public function actionAa(){
		$type = Yii::$app->request->get('type');
		$uid  = Yii::$app->session->get('wechat_id');
		
		$ret = WxPushApi::upgrade($uid, $type);
		if ($ret != true){
			var_dump($ret);
		}
	}

	public function actionBb(){
		$type = Yii::$app->request->get('type');
		$uid  = Yii::$app->session->get('wechat_id');
		$add_point = 500;
		$ret = WxPushApi::point($uid, $type,$add_point);
		if ($ret != true){
			var_dump($ret);
		}
	}

	public function actionCc(){

		$log = WechatScanLog::findOne(1);
		$scan_key  =$log->scan_key;
		$openid = $log->openid;
		$scan_date = $log->scan_date;
		Yii::info('scan_key:'.$scan_key.'openid:'.$openid);
		\backend\service\fans\FansList::addFans($scan_key,$openid,$scan_date);
	}
}