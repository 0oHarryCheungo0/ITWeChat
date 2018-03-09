<?php

namespace common\api;

use common\models\SmsLog;
use Yii;

class SMS
{
    CONST CHINA = 1;

    CONST HK = 2;

    CONST TW = 3;

    CONST AM = 4;

    private static $account_map = [
        '1' => [
            'regcode' => 'ZXHD-CRM-0100-OCNSIC',
            'pwd' => '97552639',
        ],
        '2' => [
            'regcode' => 'ZXHD-CRM-0100-MKKIOT',
            'pwd' => '63583967',
        ],
        '3' => [
            'regcode' => 'ZXHD-CRM-0100-WIXRGN',
            'pwd' => '12533275',
        ],
        '4' => [
            'regcode' => 'ZXHD-CRM-0100-AMFRUJ',
            'pwd' => '48854428',
        ],
    ];

    public static function send($phone, $msg, $tag = '【I.T】', $type = SMS::CHINA)
    {

        $regcode = self::$account_map[$type]['regcode'];
        $pwd = md5(self::$account_map[$type]['pwd']);
        $content = urlencode(mb_convert_encoding($tag . $msg, 'gbk', 'utf-8'));
        $url = "http://sms.pica.com/zqhdServer/sendSMS.jsp?regcode={$regcode}&pwd={$pwd}&phone={$phone}&CONTENT={$content}&extnum=&level=1&schtime=&reportflag=1&url=url&smstype=4&hardKEY=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        $content = file_get_contents($url);
        $parameters = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
        $result_string = $parameters->result;
        $result = explode(',', $result_string);
        $code = $result[0];
        $result_msg = self::errorMap($code);
        self::log($phone, $code, $result_msg, $msg);
        return $code == 0;
    }

    public static function log($phone, $flag, $result, $msg)
    {
        $log = new SmsLog;
        $log->phone = $phone;
        $log->flag = $flag;
        $log->remark = $result;
        $log->msg = $msg;
        $log->send_time = date('Y-m-d H:i:s');
        if ($log->save()) {
            return true;
        } else {
            Yii::error($log->getErrors());
        }
    }

    public static function errorMap($result)
    {
        $errorMap = [
            '0' => '成功',
            '-99' => '其它故障',
            '5' => '具体的禁发词',
            '-1' => '用户名或密码不正确',
            '-2' => '余额不够',
            '-3' => '帐号没有注册',
            '-4' => '内容超长',
            '-5' => '账号路由为空',
            '-6' => '手机号码超过1000个（或手机号码非法或错误）',
            '-8' => '扩展号超长',
            '-12' => 'Key值要是32位长的英文，建议32个a',
            '-13' => '定时时间错误或者小于当前系统时间',
            '-17' => '手机号码为空',
            '-18' => '号码不是数字或者逗号不是英文逗号',
            '-19' => '短信内容为空',
        ];
        return isset($errorMap[$result]) ? $errorMap[$result] : '未知错误:' . $result;
    }

}
