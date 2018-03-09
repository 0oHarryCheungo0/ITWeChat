<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/26
 * Time: 下午4:12
 */

namespace backend\service\wechat;


use common\models\WechatReplys;
use common\models\WechatResource;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;

class AutoReply
{
    public static function getContent($keyword, $brand_id)
    {
        //首先精确查询
        /** @var WechatReplys $reply */
        $reply = WechatReplys::find()
            ->where(['keyword' => $keyword, 'brand_id' => $brand_id, 'status' => 1])
            ->one();
        if ($reply) {
            $reply->match_times++;
            $reply->save();
            return self::buildReply($reply);
        } else {
            //找不到自动回复匹配，采用默认匹配
            return self::getDefault($brand_id);
        }

    }


    public static function getDefault($brand_id)
    {
        /** @var WechatReplys $reply */
        $reply = WechatReplys::find()
            ->where(['brand_id' => $brand_id, 'response_type' => 4])
            ->one();
        if ($reply) {
            return $reply->response_text;
        } else {
            return '';
        }


    }

    /**
     * 构造回复内容
     * @param WechatReplys $reply
     * @return string
     */
    public static function buildReply(WechatReplys $reply)
    {
        if ($reply->response_type == 0) {
            //文字回复
            return $reply->response_text;
        } else if ($reply->response_type == 5) {
            //图片回复
            $media_id = $reply->media_id;
            return new Image(['media_id'=>$media_id]);
        } else {
            $ids = json_decode($reply->response_source_ids, true);
            if (!$ids) {
                return '';
            }
            $resources = WechatResource::find()
                ->where(['status' => 1])
                ->andWhere(['in', 'id', $ids])
                ->all();
            $news = [];
            foreach ($resources as $resource) {
                $news[] = new News([
                    'title' => $resource->title,
                    'description' => $resource->description,
                    'url' => $resource->url,
                    'image' => $resource->image
                ]);
            }
            return $news;
        }
        return '';

    }

}