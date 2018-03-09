<?php use yii\helpers\Url; ?>
<body>
<link rel="stylesheet" href="/public/css/animate.min.css"/>
<div class="bodyMain">
<ul class="news_nav">
    <li class="active">
        <span>●</span><span>常规优惠</span>
    </li>
    <li>
        <span>●</span><span>限时优惠</span>
    </li>
</ul>
<div class="clear"></div>
<div class="news_main">
    <ul class="news_info">
        <?php foreach ($data['data']['data'] as $k => $v): ?>
        <li  <?php if ($v['is_look'] == 0):?>class='already'<?php endif; ?>>
            <a href="<?=Url::toRoute(['discount/detail','type'=>1,'children_type'=>$v['type'],'id'=>$v['id']])?>" data-url="<?=Url::toRoute(['discount/detail','type'=>1,'children_type'=>$v['type'],'id'=>$v['id']])?>">
                <div class="news_time"><?=$v['create_time']?></div>
                <div class="news_html"><?=$v['title']?></div>
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
    <ul class="news_info">
    <?php foreach ($data['data']['data1'] as $k1 =>$v1):?>
        <li <?php if ($v1['is_look'] == 0):?>class='already'<?php endif; ?>>
            <a href="<?=Url::toRoute(['discount/detail','type'=>3,'children_type'=>$v1['type'],'id'=>$v1['id']])?>" data-url="<?=Url::toRoute(['discount/detail','type'=>3,'children_type'=>$v1['type'],'id'=>$v1['id']])?>">
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