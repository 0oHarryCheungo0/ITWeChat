<?php
namespace backend\service\wechat;

use EasyWeChat\Support\Collection;

/**
 * 微信消息处理分发
 */
class MsgHandler
{
    public static function handler(Collection $message, $brand_id)
    {
        switch ($message->MsgType) {
            case 'event':
                return EventHandler::send($message, $brand_id);
                break;
            case 'text':
                return TextHandler::send($message, $brand_id);
                break;
            case 'image':
            case 'voice':
            case 'video':
            case 'location':
            case 'link':
                return '已收到' . $message->MsgType . '消息，暂时不处理';
                break;
            default:
                return '';
                break;
        }
    }
}
