<?php

namespace console\controllers;

use backend\models\Brand;
use backend\models\Dynamic;
use backend\models\NewsLevelTemp;
use backend\models\NewsQueue;
use common\middleware\Queue;
use wechat\models\WechatVip;
use yii;
use yii\console\Controller;

class TriggerController extends Controller
{
    public function actionTest()
    {
        $newss = NewsLevelTemp::find()->where(['type' => 5, 'type_children' => 9, 'is_set' => 1])->all();
        if (!empty($newss)) {
            foreach ($newss as $k => $news) {
                $change         = NewsLevelTemp::findOne($news->id);
                $change->status = 1;
                $change->save();
            }
            Yii::error('更改状态成功');
        } else {
            Yii::error('不存在9月生日月资讯');
        }
        var_dump(Yii::$app->params['news_expire']);exit;
        Queue::sendNewsExpire(126, 'WX00000025', 1);
        //echo '111';
    }

    public function actionTest1()
    {
        $newss = NewsLevelTemp::find()->where(['type' => 5, 'type_children' => 9, 'is_set' => 1])->all();
        if (!empty($newss)) {
            foreach ($newss as $k => $v) {
                $data = Yii::$app->db->createCommand('SELECT * FROM news_member where type=1 and news_id=' . $v->id)
                    ->queryAll();
                if (!empty($data)) {
                    echo '已发布';
                } else {
                    echo '未发布';
                }
            }
        }
    }

    /**
     * 定时发布限时优惠
     * @return [type] [description]
     */
    public function actionSendTimeDiscount()
    {
        $current = time();
        $dyna    = Dynamic::find()->where(['is_auto' => 1])->andWhere(['>', 'send_time', time()])->all();
        if (!empty($dyna)) {
            Yii::error('存在定时发布优惠');
            foreach ($dyna as $k => $v) {
                $send        = strtotime(date('Y-m-d', $v->send_time));
                $current_day = strtotime(date('Y-m-d'));
                if ($send == $current_day) {
                    Yii::error('存在定时发当天的任务');
                    //发布限时优惠
                    $id       = $v->id;
                    $brand_id = $v->brand_id;
                    $model    = Dynamic::find()->where(['id' => $id])->one();
                    $brand    = Brand::getMemberRank($brand_id);
                    foreach ($brand as $k1 => $v1) {
                        if ($v1['rank'] == $model->member_rank) {
                            $vip_type = $v1['name'];
                        }
                    }
                    $queue           = new NewsQueue();
                    $queue->news_id  = $id;
                    $queue->vip_type = $vip_type;
                    $queue->brand_id = $brand_id;
                    $queue->save();
                    $q_id = $queue->getOldPrimaryKey();
                    Queue::sendDiscount($q_id);
                }
            }

        } else {
            Yii::error('不存在定时发布优惠');
        }
    }

    /**
     * 获取vip_type
     * @param  [type] $member_rank [description]
     * @param  [type] $brand_id    [description]
     * @return [type]              [description]
     */
    private function getVipType($brand_id, $member_rank)
    {
        $vip_type = '';
        $brand    = Brand::getMemberRank($brand_id);
        foreach ($brand as $k1 => $v1) {
            if ($v1['rank'] == $member_rank) {
                $vip_type = $v1['name'];
            }
        }
        return $vip_type;
    }

    private function getMemberRank($brand_id, $vip_type)
    {
        $member_rank = '';
        $brand       = Brand::getMemberRank($brand_id);
        foreach ($brand as $k1 => $v1) {
            if ($v1['name'] == $vip_type) {
                $member_rank = $v1['rank'];
            }
        }
        return $member_rank;
    }

