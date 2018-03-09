<?php

namespace backend\service\brand;

use backend\models\Brand;
use backend\service\BaseModel;

class BrandForm extends BaseModel
{
    public $p_id;
    public $brand_name;
    public $appid;
    public $appsecret;
    public $token;
    public $identify;
    public $id;

    public function rules()
    {
        return [
            ['id', 'integer'],
            ['p_id', 'required', 'message' => '请选择分组'],
            ['brand_name', 'required', 'message' => '品牌名必填'],
            ['brand_name', 'string', 'max' => 20],
            ['identify', 'required'],
            ['identify', 'string', 'max' => 30],
            ['p_id', 'number'],
            ['appid', 'required'],
            ['appid', 'string', 'max' => 20, 'min' => 10],
            ['appsecret', 'required'],
            ['appsecret', 'string', 'length' => 32],
            ['token', 'required'],
        ];
    }

    public function save($array)
    {
        $model             = new Brand();
        $model->brand_name = $this->brand_name;
        $model->identify   = $this->identify;
        $model->p_id       = $this->p_id;
        $model->appid      = $this->appid;
        $model->appsecret  = $this->appsecret;
        $model->token      = $this->token;
        $model->rank       = json_encode($array);
        $model->save();
    }

    public function update($id, $array)
    {
        $model             = Brand::findOne($id);
        $model->brand_name = $this->brand_name;
        $model->identify   = $this->identify;
        $model->p_id       = $this->p_id;
        $model->appid      = $this->appid;
        $model->appsecret  = $this->appsecret;
        $model->token      = $this->token;
        $model->rank       = json_encode($array);
        $model->save();
    }

    /**
     * [parse description]
     * @author fushl 2017-05-25
     * @return [type] [description]
     */
    public function parse($array)
    {

        if (!empty($this->id)) {

            if ($this->validate()) {
                $this->update($this->id,$array);
                return true;
            } else {
                return false;
            }
        } else {
            if ($this->validate()) {
                $this->save($array);
                return true;
            } else {
                return false;
            }

        }
    }

    public function attributeLabels()
    {
        return [
            'brand_name' => '品牌名',
            'p_id'       => '父类品牌',
            'identify'   => '标识符',
            'appid'      => '微信Appid',
            'appsecret'  => '微信AppSecret',
            'token'      => 'TOKEN',
        ];
    }
}
