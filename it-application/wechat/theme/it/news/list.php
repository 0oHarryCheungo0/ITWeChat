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
    <link rel="stylesheet" href="/public/css/it.style.css"/>
    <link rel="stylesheet" href="/public/fonts/font.style.css"/>
    <link rel="stylesheet" href="/public/fonts/iconfont.css"/>
    <link rel="stylesheet" href="/public/css/pageCss.css"/>
    <script src="/public/js/pace.min.js"></script>
</head>
<body>
<link rel="stylesheet" href="/public/css/animate.min.css"/>
<div class="bodyMain">
<ul class="news_nav">
    <li class="active">
           <span><?php if ($is_member): ?>●<?php endif; ?></span><span><?= Yii::t('app', '会员资讯') ?></span>
    </li>
    <li>
            <span><?php if ($is_point): ?>●<?php endif; ?></span><span><?= Yii::t('app', '积分资讯') ?></span>
    </li>
</ul>
<div class="clear"></div>
<div class="news_main">
    <ul class="news_info">
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
    <img src="/public/images/bottom2.png">
</div>
</div>
<script src="/public/js/jquery.min.js"></script>
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
        console.log(index);
        //$('.news_main').find('ul').attr('class','news_info offer-info').addClass('animated bounceOutLeft').eq().addClass('animated bounceInRight');
        $('.news_main').find('ul').each(function(i){
            //console.log(i);
            $(this).attr('class','news_info');
            if(i==index){
                $(this).show();
                $(this).addClass('animated bounceInRight');
            }else{
                $(this).addClass('animated bounceOutLeft');
            }

        });
    });

    $('.news_info li').on('click',function(){
        var url=$(this).find('a').attr('data-url');
        var time=1500;
        if($(this).attr("class")=='already'){
            time=15;
        }
        $(this).addClass('newsBe');
        setTimeout(function(){
           window.location.href=url;
        },time)
    })
</script>
</body>
</html>