    /**
     * 定时发布生日月资讯
     * @return [type] [description]
     */
    public function actionSendBirth()
    {
        $y   = date('Y');
        $m   = date('m');
        $d   = date('d');
        $day = cal_days_in_month(CAL_GREGORIAN, $m, $y);
        if ($d == $day) {
            Yii::error('当前月为' . $m . '月的最后一天');
            if ($m == 12) {
                $send_m = 1;
            } else {
                $send_m = $m + 1;
            }
            $newss = NewsLevelTemp::find()->where(['type' => 5, 'type_children' => $send_m, 'is_set' => 1])->all();
            if (!empty($newss)) {
                Yii::error('存在当前月份已设置的模版');
                foreach ($newss as $key => $news) {
                    $change         = NewsLevelTemp::findOne($news->id);
                    $change->status = 1;
                    $change->save();
                    $brand_id = $news->brand_id;
                    $brand    = Brand::getMemberRank($brand_id);
                    foreach ($brand as $k => $v) {
                        if ($v['rank'] == $news->member_rank) {
                            $vip_type = $v['name'];
                        }
                    }
                    $vips = WechatVip::find()->where(['brand' => $brand_id, 'vip_type' => $vip_type])->all();
                    if (!empty($vips)) {
                        Yii::error('存在当前品牌，当前类型的会员');
                        foreach ($vips as $k => $v) {
                            $birthday   = $v->birthday;
                            $birthday_m = date('m', strtotime($birthday));
                            if ($birthday_m != $send_m) {
                                unset($vips[$k]);
                            }
                        }
                    } else {
                        Yii::error('不存在当前品牌，当前类型的会员');
                    }

                    if (!empty($vips)) {
                        Yii::error('存在当前月份的生日会员');
                        $data = [];
                        $i    = 0;
                        foreach ($vips as $k => $v) {
                            $data[$i]['uid']         = $v->vip_no;
                            $data[$i]['create_time'] = time();
                            $data[$i]['end_time']    = $news->end_time;
                            $data[$i]['news_id']     = $news->id;
                            $data[$i]['type']        = 1;
                            $data[$i]['is_look']     = 0;
                            $i++;
                        }

                        $int = Yii::$app->db->createCommand()->batchInsert('news_member', ['uid', 'create_time', 'end_time', 'news_id', 'type', 'is_look'], $data)->execute();

                        // $change         = NewsLevelTemp::findOne($news->id);
                        // $change->status = 1;
                        // $change->save();
                        if ($int) {
                            Yii::error('生日月资讯已发布');
                        } else {
                            return false;
                        }
                    } else {
                        Yii::error('不存在当前月生日的会员');
                    }
                    //$news_id = $news->id;
                }
            } else {
                Yii::error('不存在此条模版');
            }

        } else {
            Yii::error('当天' . $d . '不是最后一天' . $day);
        }

    }

    /**
     * 临时发布生日月资讯
     * @return [type] [description]
     */
    public function actionSendT()
    {
        $m      = date('m');
        $send_m = $m;
        $newss  = NewsLevelTemp::find()->where(['type' => 5, 'type_children' => intval($send_m), 'is_set' => 1,'brand_id'=>2])->all();

        if (!empty($newss)) {
            foreach ($newss as $key => $news) {
                 $change         = NewsLevelTemp::findOne($news->id);
                    $change->status = 1;
                    $change->save();
                $brand_id = $news->brand_id;
                Yii::error('brand_id==='.$brand_id);
                if ($brand_id == 1){
                    Yii::error('brand_id错误');
                    exit;
                }
                $brand    = Brand::getMemberRank($brand_id);
                foreach ($brand as $k => $v) {
                    if ($v['rank'] == $news->member_rank) {
                        $vip_type = $v['name'];
                    }
                }
                if ($vip_type !='CN_SIT'){
                     $vips = WechatVip::find()->where(['brand' => $brand_id, 'vip_type' => $vip_type])->all();
                if (!empty($vips)) {
                    foreach ($vips as $k => $v) {
                        $birthday   = $v->birthday;
                        $birthday_m = date('m', strtotime($birthday));
                        if ($birthday_m != $send_m) {
                            unset($vips[$k]);
                        }
                        // Yii::error('vip_type'.$v->vip_type);
                        if ($v->vip_type=='CN_SIT'){
                            unset($vips[$k]);
                            Yii::error('sit会员去除');
                        }
                    }
                }

                if (!empty($vips)) {
                    $data = [];
                    $i    = 0;
                    foreach ($vips as $k => $v) {
                        $data[$i]['uid']         = $v->vip_no;
                        $data[$i]['create_time'] = time();
                        $data[$i]['end_time']    = $news->end_time;
                        $data[$i]['news_id']     = $news->id;
                        $data[$i]['type']        = 1;
                        $data[$i]['is_look']     = 0;
                        $i++;
                    }

                    $int = Yii::$app->db->createCommand()->batchInsert('news_member', ['uid', 'create_time', 'end_time', 'news_id', 'type', 'is_look'], $data)->execute();
                    if ($int) {
                        // return true;
                    } else {
                        // return false;
                    }
                }
                $news_id = $news->id;

                }
               
            }
        } else {
            Yii::error('不存在此条模版');
        }

    }

