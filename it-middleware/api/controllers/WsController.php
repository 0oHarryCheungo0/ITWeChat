<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/22
 * Time: 下午2:17
 */

namespace api\controllers;


use api\models\service\VipService;
use api\ws\createResponse;
use api\ws\Login;
use api\ws\VipInfo;
use api\ws\VipResponse;
use yii\web\Controller;
use yii;

class WsController extends Controller
{
    public function actions()
    {
        return [
            'soap' => [
                'class' => 'conquer\services\WebServiceAction',
                'classMap' => [
                    'Vips' => 'api\ws\Vips',
                    'Request' => 'api\ws\Request',
                    'VipQuery' => 'api\ws\VipQuery',
                    'Response' => 'api\ws\Response',
                ],
            ],
        ];
    }

    /**
     * @desc 接口描述
     * @param string $account
     * @param string $pwd
     * @param string $vip_code
     * @return \api\ws\VipResponse $response
     * @soap
     */
    public function getProfile($account, $pwd, $vip_code)
    {
        $response = new VipResponse();
        $loginObj = new Login($account, $pwd);
        $loginCheck = $loginObj->checkLogin();
        if ($loginCheck !== true) {
            Yii::error('返回数' . $loginCheck, 'ERROR');
            $response->status = $loginCheck;
            $response->message = '登录验证信息错误';
        } else {

            $finder = VipService::findVip($vip_code);
            if ($finder->state == 404) {
                //本地数据库不存在，访问CRM数据库获取
                $vips = VipService::getCRMVIP($vip_code);
                if (!$vips) {
                    $response->status = 10404;
                    $response->message = '会员不存在';
                } else {
                    $info = $vips->info;
                    $vip_info = new VipInfo();
                    $vip_info->TELNO = $vips->phone;
                    $vip_info->Emailaddr = $vips->email;
//                    $vip_info->vip_code = $vips->vip_code;
//                    $vip_info->vip_type = $vips->vip_type;
//                    $vip_info->exp_date = $vips->exp_date;
//                    $vip_info->alt_id = $vips->alt_id;
//                    $vip_info->name = $info->name;
//                    $vip_info->first_name = '';
//                    $vip_info->last_name = '';
//                    $vip_info->birthday = $info->DOB;
//                    $vip_info->telno = $info->telno;
//                    $vip_info->addr1 = $info->addr1;
//                    $vip_info->addr2 = $info->addr2;
//                    $vip_info->email = $info->email;
//                    $vip_info->sex = $info->sex;
                    $response->status = 0;
                    $response->message = 'SUCCESS';
                    $response->vipInfo = $vip_info;
                }
            } else {
                $info = $finder->data->info;
                $vip_info = new VipInfo();
//                $vip_info->vip_code = $finder->data->vip_code;
//                $vip_info->vip_type = $finder->data->vip_type;
//                $vip_info->exp_date = $finder->data->exp_date;
//                $vip_info->alt_id = $finder->data->alt_id;
//                $vip_info->name = $info->name;
//                $vip_info->first_name = '';
//                $vip_info->last_name = '';
//                $vip_info->birthday = $info->DOB;
//                $vip_info->telno = $info->telno;
//                $vip_info->addr1 = $info->addr1;
//                $vip_info->addr2 = $info->addr2;
//                $vip_info->email = $info->email;
//                $vip_info->sex = $info->sex;
                if ($finder->state == 302) {
                    $response->status = 10302;
                    $response->message = '会员信息已变动';

                } else {
                    $response->status = 0;
                    $response->message = 'SUCCESS';
                }
                $response->vipInfo = $vip_info;
            }
        }
        return $response;
    }

    /**
     * @param string $account
     * @param string $pwd
     * @param string $vip_code
     * @param \api\ws\updateParams $params
     * @return \api\ws\VipResponse $response
     */
    public function updateProfile($account, $pwd, $vip_code, $params)
    {
        return new VipResponse();
    }

    /**
     * @param string $account
     * @param string $pwd
     * @param \api\ws\createParams $params
     * @return \api\ws\createResponse $response
     */
    public function createVip($account, $pwd, $params)
    {
        return new createResponse();
    }


}