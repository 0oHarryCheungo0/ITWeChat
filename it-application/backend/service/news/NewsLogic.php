<?php

namespace backend\service\news;

use backend\models\Dynamic;
use backend\models\NewsQueue;
use backend\models\User;
use backend\service\BaseModel;
use common\middleware\Queue;
use wechat\models\WechatVip;
use Yii;

class NewsLogic extends BaseModel
{
    //错误信息
    public $error = '';

    /**
     * 资讯列表
     * @author fushl 2017-05-26
     * @param  [type] $limit  [description]
     * @param  [type] $offset [description]
     * @param  [type] $type   [description]
     * @param  [type] $search [description]
     * @return [type]         [description]
     */
    public static function getList($limit, $offset, $type, $search)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $model    = Dynamic::find()->where(['brand_id' => $brand_id]);
        $model    = $model->andWhere(['type' => $type]);
        if (!empty($search)) {
            $model = $model->andWhere(['like', 'title', $search]);
        }
        $total = $model->count();
        $rows  = $model->offset($offset)->limit($limit)->all();
        return ['total' => $total, 'rows' => $rows];
    }

    /**
     * 发布资讯
     * @author fushl 2017-05-26
     * @param  [type] $str   [description]
     * @param  [type] $group [description]
     * @return [type]        [description]
     */
    public function send($id)
    {

        $data = NewsQueue::findOne($id);
        $dynamic = Dynamic::findOne($data['news_id']);
        $end_time = $dynamic->end;
        if (empty($data)) {
            throw new \Exception('数据异常id不存在');
        } else {
            $user = $this->getUser($data['brand_id']);
            $ret  = $this->sendOne($user, $data['news_id'],$end_time);
            if ($ret) {
                $data->is_send = 1;
                $data->save();
            }
        }

        return true;

    }

    /**
     * 存入队列
     * @param  [type] $group [description]
     * @param  [type] $str   [description]
     * @return [type]        [description]
     */
    public function saveQueue($str)
    {
        if (empty($str)) {
            $this->error = '资讯不能为空';
            return false;
        }
        $news     = $this->getNews($str);
        $data     = [];
        $brand_id = Yii::$app->session->get('brand_id');
        foreach ($news as $k => $v) {
            $model           = new NewsQueue();
            $model->news_id  = $v;
            $model->vip_type = '';
            $model->brand_id = $brand_id;
            if ($model->save()) {
                $id = $model->getOldPrimaryKey();
                Queue::sendNews($id);
            }
        }
        return true;
    }

    /**
     * 发布单条动态资讯
     * @author fushl 2017-05-26
     * @param  [type] $user    [description] 用户组
     * @param  [type] $news_id [description] 资讯id
     * @return [type]          [description] true|false
     */
    public function sendOne($user, $news_id, $end_time = 0)
    {
        $insert = [];
        $time   = time();
        foreach ($user as $k => $v) {
            $insert[$k]['uid']         = $v['vip_no'];
            $insert[$k]['news_id']     = $news_id;
            $insert[$k]['create_time'] = $time;
            $insert[$k]['is_look']     = 0;
            $insert[$k]['type']        = 3;
            $insert[$k]['end_time']    = $end_time;
        }
        //发布动态资讯
        $result = $this->insertAll('news_point', ['uid', 'news_id', 'create_time', 'is_look', 'type', 'end_time'], $insert);
        return $result;
    }

    /**
     * 获取资讯id
     * @author fushl 2017-05-26
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    public function getNews($str)
    {
        $data = [];
        $data = explode(',', trim($str, ','));
        return $data;
    }

    /**
     * 获取需要发布的用户
     * @author fushl 2017-05-26
     * @param  [type] $group [description]
     * @return [type]        [description]
     */
    public function getUser($brand_id)
    {
        $data = WechatVip::find()->where(['brand' => $brand_id])->asArray()->all();
        return $data;
    }

    /**
     * 获取资讯详情
     * @author fushl 2017-05-26
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function detail($id, $brand_id, $language)
    {
        $news = Dynamic::find()->where(['id' => $id, 'brand_id' => $brand_id])->asArray()->one();

        if (empty($news)) {
            throw new \Exception('权限不足或内容不存在');
        }
        $data            = [];
        $data['title']   = $language == 'hk' ? $news['hk_title'] : $news['title'];
        $data['content'] = $language == 'hk' ? $news['hk_content'] : $news['content'];
        $data['time']    = date('Y-m-d H:i:s', $news['create_time']);
        return $data;
    }

}
