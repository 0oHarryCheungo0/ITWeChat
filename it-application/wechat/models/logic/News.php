<?php

namespace wechat\models\logic;

use backend\models\Brand;
use backend\models\DynamicNews as Dynamic;
use common\models\NewsMember;
use common\models\NewsPoint;
use Yii;

/**
 * 会员资讯初始化发布
 */
class News extends BaseNews
{
    //未查看
    const NOLOOK = 0;
    //会员等级
    const RANKTYPE = 1;
    //会员到期模版类型
    const TYPE = 2;
    //一月子分类
    const CTYPE = 1;
    //三月之分类
    const TTYPE = 2;
    //会员一个月到期模版
    const ONE = 3;
    //会员三个月到期
    const THREE = 2;
    //每页多个
    const OFF = 1;

    public function getUserRank($uid, $brand_id, $rank)
    {

        $this->sendNews($brand_id, $rank, $uid);

    }

    /**
     *  发布会员等级资讯
     * @param  [type] $brand_id 品牌id
     * @param  int    $rank     vip_type对应的键
     * @param  [type] $uid      会员id
     * @return [type]           [description]
     */
    public static function sendNews($brand_id, $vip_no, $news_id)
    {
        $end_time           = self::getEnd($news_id, 1);
        $model              = new NewsMember();
        $model->is_look     = self::NOLOOK;
        $model->create_time = time();
        $model->news_id     = $news_id;
        $model->end_time    = $end_time != false ? $end_time : 0;
        $model->type        = self::RANKTYPE;
        $model->uid         = $vip_no;
        $ret  = $model->save();
        if ($ret){
            return true;
        }else{
            return false;
        }
    }

    public static function sendExpireNews($brand_id, $vip_no, $news_id)
    {
        $type     = self::getChildren($news_id);
        $end_time = self::getEnd($news_id, 1);
        if (!empty($type)) {
            $result = false;
            if ($type == 1) {
                //一个月到期资讯
                $result = self::send($vip_no, $news_id, $brand_id, self::ONE, $end_time);
            }

            if ($type == 2) {
                //三个月到期资讯
                $result = self::send($vip_no, $news_id, $brand_id, self::THREE, $end_time);
            }
            if ($result != false) {
                return true;
            } else {
                return false;
            }

        }
    }


