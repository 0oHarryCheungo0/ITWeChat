<?php 
namespace backend\service\member;

use wechat\models\WechatUser;
use backend\models\TempGroup;
use yii\helpers\ArrayHelper;


class MemberList{

	public static function getList($brand_id,$limit,$offset,$condition = []){
		$list = WechatUser::find()->where(['wechat_user.brand'=>$brand_id])->joinWith('store')->joinWith('infom');

		//用户名搜索
		if (!empty($condition['nickname'])){
			$list = $list->andWhere(['wechat_user.nickname'=>$condition['nickname']]);
		}
		//手机号码搜索
		if (!empty($condition['phone'])){
			$list = $list->andWhere(['wechat_vip.phone'=>$condition['phone']]);
		}
		//根据openid搜索
		if (!empty($condition['openid'])){
			$list = $list->andWhere(['wechat_user.openid'=>$condition['openid']]);
		}
		//VipType搜索
		if (!empty($condition['vip_type'])) {
			$list = $list->andWhere(['wechat_vip.vip_type'=>$condition['vip_type']]);
		}
		//memberShipId模糊搜索
		if (!empty($condition['member_id'])) {
			$list = $list->andWhere(['like','wechat_vip.member_id',$condition['member_id']]);
		}
		//生日搜索
		if (!empty($condition['birthday'])) {
			$list = $list->andWhere(['wechat_vip.birthday'=>$condition['birthday']]);
		}
		//城市搜索
		if (!empty($condition['city'])) {
			$list = $list->andWhere(['wechat_user.city'=>$condition['city']]);
		}
		//绑定门店搜索
		if (!empty($condition['store'])) {
			$list = $list->andWhere(['store.id'=>intval($condition['store'])]);
		}
		//绑定时间
		if (!empty($condition['bind_time'])) {
			$list = $list->andWhere(['member_relation.create_time'=>$condition['bind_time']]);
		}
		$rows = $list->all();
		$pages = $list->count();
		return ['rows'=>$rows,'total'=>$pages];
	}

	public function addGroup(){

	}

	public function export($data = []){
		$model = WechatUser::find()->where(['brand'=>$brand_id]);
		if ($search = ArrayHelper::getValue($data,'search')) {
			$model = $model->andWhere(['name'=>$search]);
		}
		if (!empty($data['title'])) {

		}
	}
}