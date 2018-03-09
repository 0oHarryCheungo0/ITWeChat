<?php use yii\helpers\Url; ?>
<div class="bodyMain">
<ul class="news_nav">
    <li class="active">
           <span><?php if ($is_member): ?>●<?php endif; ?></span><span>会员资讯</span>
    </li>
    <li>
            <span><?php if ($is_point): ?>●<?php endif; ?></span><span>积分资讯</span>
    </li>
</ul>
<div class="clear"></div>
<div class="news_main">
    <ul class="news_info">
        <?php foreach ($member_news['data'] as $k => $v): ?>
        <li <?php if ($v['is_look'] == 0):?>class='already'<?php endif;?>>
            <a href="<?=Url::toRoute(['news/detail','type'=>1,'children_type'=>$v['type'],'id'=>$v['id']])?>" data-url="<?=Url::toRoute(['news/detail','type'=>1,'children_type'=>$v['type'],'id'=>$v['id']])?>">
                <div class="news_time"><?=$v['create_time']?></div>
                <div class="news_html"><?=$v['title']?></div>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
    <ul class="news_info">
    <?php foreach ($point_news['data'] as $k1 =>$v1):?>
        <li <?php if ($v1['is_look'] == 0):?>class='already'<?php endif;?>>
            <a <?php if ($v1['type'] == 2):?>href="<?=Url::toRoute('index/index')?>" <?php else: ?>href="<?=Url::toRoute(['news/detail','type'=>3,'children_type'=>$v1['type'],'id'=>$v1['id']])?>" <?php endif; ?> >
                <div class="news_time"><?=$v1['create_time']?></div>
                <div class="news_html"><?=$v1['title']?></div>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
</div>
<div class="singUp-bt">
    <img src="/public/sit/images/bottom.png">
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
        $('.news_main').find('ul').each(function(i){
            $(this).hide();
            if(i==index){
                $(this).show();
            }
        });
    });
</script>
</body>
</html>