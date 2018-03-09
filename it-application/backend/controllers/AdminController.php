<?php
namespace backend\controllers;

use backend\components\MyBehavior;
use backend\models\AdminUser;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;

class AdminController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    /**
     * 权限控制ACF
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['login'],
                        'roles'   => ['?', '@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['logout', 'index'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
            [
                'class' => MyBehavior::className(),
            ],
        ];
    }

    protected function response($data = null, $code = 0, $msg = 'SUCCESS')
    {
        $type = Yii::$app->request->get('format', false);
        if (!$type) {
            $headers      = Yii::$app->request->headers;
            $content_type = $headers->get('format', 'json');
            $type         = $content_type == 'json' ? 'json' : 'xml';
        } else {
            if ($type !== 'json' && $type != 'xml') {
                $type = 'json';
            }
        }
        $ret              = ['code' => $code, 'msg' => $msg, 'data' => $data];
        $response         = Yii::$app->response;
        $response->format = $type;
        $response->data   = $ret;
        Yii::trace($ret);
        exit($response->send($ret));
    }

    /**
     * 登录用户
     */
    public function actionLogin()
    {
        // $session = Yii::$app->session;
        // if($session->isActive)
        // {
        //     echo 'session is active';
        // }
        $request = Yii::$app->request;
        if (Yii::$app->user->identity != null) {
            $this->redirect(Url::toRoute('/store/list', true));
        }

        if ($request->isAjax) {
            $data     = $this->unserializeData($request->post('data'));
            $username = urldecode($data['username']);
            $password = urldecode($data['password']);
            if (empty($username)) {
                return $this->response('', 300, '用户名不能为空');
            }
            if (empty($password)) {
                return $this->response('', 300, '密码不能为空');
            }

            $identity = AdminUser::findOne(['username' => $username]);
            if (!empty($identity)) {
                if (Yii::$app->getSecurity()->validatePassword($password, $identity->password)) {
                    if ($identity->is_disabled == 1) {
                        return $this->response('', 301, '用户已被禁用');
                    } else {
                        Yii::$app->user->login($identity);
                        if (Yii::$app->user->id != 1) {
                            return $this->response('', 201);
                        } else {
                            return $this->response('', 200);
                        }

                    }

                } else {
                    return $this->response('', 301, '密码错误');
                }
            } else {
                return $this->response('', 302, '用户不存在');
            }
        }
        return $this->renderPartial('login');
    }

    /**
     * 用户注销
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(Url::toRoute('/admin/login', true));
    }

    /**
     * 管理员列表
     */
    public function actionList()
    {

        $query      = AdminUser::find(true);
        $count      = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 1]);

        $list = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('list', ['list' => $list, 'pagination' => $pagination]);
    }

}