    public static function send($vip_no, $news_id, $brand_id, $send_type, $end_time)
    {
        $current_time = time();
        $ret = NewsMember::find()->where(['uid'=>$vip_no,'type'=>$send_type])->andWhere(['>','end_time',$current_time])->one();
        if (!empty($ret)){
            \Yii::error('此条消息已发送过');
            return true;
        }
        $model              = new NewsMember();
        $model->create_time = time();
        $model->uid         = $vip_no;
        $model->news_id     = $news_id;
        $model->end_time    = $end_time;
        $model->type        = $send_type;
        $model->is_look     = self::NOLOOK;
        $result = $model->save();
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取会员资讯未读条数
     * @author 2017-06-05
     * @param  integer $uid 用户id
     * @return integer      未读条数
     */
    public static function getMemberNews($uid)
    {
        $current = time();
        return NewsMember::find()->where(['is_look' => 0, 'uid' => $uid])->andWhere(['or', ['end_time' => 0], ['>', 'end_time', $current]])->count();
    }

    /**
     * 获取会员动态资讯未读条数
     * @author 2017-06-05
     * @param  integer $uid [description]
     * @return [type]       [description]
     */
    public static function getDynamic($uid = 1)
    {
        $result = Dynamic::find()->where(['is_look' => 0, 'uid' => $uid])->count();
        if (!empty($result)) {
            return $result;
        }
    }

    /**
     * 获取积分资讯未读条数
     * @author 2017-06-05
     * @param  integer $uid [description]
     * @return [type]       [description]
     */
    public static function getPoint($uid = 1)
    {
        $current = time();
        $point   = NewsPoint::find()->where(['is_look' => 0, 'uid' => $uid])->andWhere(['or', ['end_time' => 0], ['>', 'end_time', $current]])->count();
        return $point;
    }

    /**
     * 获取所有资讯未读条数
     * @author 2017-06-05
     * @return [type] [description]
     */
    public static function getTotal($uid)
    {
        $vip = \wechat\models\WechatVip::find()->where(['wechat_user_id'=>$uid])->one();
        if (!empty($vip)){
            $uid = $vip->vip_no;
        }else{
            $uid = 0;
        }
        //return self::getMemberNews($uid)  + self::getPoint($uid)+ self::getDynamic($uid);
        return self::getMemberNews($uid) + self::getPoint($uid);
    }

    /**
     * 获取会员资讯列表
     * @author
     * @return [type] [description]
     */
    public function getMemberList($uid, $page = 1)
    {
        $language = Yii::$app->language;
        $data     = [];
        $current  = time();
        $result   = NewsMember::find()->select(['id', 'title', 'type', 'is_look', 'create_time', 'news_id','end_time'])->where(['uid' => $uid])->andWhere(['or', ['end_time' => 0], ['>', 'end_time', $current]])->with('template')->asArray()->all();

        if (!empty($result)) {
            foreach ($result as $k => $v) {

                $data[$k]['id']          = $v['id'];
                $data[$k]['title']       = $language == 'hk' ? $v['template']['hk_title'] : $v['template']['title'];
                $data[$k]['type']        = $v['type'];
                $data[$k]['is_look']     = $v['is_look'];
                $data[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            }
        }

        return ['data' => $data];
        //return ['data' => $data, 'pages' => $pages];
    }

    /**
     * 获取动态资讯列表
     * @author
     * @param  [type] $uid  [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public function getDynamicList($uid, $page)
    {
        $data   = [];
        $result = Dynamic::find()->where(['uid' => $uid, 'type' => 1])->all();
        if (!empty($result)) {
            foreach ($result as $k => $v) {
                $data[$k]['id']          = $v->id;
                $data[$k]['title']       = $v->news->title;
                $data[$k]['create_time'] = $v->news->create_time;
                $data[$k]['type']        = $v->type;
                $data[$k]['is_look']     = $v->is_look;
            }
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 获取积分资讯列表
     * @author
     * @param  [type] $uid  [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public function getPointList($uid, $page = '')
    {
        $new      = [];
        $language = Yii::$app->language;
        $current  = time();
        $data     = NewsPoint::find()->where(['uid' => $uid])->andWhere(['or', ['end_time' => 0], ['>', 'end_time', $current]])->with('news')->orderBy('create_time desc')->asArray()->all();
        foreach ($data as $k => $v) {
            if ($v['type'] == 3) {
                Yii::info('动态积分');
                $new[$k]['type']        = $v['type'];
                $new[$k]['is_look']     = $v['is_look'];
                $new[$k]['id']          = $v['id'];
                $new[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $new[$k]['title']       = $language == 'hk' ? $v['news']['hk_title'] : $v['news']['title'];
                $new[$k]['content']     = $language == 'hk' ? $v['news']['hk_content'] : $v['news']['content'];
            } else {
                $new[$k]['title']       = $language == 'hk' ? $v['hk_title'] : $v['title'];
                $new[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                $new[$k]['type']        = $v['type'];
                $new[$k]['is_look']     = $v['is_look'];
                $new[$k]['id']          = $v['id'];
            }

        }

        return ['data' => $new];
    }

    /**
     * 获取资讯详情
     * @author 2017-06-05
     * @param  integer $id   资讯主键id
     * @param  integer $type 资讯类型表1,会员资讯。2,动态资讯。3,积分资讯
     * @return [type]        资讯详情
     */
    public function getDetail($id = 1, $type = 1)
    {

        switch ($type) {
            case 1:
                //会员资讯
                $language        = Yii::$app->language;
                $result          = NewsMember::findOne($id);
                $result->title   = $language == 'hk' ? $result->template->hk_title : $result->template->title;
                $result->content = $language == 'hk' ? $result->template->hk_content : $result->template->content;
                $result->is_look = 1;
                $result->save();
                return $result;
                break;
            case 2:
                //动态资讯
                return $this->getDynamicDetail($id);
                break;
            default:
                //积分资讯
                return $this->getPointDetail($id);
                break;
        }
    }

    /**
     * 获取动态资讯详情
     * @author 2017-06-05
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getDynamicDetail($id)
    {
        $data            = [];
        $language        = Yii::$app->language;
        $model           = Dynamic::findOne($id);
        $data['title']   = $language == 'hk' ? $model->news->hk_title : $model->news->title;
        $data['content'] = $language == 'hk' ? $model->news->hk_content : $model->news->content;
        $model->is_look  = 1;
        $model->save();
        return $data;
    }

    public function getPointDetail($id)
    {
        $language = Yii::$app->language;
        $result   = NewsPoint::find()->where(['id' => $id])->one();
        if (empty($result)) {
            throw new \Exception('数据异常');
        } else {

            $result->is_look = 1;
            $result->save();

            if ($result->type == 3) {
                $language        = Yii::$app->language;
                $result->title   = $language == 'hk' ? $result->news->hk_title : $result->news->title;
                $result->content = $language == 'hk' ? $result->news->hk_content : $result->news->content;
            } else {
                $result->title   = $language == 'hk' ? $result->hk_title : $result->title;
                $result->content = $language == 'hk' ? $result->hk_content : $result->content;
            }
            return $result;
        }

    }

}
