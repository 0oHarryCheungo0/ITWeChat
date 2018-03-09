<?php use yii\helpers\Url; ?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <title>NEWS</title>
    <link rel="stylesheet" href="/public/sit/css/it.style.css"/>
    <link rel="stylesheet" href="/public/sit/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/sit/fonts/iconfont.css"/>
    <link rel="stylesheet" href="/public/sit/css/pageCss.css"/>
    <script src="/public/sit/js/pace.min.js"></script>
</head>
<body>
<div class="bodyMain">
<ul class="news_nav">
    <li class="active">
           <span><?php if ($is_member): ?>●<?php endif; ?></span><span><?= Yii::t('app', '会员资讯'); ?></span>
    </li>
    <li>
            <span><?php if ($is_point): ?>●<?php endif; ?></span><span><?= Yii::t('app', '积分资讯'); ?></span>
    </li>
</ul>
<div class="clear"></div>
<div class="news_main">
    <ul class="news_info">
        <?php
        if (!$member_news['data']) {
            ?>
            <div style="padding-top:30%;text-align: center;font-size:16px;color: #8a8a8a;">
                <img src="/public/images/notk.png" style="width:108px;">
                <p><?=Yii::t('app','暂无会员资讯!')?></p>
            </div>
            <?php
        }
        ?>
         <?php foreach ($member_news['data'] as $k => $v): ?>
        <li <?php if ($v['is_look'] == 0):?>class='already'<?php endif;?>>
            <a href="javascript:void(0)" data-url="<?=Url::toRoute(['news/detail','type'=>1,'children_type'=>$v['type'],'id'=>$v['id']])?>">
                <div class="news_time"><?=$v['create_time']?></div>
                <div class="news_html"><?=$v['title']?></div>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
    <ul class="news_info">
        <?php
        if (!$point_news['data']) {
            ?>
            <div style="padding-top:30%;text-align: center;font-size:16px;color: #8a8a8a;">
                <img src="/public/images/notk.png" style="width:108px;">
                <p><?=Yii::t('app','暂无积分资讯!')?></p>
            </div>
            <?php
        }
        ?>
          <?php foreach ($point_news['data'] as $k1 =>$v1):?>
        <li <?php if ($v1['is_look'] == 0):?>class='already'<?php endif;?>>
            <a href="javascript:void(0)" data-url="<?=Url::toRoute(['news/detail','type'=>3,'children_type'=>$v1['type'],'id'=>$v1['id']])?>"
                >
                <div class="news_time"><?=$v1['create_time']?></div>
                <div class="news_html"><?=$v1['title']?></div>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="singUp-bt">
    <img src="/public/sit/images/bottom2.png">
</div>
</div>
<script src="/public/sit/js/jquery.min.js"></script>
<script>
    Pace.on('hide', function(){
    var bh=document.documentElement.clientHeight;
    var viph=Number($('.singUp-bt').height())+10+45+40;
    var $mainBg=$('.news_main');
    $mainBg.css({'height':(bh-viph)+'px'});
    });
    $(document).on('click','.news_nav li',function(){
        $('.news_nav li').removeClass('active');
        $(this).addClass('active');
        var index=$(this).index();
        $('.news_main').find('ul').each(function(i){
            $(this).hide();
            if(i==index){
                $(this).show();
            }
        });
    });

    $('.news_info li').on('click',function(){
        $(this).removeClass('already');
        window.location.href=$(this).find('a').attr('data-url');
    })
    
    window.onpageshow = function(event){
    if (event.persisted) {
    window.location.reload();
    }
    }
    

</script>
</body>
</html>