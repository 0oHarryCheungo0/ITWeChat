<?php
namespace backend\service\discount;

use backend\models\Discount;
use backend\models\Dynamic;
use backend\models\NewsLevelTemp;
use Yii;

class DiscountList
{
    /**
     * [getList description]
     * @author fushl 2017-05-23
     * @param  [type] $limit  [description]
     * @param  [type] $offset [description]
     * @param  string $search [description]
     * @return [type]         [description]
     */
    public function getList($limit, $offset, $search = '')
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $rows     = Dynamic::find()->where(['brand_id' => $brand_id, 'type' => 3]);
        if (!empty($search)) {

        }
        $total  = $rows->count();
        $result = $rows->all();
        return ['total' => $total, 'rows' => $result];
    }

    /**
     * 根据ID获取详情
     * @author fushl 2017-05-26
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getDetail($id,$brand_id,$language ='hk')
    {
        $model    = new Dynamic();
        $result   = $model::find()->where(['id' => $id, 'brand_id' => $brand_id])->asArray()->one();
        if (empty($result)) {
            throw new \Exception('权限不足或优惠详情不存在');
        }
        $data = [];
        $data['title'] = $language =='hk'?$result['hk_title']:$result['title'];
        $data['content'] = $language == 'hk'?$result['hk_content']:$result['content'];
        $data['time'] = date('Y-m-d H:i:s',$result['create_time']);
        return $data;
    }

    /**
     * [delteOne description]
     * @author fushl 2017-05-26
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function deleteOne($id)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        Discount::deleteAll(['news_id' => $id,'type'=>3]);
        $model = Dynamic::find()->where(['id' => $id, 'brand_id' => $brand_id])->one();
        if (empty($model)) {
            throw new \Exception('权限不足');
        }
        $model->delete();
        return true;
    }

    public function getRankList($limit, $offset, $search = '')
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $rows     = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => 3]);
        if (!empty($search)) {
            $rows = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => 3])->andWhere(['like', 'title', $search]);
        }
        $total = $rows->count();
        $rows  = $rows->limit($limit)->offset($offset)->all();
        return ['total' => $total, 'rows' => $rows];
    }

    public function delRank($id)
    {
        $result = NewsLevelTemp::findOne($id);
        Discount::deleteAll(['news_id' => $id, 'type' => 1]);
        $result->delete();
        return true;
    }

    public function pubbirth($id)
    {
        NewsLevelTemp::findOne($id);
    }

    /**
     * 查看常规等级优惠
     * @param  [type] $id       [description]
     * @param  [type] $brand_id [description]
     * @param  [type] $language [description]
     * @return [type]           [description]
     */
    public static function scanNormal($id, $brand_id, $language = 'hk')
    {
        $normal = NewsLevelTemp::find()->where(['id' => $id, 'brand_id' => $brand_id])->asArray()->one();
        Yii::info('查看id'.$id);
        if (empty($normal)) {
            throw new \Exception('权限不足');
        }
        $data= [];
        $data['title']   = $language == 'hk' ? $normal['hk_title'] : $normal['title'];
        $data['content'] = $language == 'hk' ? $normal['hk_content'] : $normal['content'];
        $data['time'] = date('Y-m-d H:i:s',$normal['create_time']);
        return $data;
    }

}
