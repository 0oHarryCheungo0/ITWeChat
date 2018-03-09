<?php

namespace wechat\models\logic;

use backend\models\Discount;
use Yii;

class DiscountLogic extends BaseNews
{
    const NOLOOK = 0;
    const MTYPE  = 3;
    const SMTYPE = 1;
    const BTYPE  = 4;
    const CBTYPE = 2;
    const OFF    = 1;

    public function memberDiscount($uid, $brand_id, $rank)
    {
        $this->sendNews($brand_id, $rank, $uid);
    }

    /**
     * 发布会员等级变动专属优惠
     * @author fushl 2017-06-01
     * @return [type] [description]
     */
    public function sendNews($brand_id, $rank, $uid)
    {

        $template           = $this->getTemplate(self::MTYPE, $brand_id, $rank);
        $model              = new Discount();
        $model->title       = $template['title'];
        $model->content     = $template['content'];
        $model->is_look     = self::NOLOOK;
        $model->create_time = time();
        $model->type        = self::SMTYPE;
        $model->uid         = $uid;
        $model->end_time    = 0;
        $model->brand_id    = $brand_id;
        $model->save();
    }

    /**
     * 检查是否是生日月
     * @author fushl 2017-06-02
     * @return [type] [description]
     */
    public function checkBirth($uid, $brand_id, $rank_value, $birthday, $current_month)
    {
        Yii::info('生日月传入的会员等级为' . $rank_value);
        $birth_month = date('m', strtotime($birthday));
        // $current_month = date('m', time());
        //当前月为生日月，进行消息推送
        if (intval($birth_month) == intval($current_month)) {
            $member_rank = $this->getMemberRank($rank_value, $brand_id);
            Yii::info('会员等级为' . $member_rank);
            //检查当月是否发过
            $model = new Discount();
            $ret   = $model::find()->where(['uid' => $uid, 'type' => self::CBTYPE])->orderBy('create_time desc')->one();
            if (empty($ret)) {
                //执行插入
                Yii::info('插入生日月优惠');
                $this->sendBrith($uid, $brand_id, $member_rank, $birth_month);
            } else {
                if (date('Y-m', $ret['create_time']) != date('Y-m', time())) {
                    Yii::info('生日月优惠过期，插入');
                    $this->sendBrith($uid, $brand_id, $member_rank, $birth_month);
                } else {
                    Yii::info('不是生日月');
                }

            }

        }
    }

    public function sendBrith($uid, $brand_id, $member_rank, $birth_month)
    {
        $template           = $this->getTemplate(self::BTYPE, $brand_id, $member_rank, $birth_month);
        $model              = new Discount();
        $model->title       = $template['title'];
        $model->content     = $template['content'];
        $model->create_time = time();
        $model->brand_id    = $brand_id;
        $model->news_id     = $template['id'];
        $model->uid         = $uid;
        $model->end_time    = 0;
        $model->type        = self::CBTYPE;
        $model->save();
    }

    /**
     * 统计所有未读
     * @author fushl 2017-06-05
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function countTotal($uid)
    {
        return Discount::find()->where(['uid' => $uid, 'is_look' => 0])->count();
    }

    /**
     * 限时优惠未读条数
     * @author fushl 2017-06-05
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function countExclusive($uid)
    {
        return Discount::find()->where(['uid' => $uid, 'is_look' => 0, 'type' => 3])->count();
    }

    /**
     * 常规权益未读条数
     *
     * @author fushl 2017-06-05
     * @param  [type] $uid [description]
     * @return [type]      [description]
     */
    public static function countNormal($uid)
    {
        return Discount::find()->where(['uid' => $uid, 'is_look' => 0])->andWhere(['in', 'type', [1, 2]])->count();
    }

    /**
     * 获取优惠列表
     * @author fushl 2017-06-05
     * @return [type] [description]
     */
    public function getList($uid, $type = '3', $page)
    {
        $language = Yii::$app->language;
        $current  = time();
        $result   = Discount::find()->where(['uid' => $uid])->andWhere(['in', 'type', [1, 2, 3]])->andWhere(['or', 'end_time=0', ['>', 'end_time', $current]])->with('dynamic')->orderBy('create_time desc')->with('template')->asArray()->all();
        $test =  Discount::find()->where(['uid' => $uid])->andWhere(['in', 'type', [1, 2, 3]])->andWhere(['or', 'end_time=0', ['>', 'end_time', $current]])->with('dynamic')->orderBy('create_time desc')->with('template')->createCommand()->getRawSql();
        $data     = [];
        $data1    = [];

        if (!empty($result)) {
            foreach ($result as $k => $v) {
                if ($v['type'] != 3) {
                    $data[$k]['id']          = $v['id'];
                    $data[$k]['title']       = $language == 'hk' ? $v['template']['hk_title'] : $v['template']['title'];
                    $data[$k]['content']     = $language == 'hk' ? $v['template']['hk_content'] : $v['template']['content'];
                    $data[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                    $data[$k]['type']        = $v['type'];
                    $data[$k]['is_look']     = $v['is_look'];
                } else {
                    $data1[$k]['id']          = $v['id'];
                    $data1[$k]['title']       = $language == 'hk' ? $v['dynamic']['hk_title'] : $v['dynamic']['title'];
                    $data1[$k]['content']     = $language == 'hk' ? $v['dynamic']['hk_content'] : $v['dynamic']['content'];
                    $data1[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
                    $data1[$k]['type']        = $v['type'];
                    $data1[$k]['is_look']     = $v['is_look'];
                }

            }

        }

        return ['data' => $data, 'data1' => $data1];

    }

    /**
     * [getPages description]
     * @author
     * @param  [type] $uid  [description]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    public function getPages($uid, $type)
    {
        if ($type == 1) {
            return Discount::find()->where(['uid' => $uid,
            ])->andWhere(['in', 'type', [1, 2]])->count();
        } else {
            return Discount::find()->where(['uid' => $uid, 'type' => 3])->count();
        }
    }

    /**
     * [getOne description]
     * @author
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function getOne($id)
    {

        $data     = [];
        $language = Yii::$app->language;
        $result   = Discount::findOne($id);

        if ($result->type != 3) {
            $data['title']   = $language == 'hk' ? $result->template->hk_title : $result->template->title;
            $data['content'] = $language == 'hk' ? $result->template->hk_content : $result->template->content;
        } else {
            $data['title']   = $language == 'hk' ? $result->dynamic->hk_title : $result->dynamic->title;
            $data['content'] = $language == 'hk' ? $result->dynamic->hk_content : $result->dynamic->content;
        }
        if (!empty($data)) {
            $result->is_look = 1;
            $result->save();
            return $data;
        } else {
            return false;
        }
    }

    /**
     * 发布等级优惠
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public static function sendRank($brand_id, $vip_no, $news_id)
    {
        $end_time           = self::getEnd($news_id);
        $type               = 1;
        $model              = new Discount();
        $model->brand_id    = $brand_id;
        $model->type        = 1;
        $model->end_time    = $end_time;
        $model->news_id     = $news_id;
        $model->uid = $vip_no;
        $model->create_time = time();
        $ret                = $model->save();
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

    public static function sendBirth($brand_id, $vip_no, $news_id)
    {
        $end_time           = self::getEnd($news_id);
        $model              = new Discount();
        $model->brand_id    = $brand_id;
        $model->type        = self::CBTYPE;
        $model->end_time    = $end_time;
        $model->news_id     = $news_id;
        $model->uid = $vip_no;
        $model->create_time = time();
        $ret                = $model->save();
        if ($ret) {
            return true;
        } else {
            return false;
        }

    }
}
