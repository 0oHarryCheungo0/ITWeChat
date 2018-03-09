<?php

namespace backend\service\news;

use backend\models\Dynamic;
use backend\service\BaseModel;
use Yii;

class NewsForm extends BaseModel
{
    public $type;
    public $title;
    public $hk_title;
    public $hk_content;
    public $content;
    public $end;
    public $url;

    public function rules()
    {
        return [
            ['type', 'number'],
            ['type', 'required'],
            ['title', 'required'],
            ['title', 'string', 'max' => 20],
            ['content', 'required'],
            ['content', 'string', 'max' => 999],
            ['hk_title','required'],
            ['hk_content','required'],
            ['end', 'required'],
        ];
    }

    public function save()
    {
        $model              = new Dynamic();
        $model->type        = $this->type;
        $model->title       = $this->title;
        $model->hk_title    = $this->hk_title;
        $model->content     = $this->content;
        $model->hk_content  = $this->hk_content;
        $model->end         = strtotime($this->end);
        $model->create_time = time();
        $model->brand_id    = Yii::$app->session->get('brand_id');
        $ret = $model->save();
          if ($ret){
            return true;
        }else{
            return false;
        }
    }

    public function update($id){
        $model              = Dynamic::findOne($id);
        $model->type        = $this->type;
        $model->title       = $this->title;
        $model->hk_title    = $this->hk_title;
        $model->content     = $this->content;
        $model->hk_content  = $this->hk_content;
        $model->end         = strtotime($this->end);
       // $model->create_time = time();
        $model->brand_id    = Yii::$app->session->get('brand_id');
        $ret=  $model->save();
        if ($ret){
            return true;
        }else{
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'title'   => '资讯标题',
            'content' => '资讯内容',
            'end'     => '资讯持续天数',
        ];
    }
}
