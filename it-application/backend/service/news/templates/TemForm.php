<?php

namespace backend\service\news\templates;

use backend\models\NewsLevelTemp;
use backend\service\BaseModel;

/**
 * 会员等级模版逻辑
 */
class TemForm extends BaseModel
{
    public $type;
    public $type_children;
    public $title;
    public $hk_title;
    public $content;
    public $hk_content;
    public $member_rank;
    public $end_time = '';

    public function rules()
    {
        return [
            ['type', 'required'],
            ['title', 'required'],
            ['end_time', 'string'],
            ['hk_title', 'required'],
            ['title', 'string', 'min' => 3],
            ['title', 'string', 'max' => 30],
            ['type_children', 'number'],
            ['hk_content', 'required'],
            ['content', 'string'],
            ['member_rank', 'required'],
            ['member_rank', 'number'],
        ];
    }

    public function save()
    {
        $model                = new NewsLevelTemp();
        $model->title         = $this->title;
        $model->hk_title      = $this->hk_title;
        $model->type          = $this->type;
        $model->type_children = $this->type_children;
        $model->content       = $this->content;
        $model->end_time      = $this->end_time==0?0:strtotime($this->end_time);
        $model->hk_content    = $this->hk_content;
        $model->member_rank   = $this->member_rank;
        $model->brand_id      = \Yii::$app->session->get('brand_id');
        $model->save();
    }

    public function updateData($id)
    {
        $model                = NewsLevelTemp::findOne($id);
        $model->title         = $this->title;
        $model->hk_title      = $this->hk_title;
        $model->type          = $this->type;
        $model->type_children = $this->type_children;
        $model->end_time      = $this->end_time==0?0:strtotime($this->end_time);
        $model->content       = $this->content;
        $model->hk_content    = $this->hk_content;
        $model->member_rank   = $this->member_rank;
        $model->brand_id      = \Yii::$app->session->get('brand_id');
        $model->save();
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
