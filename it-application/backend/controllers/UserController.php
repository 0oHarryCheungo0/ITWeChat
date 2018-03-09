<?php
namespace backend\controllers;

use backend\models\AdminUser;
use backend\models\Auth;
use backend\models\Brand;
use backend\service\adminuser\UserForm;
use backend\service\adminuser\UserList;
use Yii;

class UserController extends AdminBaseController
{
    public $enableCsrfValidation = false;

    /**
     * 新增管理员
     */
    public function actionAdduser()
    {

        if (\Yii::$app->user->id == 1) {
            $brands  = Brand::getAll();
            $auth    = Auth::getAll();
            $request = Yii::$app->request;
            if ($request->isAjax) {
                $user = new UserForm();
                $user->setScenario('add');
                if ($aa = $user->loadAjax($request->post('data')) && $user->validate()) {
                    $user->save();
                    return $this->response('', 200);
                } else {
                    return $this->response('', 302, $user->getOneError());
                }
            }
            return $this->render('adduser', ['brands' => $brands, 'auth' => $auth]);
        } else {
            return $this->response('权限不足');
        }

    }

    /**
     * 管理员列表
     */
    public function actionList()
    {
        $auth = Auth::find()->where(['>','id',1])->asArray()->all();
        $data = [];
        if (!empty($auth)){
            foreach ($auth as $k=>$v){
                $data[$v['id']] = $v['auth'];
            }
        }

        $au  =json_encode($data);
         

        $request = Yii::$app->request;
        if ($request->isAjax) {
            $limit  = $request->get('limit');
            $offset = $request->get('offset');
            $search = $request->get('search');
            $data   = UserList::getList($limit, $offset,$search);
            return $this->response($data, 200);
        }
        return $this->render('list',['auth'=>$au]);
    }

    public function actionDel()
    {

        $request = Yii::$app->request;
        $id      = $request->get('id');
        if ($id == 1) {
            return $this->goBack('index.php?r=user/list');
        }
        if (\Yii::$app->user->id == 1) {
            $model = AdminUser::findOne($id);
            $model->delete();
        }

        return $this->goBack('index.php?r=user/list');

    }

    /**
     *
     * @author fushl 2017-05-25
     * @return [type] [description]
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id      = $request->get('id');
        if (\Yii::$app->user->id == 1) {
            $brands = Brand::getAll();
            $user   = AdminUser::findOne($id);
            $auth   = Auth::getAll();
            return $this->render('edit', ['brands' => $brands, 'auth' => $auth, 'data' => $user]);
        }

    }

    /**
     * 重置密码
     * @author fushl 2017-05-25
     * @return [type] [description]
     */
    public function actionResetpassword()
    {

        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');

            $password1 = Yii::$app->request->post('password1');
            $password2 = Yii::$app->request->post('password2');

            if (empty($password1) || empty($password2)) {
                return $this->response('', 301, '密码不能为空');
            } elseif ($password1 != $password2) {
                return $this->response('', 302, '密码不相等');
            } else {

                $result = AdminUser::findOne($id);

                //密码加密
                $password_hash = Yii::$app->getSecurity()->generatePasswordHash($password1);

                //更新密码
                Yii::$app->db
                    ->createCommand()
                    ->update('admin_user', ['password' => $password_hash], 'id=' . $id)
                    ->execute();

                return $this->response($result, 200);
            }
        }
    }

    public function actionChangePassword(){
        if (Yii::$app->request->isAjax){
            
        }
        return $this->render('password');
    }

    /**
     * 编辑用户数据
     * @author fushl 2017-05-25
     * @return [type] [description]
     */
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        if (\Yii::$app->user->id == 1) {
            if ($request->isAjax) {
                $user = new UserForm();
                $user->setScenario('update');
                if ($user->loadAjax($request->post('data')) && $user->validate()) {
                    $data = $this->unserializeData($request->post('data'));
                    $user->update($data['id']);
                    return $this->response('', 200);
                } else {
                    return $this->response('', 302, $user->getOneError());
                }

            } else {
                return $this->response('权限不足');
            }
        }
    }

    public function actionDisAdmin()
    {
        $error = '';
        $id = Yii::$app->request->post('id');
        if (empty($id)) {
            return $this->response('', 300, '传入值不能为空');
        }
        $user = AdminUser::findOne($id);
        if (!empty($user)) {
            if ($user->is_disabled == 1) {
                $user->is_disabled = 0;
                $error = '启用';
            } else {
                $error = '禁用';
                $user->is_disabled = 1;
            }

            $is_save = $user->save();
            if ($is_save) {
                return $this->response('', 200, $error.'成功');
            } else {
                return $this->response('', 300, $error.'失败');
            }

        } else {
            return $this->response('', 300, '用户不存在');
        }

    }

    public function actionAuth(){
        $group = Yii::$app->request->get('auth',2);
        if (($group == 1) ||($group >5)){
            throw new \Exception('参数异常');
        }
      
        $auth = Auth::find()->where(['>','id',1])->all();
        $contents = Auth::findOne($group);
        $name = $contents->auth;
        $content = $contents->content;
        if (!empty($content)){
            $arr=explode(',',$content);
        }else{
            $arr = [];
        }
        return $this->render('auth',['auth'=>$auth,'content'=>$arr,'name'=>$name,'id'=>$group]);
    }

    public function actionUpdateAuth(){
        if (Yii::$app->request->isAjax){
            $data  = Yii::$app->request->post('data');
            $arr  =json_decode($data,true);
            $auth_id = $arr['auth'];
            $name = $arr['name'];
            unset($arr['name']);
            unset($arr['auth']);
            $content = [];
            foreach ($arr as $k=>$v){
                $content[] = $k;
            }
            if (!empty($content)){
                $str = implode(',',$content);
            }else{
                $str = '';
            }
            $model = Auth::findOne($auth_id);
            $model->content = trim($str,',');
            $model->auth = $name;
            $ret = $model->save();
            if ($ret != false){
                return $this->response('',200);
            }else{
                return $this->response('',300);  
            }

        }
    }

}
