<?php
return [
    'wechat_url' => 'http://localhost:81',
    //中间件-地址
    'middleware' => 'http://localhost:83',

    //中间件-查找用户
    'middleware_find' => 'http://localhost:83/user/find',

    'middleware_phone' => 'http://localhost:83/user/phone',

    //中间件-查看用户基础信息
    'middleware_user_info' => 'http://localhost:83/user/info',

    //中间件-用户注册
    'middleware_reg' => 'http://localhost:83/reg/vip',

    //中间件-新用户注册
    'create_vip' => 'http://localhost:83/reg/reg',

    //中间件-积分记录查询
    'middleware_bonus_query' => 'http://196mw.cyril-c.cn/bonus/query',

    //中间件-添加积分
    'middleware_bonus_add' => 'http://localhost:83/bonus/add',

    //中间件-获取积分记录
    'middleware_bonus_get' => 'http://localhost:83/record/get',

    //中间件-获取积分使用记录
    'middleware_bonus_spend' => 'http://localhost:83/record/spend',

    //中间件-获取消费记录
    'middleware_record_query' => 'http://localhost:83/spend/query',

    //中间件-消费记录详情
    'middleware_spend_detail' => 'http://localhost:83/spend/detail',

    //中间件-获取消费记录
    'middleware_spend_query' => 'http://localhost:83/spend/query',

    //队列任务-添加积分接口
    'queue_bonus' => 'http://localhost:81/queue/bonus',

    //队列任务-刷新用户记录
    'queue_renew' => 'http://localhost:81/user/renew',

    //队列任务-发送短信
    'queue_msg' => 'http://localhost:81/queue/bonus',

    //队列任务-同步积分日志
    'queue_sync_bonus' => 'http://localhost:83/crontab/bplog',

    'news' => 'http://localhost:81/queue/handle',
    // 队列任务－发布专属优惠discount
    'discount' => 'http://localhost:81/queue/discount',
    //资读专属优惠初始化
    'news_discout_reset' => 'http://localhost:81/queue/reset',

    'discount_rank' => 'http://localhost:81/queue/relase',

    'discount_birthday' => 'http://localhost:81/queue/birthday',

    'news_rank' => 'http://localhost:81/queue/newsrank',

    'news_expire' => 'http://localhost:81/queue/newsexpire',

    'news_sign' => 'http://localhost:81/queue/sign',

    'news_prefect' => 'http://localhost:81/queue/prefect',

    'queue_send_message' => 'http://wechatcn.itezhop.com/queue/group-sender',

    'update_vip' => 'http://localhost:81/queue/update-vip',

    'middleware_user_update' => 'http://localhost:83/user/update',

    'middleware_user_renew'=>'http://localhost:83/user/renew-info',

    'middleware_user_refresh'=>'http://localhost:83/user/refresh',

    'renew_info' => 'http://localhost:81/queue/renew-info',

    'import_staff'=>'http://localhost:81/queue/import-staff',

    'day_spned'=>'http://localhost:83/spend/today',

    'bonus_used'=>'http://localhost:83/bonus/used',

    'point_search'=>'http://localhost:83/bonus/spend-point',

    'send_spend_message'=>'http://localhost:81/queue/spend',

    'send_point_message'=>'http://localhost:81/queue/point',

    'send_exp_message'=>'http://localhost:81/queue/exp-point',

];
