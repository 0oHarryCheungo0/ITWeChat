<?php

namespace backend\service\news\templates;

use backend\models\NewsLevelTemp;
use backend\service\BaseModel;
use Yii;

/**
 * 会员等级模版逻辑
 */
class MemberTemplatesForm extends BaseModel
{
    public $type;
    public $title;
    public $content;
    public $member_rank;
    public $type_children;
    public $hk_title;
    public $hk_content;

    public function rules()
    {
        return [
            ['type', 'required'],
            ['title', 'required'],
            ['hk_title', 'required'],
            ['title', 'string', 'min' => 3],
            ['title', 'string', 'max' => 30],
            ['type_children', 'number'],
            ['hk_content', 'required'],
            ['content', 'string', 'max' => 500],
            ['member_rank', 'required'],
            ['member_rank', 'number'],
        ];
    }

    /**
     * 判断是否存在会员等级模版
     * @author fushl 2017-05-27
     * @param  [type]  $rank [description]
     * @return boolean       [description]
     */
    public static function isOne($rank, $type)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $result   = NewsLevelTemp::find()->where(['member_rank' => $rank, 'brand_id' => $brand_id, 'type' => $type])->one();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 判断是否存在会员到期模版
     * @author fushl 2017-05-31
     * @param  [type]  $type_childen [description]
     * @param  [type]  $rank         [description]
     * @return boolean               [description]
     */
    public static function isExpire($rank, $type_childen, $type)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $result   = NewsLevelTemp::find()->where(['member_rank' => $rank, 'brand_id' => $brand_id, 'type_children' => $type_childen, 'type' => $type])->one();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 模版保存
     * @author fushl 2017-05-27
     * @return [type] [description]
     */
    public function save()
    {
        $model              = new NewsLevelTemp();
        $model->title       = $this->title;
        $model->hk_title    = $this->hk_title;
        $model->hk_content  = $this->hk_content;
        $model->content     = $this->content;
        $model->brand_id    = Yii::$app->session->get('brand_id');
        $model->member_rank = $this->member_rank;
        $model->type        = $this->type;
        $model->save();
        $id = $model->getOldPrimaryKey();
        return $id;
    }

    /**
     * 模版更新
     * @author fushl 2017-05-27
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update($id)
    {
        $brand_id = Yii::$app->session->get('brand_id');
        $model    = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();
        if (empty($model)) {
            Yii::info('品牌id'.$brand_id.'模版id'.$id);
            throw new \Exception('权限不足或数据异常');
        }
        $model->title      = $this->title;
        $model->hk_title   = $this->hk_title;
        $model->hk_content = $this->hk_content;
        $model->content    = $this->content;
        $model->type       = $this->type;
        $model->save();
        return true;
    }

    /**
     * 新增会员到期模版
     * @author fushl 2017-05-31
     * @return [type] [description]
     */
    public function saveExpire()
    {
        $model                = new NewsLevelTemp();
        $model->type          = $this->type;
        $model->title         = $this->title;
        $model->hk_title      = $this->hk_title;
        $model->hk_content    = $this->hk_content;
        $model->content       = $this->content;
        $model->brand_id      = Yii::$app->session->get('brand_id');
        $model->member_rank   = $this->member_rank;
        $model->type_children = $this->type_children;
        $model->save();
        $id = $model->getOldPrimaryKey();
        return $id;    
    }

    public function updateExpire($id)
    {

        $brand_id = Yii::$app->session->get('brand_id');
        $model    = NewsLevelTemp::find()->where(['brand_id' => $brand_id, 'id' => $id])->one();
        if (empty($model)) {
            throw new \Exception('权限不足或数据异常');
        }
        $model->type          = $this->type;
        $model->title         = $this->title;
        $model->hk_title      = $this->hk_title;
        $model->hk_content    = $this->hk_content;
        $model->content       = $this->content;
        $model->type_children = $this->type_children;
        $model->save();
        return true;

    }

    /**
     * 描述
     * @author fushl 2017-05-27
     * @return [type] [description]
     */
    public function attributeLabels()
    {
        return [
            'type'         => '模版类型',
            'title'        => '模版标题',
            'content'      => '模版内容',
            'memeber_rank' => '会员等级',
        ];
    }

}
