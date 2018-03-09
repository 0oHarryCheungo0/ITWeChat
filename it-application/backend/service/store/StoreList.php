<?php

namespace backend\service\store;

use backend\models\Store;
use common\models\MemberRelation;
use wechat\models\WechatVip;
use Yii;

class StoreList extends Store
{
    /**
     * @param  [type] $limit 获取数据
     * @param  [type]  $offset  开始获取数据
     * @param  string $search 搜索关键字
     * @return [type] array 返回数组
     */
    public static function getList($limit, $offset, $search = '')
    {
        $brand_id = Yii::$app->session->get('brand_id');

        $query = self::find()->where(['brand_id' => $brand_id]);

        if (!empty($search)) {
            $query = $query->andwhere(['like', 'store_code', $search]);
        }
        $total = $query->count();
        $rows  = $query->offset($offset)
            ->limit($limit)
            ->asArray()
            ->all();
        foreach ($rows as $key => $row) {
            $rows[$key]['create_time'] = date('Y-m-d H:i', $row['create_time']);
        }

        return ['total' => $total, 'rows' => $rows];
    }

    /**
     * @param  [type] $id 删除的主键
     * @return [type] 返回结果
     */
    public static function deleteOne($id)
    {

        if ($ret = Store::findById($id)) {
            if ($ret->is_disabled == 1) {
                if (Store::deleteById($id)) {
                    return true;
                }
            } else {
                return false;
            }

        }
        return false;
    }

    public function storeFans($store_id)
    {
        $store_data = self::findOne($store_id);
        //查询当前店铺下的所有粉丝
        $result = MemberRelation::find()->where(['store_id' => $store_id])->select('create_time')->distinct()->asArray()->all();
        $data   = [];
        foreach ($result as $k => $v) {
            $ret = MemberRelation::find()->where(['store_id' => $store_id, 'create_time' => $v['create_time']])->all();
            foreach ($ret as $k => $v) {
                $member_id = $v->member_id;
                if ($this->isBindMember($member_id)) {
                    unset($ret[$k]);
                }
            }
            $data[$k]['total']      = count($ret);
            $data[$k]['date']       = $v['create_time'];
            $data[$k]['store_name'] = $store_data['store_name'];
            $data[$k]['store_code'] = $store_data['store_code'];
        }

        //过滤绑定的会员
        return $data;

    }

    /**
     * 导出新老会员资料
     * @return [type] [description]
     */
    public function exportNewOld($data)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        Yii::info('接收的数据为' . $data);
        $data = json_decode($data, true);

        if (isset($data['search'])) {
            Yii::info('搜索值不为空');
            $all = Yii::$app->db->createCommand('select *,store.store_code as store_code from store left join member_relation on store.id = member_relation.store_id left join wechat_vip on wechat_vip.vip_no=member_relation.member_id where store.store_code like "%' . $data['search'] . '%" and store.brand_id =' . $brand_id)->queryAll();
        } else {
            Yii::info("导出当前品牌下所有店铺资料");
            $all = Yii::$app->db->createCommand('select *,store.store_code as store_code from store left join member_relation on store.id = member_relation.store_id left join wechat_vip on wechat_vip.vip_no=member_relation.member_id where store.brand_id =' . $brand_id)->queryAll();
        }

        \backend\service\Export::newOldMember($all);

    }

    public function exportFans($store_id)
    {
        return $this->storeFans($store_id);

    }

    /**
     * 判断是否为绑定会员
     * @param  [type]  $member_id 会员id
     * @return boolean            true是  false为粉丝
     */
    public function isBindMember($member_id)
    {
        $result = WechatVip::find()->where(['wechat_user_id' => $member_id])->one();

        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

}
