<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Dynamic;
use backend\models\NewsLevelTemp;
use backend\service\news\NewsForm;
use backend\service\news\NewsLogic;
use backend\service\news\templates\TemForm;
use backend\service\QueueService;
use common\middleware\Queue;
use common\models\NewsMember;
use common\models\NewsPoint;
use Yii;

class NewsController extends AdminBaseController
{
    public $error;

    public $month = [
        1 => '一月内到期',
        2 => '三月内到期',
    ];
    //会员权益模版
    const MEMBERRANK = 1;
    //会员到期模版
    const MEMBEREXPIRE = 2;
    //会员资讯模
    const POINT_TYPE = 1;

    /**
     * 积分资讯列表
     * @author fushl 2017-05-26
     * @return [type] [description]
     */
    public function actionList()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
            $search = $request->get('search');
            $type   = $request->get('type');
            $result = NewsLogic::getList($limit, $offset, $type, $search);
            return $this->response($result);
        }
        return $this->render('list');
    }

    /**
     * 新增积分资讯
     * @author fushl 2017-05-26
     * @return [type] [description]
     */
    public function actionAdd()
    {
        $request = Yii::$app->request;
        $type    = self::POINT_TYPE;
        $data    = new NewsForm();
        return $this->render('add', ['type' => $type, 'data' => $data, 'id' => '']);
    }

    public function actionEditPoint()
    {
        $id       = Yii::$app->request->get('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $type     = self::POINT_TYPE;
        $form     = new NewsForm();
        $data     = Dynamic::find()->where(['brand_id' => $brand_id, 'id' => $id])->one()->toArray();
        $form->load($data, '');

        return $this->render('add', ['id' => $id, 'type' => $type, 'data' => $form]);
    }

    public function actionUpdatePoint()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $data = [];
            $ajax = Yii::$app->request->post('data');
            $data = json_decode($ajax, true);
            $id   = $data['id'];
            unset($data['id']);
            $form = new NewsForm();
            if ($form->load($data, '') && $form->validate()) {
                if (empty($id)) {
                    if ($form->save()) {
                        return $this->response('', 200);
                    } else {
                        return $this->response('', 300, '失败');
                    }
                } else {
                    if ($form->update($id)) {
                        return $this->response('', 200);
                    } else {
                        return $this->response('', 300, '失败');
                    }
                }

            } else {
                foreach ($form->getErrors() as $k => $v) {
                    return $this->response('', 300, $v['0']);
                }
            }
        }
    }

    /**
     * 删除积分资讯
     * @return [type] [description]
     */
    public function actionDelPoint()
    {
        $id       = Yii::$app->request->post('id');
        $brand_id = Yii::$app->session->get('brand_id');
        $result   = Dynamic::find()->where(['id' => $id, 'brand_id' => $brand_id])->one();
        if (!empty($result)) {
            //删除会员资讯表
            $ret = NewsPoint::deleteAll(['news_id' => $id]);

            if ($result->delete()) {
                return $this->response('', 200);
            }

        }
        return $this->response('', 300);
    }

    /**
     * 选取发布积分资讯
     * @author fushl 2017-05-26
     * @return [type] [description]
     */
    public function actionRelease()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {

            $str    = $request->get('str');
            $model  = new NewsLogic();
            $result = $model->saveQueue($str);
            if ($result) {
                $model          = Dynamic::findOne($str);
                $model->is_send = 1;
                $model->save();
                //  Queue::send($url,['news_id'=>$result]);
                return $this->response('', 200);
            } else {
                return $this->response('', 302, $model->error);
            }
        }

    }

    /**
     * 会员等级资讯列表
     * @return [type] [description]
     */
    public function actionMemberRankList()
    {
        $request  = Yii::$app->request;
        $type     = self::MEMBERRANK;
        $brand_id = Yii::$app->session->get('brand_id');
        $month    = json_encode(Yii::$app->params['month']);
        $brand    = Brand::getMemberRank();
        $new      = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        $brand_data = json_encode($new);
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
            $type   = $request->get('type');
            $count  = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => $type])->count();
            $data   = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => $type])->limit($limit)->offset($offset)->orderBy('type_children desc')->all();
            return $this->response(['total' => $count, 'rows' => $data]);
        }
        return $this->render('member-rank-list', ['brand_data' => $brand_data, 'type' => $type]);

    }

    /**
     * 新增会员等级资讯
     * @return [type] [description]
     */
    public function actionAddRank()
    {
        $form  = new TemForm();
        $type  = self::MEMBERRANK;
        $month = Yii::$app->params['month'];
        $brand = Brand::getMemberRank();
        return $this->render('rank', ['month' => $month, 'brand' => $brand, 'type' => $type, 'data' => $form, 'id' => '']);

    }

    /**
     * 删除会员等级
     * @return [type] [description]
     */
    public function actionDelRank()
    {
        if (Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->get('id');
            $brand_id = Yii::$app->session->get('brand_id');
            \common\models\NewsMember::deleteAll(['news_id' => $id]);
            NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'id' => $id])->one()->delete();
            return $this->response('', 200);
        }
    }

    public function actionDelDynamic()
    {

    }

    /**
     * 编辑会员等级资讯
     */
    public function actionEditRank()
    {
        $type  = self::MEMBERRANK;
        $brand = Brand::getMemberRank();
        $id    = Yii::$app->request->get('id');
        $data  = NewsLevelTemp::find()->where(['id' => $id])->one()->toArray();
        $form  = new TemForm();
        $form->load($data, '');
        return $this->render('rank', ['brand' => $brand, 'type' => $type, 'data' => $form, 'id' => $id]);
    }

    /**
     * 数据操作
     * @return [type] [description]
     */
    public function actionUpdateData()
    {
        if (Yii::$app->request->isAjax) {
            $ajax = Yii::$app->request->post('data');
            $data = json_decode($ajax, true);
            $id   = $data['id'];
            unset($data['id']);
            $form = new TemForm();
            if ($form->load($data, '') && $form->validate()) {
                if (!empty($id)) {
                    Yii::info('编辑数据');
                    $form->updateData($id);
                    return $this->response('', 200);
                } else {
                    Yii::info('新增数据');
                    $form->save();
                    return $this->response('', 200);
                }
            } else {
                foreach ($form->getFirstErrors() as $k => $v) {
                    $error = $v;
                }
                return $this->response('', 300, $error);

            }

        }
    }

    /**
     * 会员到期列表
     * @return [type] [description]
     */
    public function actionExpireList()
    {
        $type     = self::MEMBEREXPIRE;
        $brand_id = Yii::$app->session->get('brand_id');
        $month    = json_encode($this->month);
        $brand    = Brand::getMemberRank();
        $new      = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        $brand_data = json_encode($new);
        return $this->render('member-expire-list', ['brand_data' => $brand_data, 'month' => $month, 'type' => $type]);
    }

    /**
     * 新增会员到期模版
     * @return [type] [description]
     */
    public function actionAddExpire()
    {
        $form  = new TemForm();
        $type  = self::MEMBEREXPIRE;
        $brand = Brand::getMemberRank();
        return $this->render('member-expire', ['month' => $this->month, 'brand' => $brand, 'type' => $type, 'data' => $form, 'id' => '']);
    }

    /**
     * 编辑会员到期模版
     * @return [type] [description]
     */
    public function actionEditExpire()
    {
        $type  = self::MEMBEREXPIRE;
        $brand = Brand::getMemberRank();
        $id    = Yii::$app->request->get('id');
        $data  = NewsLevelTemp::find()->where(['id' => $id])->one()->toArray();
        $form  = new TemForm();
        $form->load($data, '');
        return $this->render('member-expire', ['brand' => $brand, 'month' => $this->month, 'type' => $type, 'data' => $form, 'id' => $id]);
    }

    /**
     * 推送会员等级资讯
     * @return [type] [description]
     */
    public function actionPubRank()
    {
        $news_id  = Yii::$app->request->post('id');
        $brand_id = Yii::$app->session->get('brand_id');
        if (NewsLevelTemp::isSend($news_id)){
            return $this->response('', 300,'已发布，请勿重复发布');
        }
        $vip_no   = $this->getVipNo($news_id);
        if (!empty($vip_no)) {
            foreach ($vip_no as $k => $v) {
                $no = $v['vip_no'];
                Queue::sendNewsRank($news_id, $no, $brand_id);
            }
        }
        NewsLevelTemp::send($news_id);
        return $this->response('', 200);

    }

    /**
     * 推送会员到期资讯
     * @return [type] [description]
     */
    public function actionPubExpire()
    {
        $news_id  = Yii::$app->request->post('id');
        $result   = NewsLevelTemp::findOne($news_id);
        $brand_id = Yii::$app->session->get('brand_id');
        if (!empty($result)) {
            $send = NewsLevelTemp::find()->where(['type' => $result->type, 'type_children' => $result->type_children, 'status' => 1, 'brand_id' => $brand_id, 'member_rank' => $result->member_rank])->one();
            if (!empty($send)) {
                return $this->response('', 300, ',已发布过同类型资讯');
            }
        }
        $brand_id = Yii::$app->session->get('brand_id');
        $vip_no   = $this->getExpireVipNo($news_id);
        if (!empty($vip_no)) {
            foreach ($vip_no as $k => $v) {
                $no = $v['vip_no'];

                Queue::sendNewsExpire($news_id, $no, $brand_id);

            }
        } else {
            NewsLevelTemp::send($news_id);
            return $this->response('', 200, '不存在过期会员');
        }
        NewsLevelTemp::send($news_id);
        return $this->response('', 200);
    }

    /**
     * 推送签到资讯
     * @return [type] [description]
     */
    public function actionPupoint()
    {
        $brand_id = Yii::$app->session->get('brand_id');
        Queue::sendNewsPoint($brand_id);
        return $this->response('', 200);
    }

    /**
     * 推送完善资料资讯
     * @return [type] [description]
     */
    public function actionPuprefect()
    {

        $brand_id = Yii::$app->session->get('brand_id');
        Queue::sendNewsPrefect($brand_id);
        return $this->response('', 200);
    }

    /**
     * 设置默认会员等级模版
     * @return [type] [description]
     */
    public function actionDefaultRank()
    {
        $brand_id    = Yii::$app->session->get('brand_id');
        $id          = Yii::$app->request->post('id');
        $result      = NewsLevelTemp::find()->where(['id' => $id, 'brand_id' => $brand_id])->one();
        $type        = $result->type;
        $member_rank = $result->member_rank;
    }

    /**
     * 设置默认会员到期模版
     * @return [type] [description]
     */
    public function actionDefaultExpire()
    {

    }

    public function getExpireVipNo($news_id)
    {
        $result = QueueService::getExpireVipNo($news_id);
        if ($result != false) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 会员等级vip_no
     * @param  [type] $news_id [description]
     * @return [type]          [description]
     */
    private function getVipNo($news_id)
    {
        $vip_no = QueueService::getVipNo($news_id);
        if ($vip_no != false) {
            return $vip_no;
        } else {
            return [];
        }
    }

    public function actionTest()
    {
        QueueService::getExpireVipNo($news_id);
    }

    /**
     * 生日月资讯
     * @return [type] [description]
     */
    public function actionBirthNews()
    {
        $request  = Yii::$app->request;
        $brand_id = Yii::$app->session->get('brand_id');
        $month    = json_encode(Yii::$app->params['month']);
        $brand    = Brand::getMemberRank();
        $new      = [];
        foreach ($brand as $k => $v) {
            $new[$v['rank']] = $v['name'];
        }
        $brand_data = json_encode($new);
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
            $type   = $request->get('type');
            $count  = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => $type])->count();
            $data   = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'type' => $type])->limit($limit)->offset($offset)->orderBy('type_children desc')->all();
            return $this->response(['total' => $count, 'rows' => $data]);
        }
        return $this->render('birth-list', ['brand_data' => $brand_data, 'month' => $month]);

    }

    public function actionAddBirthNews()
    {
        $form = new TemForm();
        //会员生日月资讯模版
        $type = 5;
        //月份配置数组
        $month = Yii::$app->params['month'];
        $brand = Brand::getMemberRank();
        return $this->render('birth-add', ['month' => $month, 'brand' => $brand, 'type' => $type, 'data' => $form, 'id' => '']);
    }

    /**
     * 编辑生日月模版
     */
    public function actionEditBirth()
    {
        //会员生日月模版
        $type = 5;
        //月份配置数组
        $month = Yii::$app->params['month'];
        $brand = Brand::getMemberRank();

        $id   = Yii::$app->request->get('id');
        $data = NewsLevelTemp::find()->where(['id' => $id])->one()->toArray();

        $form = new TemForm();
        $form->load($data, '');

        return $this->render('birth-add', ['month' => $month, 'brand' => $brand, 'type' => $type, 'data' => $form, 'id' => $id]);
    }

    /**
     * 数据操作
     * @return [type] [description]
     */
    public function actionUpdateBirthData()
    {
        if (Yii::$app->request->isAjax) {
            $ajax          = Yii::$app->request->post('data');
            $data          = json_decode($ajax, true);
            $id            = $data['id'];
            $type_children = $data['type_children'];

            if ($type_children == 1) {
                $y = date('Y', strtotime("+1 year"));
            } else {
                $y = date('Y');
            }

            $d                = cal_days_in_month(CAL_GREGORIAN, $type_children, $y);
            $data['end_time'] = $y . '-' . $type_children . '-' . $d . ' 23:59:59';
            unset($data['id']);
            $form = new TemForm();
            if ($form->load($data, '') && $form->validate()) {
                if (!empty($id)) {
                    Yii::info('编辑数据');
                    $form->updateData($id);
                    return $this->response('', 200);
                } else {
                    Yii::info('新增数据');
                    $form->save();
                    return $this->response('', 200);
                }
            } else {
                foreach ($form->getFirstErrors() as $k => $v) {
                    $error = $v;
                }
                return $this->response('', 300, $error);

            }

        }
    }

    /**
     * 删除生日月优惠
     * @return [type] [description]
     */
    public function actionDelBirth()
    {
        if (Yii::$app->request->isAjax) {
            $id       = Yii::$app->request->get('id');
            $brand_id = Yii::$app->session->get('brand_id');
            //Discount::deleteAll(['brand_id' => $brand_id, 'news_id' => $id]);
            NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'id' => $id])->one()->delete();
            NewsMember::deleteAll(['type' => 1, 'news_id' => $id]);
            return $this->response('', 200);
        }
    }

}
