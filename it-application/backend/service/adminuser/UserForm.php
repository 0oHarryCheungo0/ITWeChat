<?php
namespace backend\service\adminuser;

use Yii;
use backend\service\BaseModel;
use backend\models\AdminUser;

class UserForm extends BaseModel
{
    public $id;
    public $username;
    public $password;
    public $password_repeat;
    public $role;
    public $auth_id;
    public $brand_id;

    /**
     * 表单验证
     */
    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat', 'auth_id'], 'required', 'on' => ['add']],
            ['username','string','min'=>6,'max'=>20,'on'=>['add','update']],
            [['id', 'username', 'auth_id'], 'required', 'on' => ['update']],
            ['username', 'unique', 'targetClass' => 'backend\models\AdminUser', 'on' => ['add']],
            ['password', 'compare', 'on' => ['add']],
            ['brand_id', 'required', 'on' => ['add', 'update']],
        ];
    }

    public function scenarios()
    {
        return [
            'add'    => ['username', 'password', 'auth_id', 'password_repeat', 'brand_id'],
            'update' => ['id', 'username', 'brand_id', 'auth_id'],
        ];
    }

    /**
     * 新增用户
     *
     * @author fushl 2017-05-16
     * @return [type] [description]
     */
    public function save()
    {

        $model = new AdminUser();
        $model->username = $this->username;
        $model->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $model->brand_id = $this->brand_id;
        $model->auth_id  = $this->auth_id;
        $model->create_time = time();
        $model->update_time = time();
        $model->save();

    }

    /**
     * [update description] 编辑用户数据
     *
     * @author fushl 2017-05-16
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update($id)
    {
        $model = AdminUser::findOne($id);
        //$model->username = $this->username;
        $model->brand_id = $this->brand_id;
        $model->auth_id  = $this->auth_id;
        $model->update_time = time();
        $model->save();
    }

    /**
     *
     *
     * @author fushl 2017-05-16
     * @return [type] [description]
     */
    public function attributeLabels()
    {
        return [
            'username'        => '用户',
            'password'        => '密码',
            'password_repeat' => '重复密码',
            'brand'           => '品牌',
        ];
    }
}
