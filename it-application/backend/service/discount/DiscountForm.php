<?php

namespace backend\service\discount;

use backend\models\Discount;
use backend\models\NewsQueue;
use backend\models\Dynamic;
use backend\service\BaseModel;
use wechat\models\WechatVip;
use Yii;

/**
 * 限时优惠表单
 */
class DiscountForm extends BaseModel
{
    public $title;
    public $content;
    public $create_time;
    public $end;
    public $group;
    public $hk_title;
    public $hk_content;
    public $member_rank;
    public $send_time;
    public $type;

    public function rules()
    {
        return [

            ['title', 'required'],
            ['hk_title', 'required'],
            ['send_time', 'string'],
            ['type', 'required'],
            ['hk_content', 'required'],
            ['title', 'string', 'max' => 15],
            ['content', 'required'],
            ['content', 'string', 'max' => 255],
            ['end', 'required'],
            ['member_rank', 'required'],
        ];
    }

    /**
     * 发布限时优惠
     * @author fushl 2017-05-24
     * @return [type] [description]
     */
    public function save()
    {
        $brand_id            = Yii::$app->session->get('brand_id');
        $models              = new Dynamic();
        $models->member_rank = $this->member_rank;
        $models->title       = $this->title;
        $models->content     = $this->content;
        $models->create_time = time();
        $models->brand_id    = $brand_id;
        $models->send_time   = strtotime($this->send_time);
        $models->hk_title    = $this->hk_title;
        $models->hk_content  = $this->hk_content;
        $models->end         = strtotime($this->end);
        $models->type        = $this->type;
        $models->save();
        return true;

    }

    public function send($id)
    {
        $queue = NewsQueue::findOne($id);
        Yii::info('资讯队列id' . $id);
        $news_id  = $queue['news_id'];
        $vip_type = $queue['vip_type'];
        $brand_id = $queue['brand_id'];
        $ret = Dynamic::findOne($news_id);
        $end_time = $ret['end'];
        $ret->is_send = 1;
        $ret->create_time = time();
        $ret->save();
        $users    = WechatVip::find()->where(['vip_type'=>$vip_type, 'brand'=>$brand_id])->all();
        $insert   = [];
        $time     = time();
        foreach ($users as $k => $v) {
            $insert[$k]['uid']         = $v['vip_no'];
            $insert[$k]['news_id']     = $news_id;
            $insert[$k]['create_time'] = $time;
            $insert[$k]['end_time']    = $end_time;
            $insert[$k]['is_look']     = 0;
            $insert[$k]['type']        = 3;
        }
        //发布动态资讯
        $result = $this->insertAll('discount', ['uid', 'news_id', 'create_time', 'end_time', 'is_look', 'type'], $insert);
        if ($result) {
            $queue->is_send = 1;
            $queue->save();
        }

    }

    public function update($id)
    {
        $brand_id            = Yii::$app->session->get('brand_id');
        $models              = Dynamic::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();
        $models->member_rank = $this->member_rank;
        $models->title       = $this->title;
        $models->content     = $this->content;
        $models->create_time = time();
        $models->brand_id    = $brand_id;
        $models->hk_title    = $this->hk_title;
        $models->send_time   = strtotime($this->send_time);
        $models->hk_content  = $this->hk_content;
        $models->end         = strtotime($this->end);
        $models->type        = $this->type;
        $models->save();
        return true;
    }

    /**
     * [attributeLabels description]
     * @author fushl 2017-05-26
     * @return [type] [description]
     */
    public function attributeLabels()
    {
        return [
            'title'   => '标题',
            'content' => '优惠详情',
            'end'     => '结束时间',
        ];

    }
}
