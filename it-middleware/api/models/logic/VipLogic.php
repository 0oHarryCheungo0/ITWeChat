<?php

namespace api\models\logic;

class VipLogic
{
    //VIPType 和  会员等级映射表
    static $type_group = [
        //小IT
        'SIT' => [
            'types' => [
                'CN_SITT0',
                'CN_SIT',
                'CN_IPASS',
                'CN_VIPASS',
            ],
            'indexs' => [
                'CN_VIPASS',
                'CN_IPASS',
                'CN_SIT',
                'CN_SITT0',
            ],
        ],
        //大IT
        'BIT' => [
            'types' => [
                'CN_BITT0',
                'CN_BIT',
                'CN_VPASS',
                'CN_VIPASS',
            ],
            'indexs' => [
                'CN_VIPASS',
                'CN_VPASS',
                'CN_BIT',
                'CN_BITT0',
            ],

        ],
    ];

    public static function validateType($type)
    {
        return isset(self::$type_group[$type]);
    }

    public static function getTypes($type = "CN_BITT0")
    {
        $type = strtoupper($type);
        return self::$type_group[$type]['types'];
    }

    public static function getLevel($group, $type_key)
    {
        $group = strtoupper($group);
        return self::$type_group[$group]['indexs'][$type_key];
    }

    public static function getLevels($group)
    {
        $group = strtoupper($group);
        return self::$type_group[$group]['indexs'];
    }
}
