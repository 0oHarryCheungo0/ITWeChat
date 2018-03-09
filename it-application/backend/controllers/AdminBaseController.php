<?php
namespace backend\controllers;

use backend\models\AdminUser;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use backend\models\Auth;

class AdminBaseController extends Controller
{
    protected $brand_id = null;

    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        if (!$session->has('brand_id')) {
            $user = Yii::$app->user->identity;
            if ($user === null) {
                return $this->redirect(Url::toRoute('/admin/login', true))->send();
            } else {
                //判断是否超管
                //判断是否普通管理员，session记录品牌id
                if ($user->id != 1) {
                    $id    = $user->id;
                    $brand = AdminUser::find()->where(['id' => $id])->one();
                    $session->set('brand_id', $brand->brand_id);
                    $auth = Auth::findOne($user->auth_id);
                    $session->set('auth', $auth->content);
                }else{
                    $session->set('auth', '');
                }
            }

        }
        $this->brand_id = $session->get('brand_id');
        return true;
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
     * 将异步表单数据处理成数组形式
     *
     * @param string $str 源数据
     * @return array $new 转换后数据
     */
    protected function unserializeData($str = '')
    {
        $array = explode('&', $str);

        $new = [];
        foreach ($array as $k => $v) {
            $arr          = explode('=', $v);
            $new[$arr[0]] = $arr[1];
        }
        return $new;
    }

    //获取当前公众号的brand_id;
    public static function brandId()
    {
        $id = \yii::$app->user->id;
        return AdminUser::findOne(["id" => $id])['brand_id'];
    }

    /**
     * [getOneError description]
     * @author fushl 2017-05-25
     * @param  array $data [description]
     * @return str      [description]
     */
    public function getOneError($data = [])
    {
        $error = '';
        foreach ($data as $k => $v) {
            $error = $v['0'];
            break;
        }
        return $error;
    }

}