    /**
     * 会员到期执行脚本
     * @return [type] [description]
     */
    public function actionMemberExpire()
    {
        $date       = date('Y-m-d H:i:s');
        $date_one   = date('Y-m-d H:i:s', strtotime('+1 month'));
        $date_three = date('Y-m-d H:i:s', strtotime('+3 month'));
        //一个月到期的会员
        $vips_one = WechatVip::find()->where(['between', 'exp_date', $date, $date_one])->all();
        if (!empty($vips_one)) {
            Yii::error('存在一个月到期会员');
            foreach ($vips_one as $k => $v) {
                $brand_id    = $v->brand;
                $vip_type    = $v->vip_type;
                $vip_no      = $v->vip_no;
                $member_rank = $this->getMemberRank($brand_id, $vip_type);

                Yii::error('member_rank' . $member_rank);
                $news = NewsLevelTemp::find()->where(['type' => 2, 'member_rank' => intval($member_rank), 'type_children' => 1, 'is_set' => 1, 'brand_id' => $brand_id])->one();
                if (!empty($news)) {
                    Yii::error('存在一个月到期会员vip_type' . $vip_type . '的模版');
                    $news_id = $news->id;
                    $this->sendExpire($vip_no, $brand_id, $news_id);
                } else {
                    Yii::error('模版不存在');
                }
            }
        }

        //三个月到期会员
        $vips_three = WechatVip::find()->where(['between', 'exp_date', $date_one, $date_three])->all();
        if (!empty($vips_three)) {
            Yii::error('存在大于一个月小于三个月到期会员');
            foreach ($vips_three as $k => $v) {
                $brand_id    = $v->brand;
                $vip_no      = $v->vip_no;
                $vip_type    = $v->vip_type;
                $member_rank = $this->getMemberRank($brand_id, $vip_type);

                Yii::error('member_rank' . $member_rank);
                $news = NewsLevelTemp::find()->where(['type' => 2, 'member_rank' => intval($member_rank), 'type_children' => 2, 'is_set' => 1, 'brand_id' => $brand_id])->one();
                if (!empty($news)) {
                    Yii::error('存在三个月到期会员vip_type' . $vip_type . '的模版');
                    $news_id = $news->id;
                    $this->sendExpire($vip_no, $brand_id, $news_id);
                } else {
                    Yii::error('模版不存在');
                }
            }
        }

    }

    /**
     * [sendExpire description]
     * @param  [type] $vip_no [description]
     * @param  [type] $type   1一个月到期，2,三个月到期
     * @return [type]         [description]
     */
    private function sendExpire($vip_no, $brand_id, $news_id)
    {
        Yii::error('发布到期资讯vip_no:' . $vip_no . '=====brand_id:' . $brand_id . '===news_id:' . $news_id);
        $ret = Queue::sendNewsExpire($news_id, $vip_no, $brand_id);

    }

    /**
     * 推送会员三个月到期积分
     * @return [type] [description]
     */
    public function actionPointExpire()
    {
        $users = \wechat\models\WechatVip::find()->all();
        foreach ($users as $k => $v) {
            $uid      = $v->wechat_user_id;
            $brand_id = $v->brand;
            $vip_no   = $v->vip_no;
            $result   = \wechat\models\service\WechatVipService::getBonus($vip_no);
            \wechat\models\logic\NewsPointLogic::expirePoint($uid, $brand_id, $result->exp_bonus);
        }
    }

    public function actionCheckDb()
    {
        $ret = WechatVip::find()->one();
        var_dump($ret);exit;
    }

}
