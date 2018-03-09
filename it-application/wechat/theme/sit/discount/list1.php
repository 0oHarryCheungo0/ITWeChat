<?php use yii\helpers\Url; ?>

<div class="bodyMain">
<ul class="news_nav">
    <li class="active">
           <span><?php if ($normal): ?>●<?php endif; ?></span><span><?= Yii::t('app', '常规优惠'); ?></span>
    </li>
    <li>
            <span><?php if ($expire): ?>●<?php endif; ?></span><span><?= Yii::t('app', '限时优惠'); ?></span>
    </li>
</ul>
<div class="clear"></div>
<div class="news_main">
    <ul class="news_info">
        <?php
        if (!$data['data']['data']) {
            ?>
            <div style="padding-top:30%;text-align: center;font-size:16px;color: #8a8a8a;">
                <img src="/public/images/notk.png" style="width:108px;">
                <p><?=Yii::t('app','暂无常规优惠!')?></p>
            </div>
            <?php
        }
        ?>
    <?php foreach ($data['data']['data'] as $k => $v): ?>
        <?php if (!empty($v['title'])):?>
        <li <?php if ($v['is_look'] == 0): ?>class="already"<?php endif; ?>>
            <a href="javascript:void(0)" data-url="<?=Url::toRoute(['discount/detail','type'=>1,'children_type'=>$v['type'],'id'=>$v['id']])?>">
                <div class="news_time"><?=$v['create_time']?></div>
                <div class="news_html"><?=$v['title']?></div>
            </a>
        </li>
    <?php endif;?>
    <?php endforeach; ?>
    </ul>
    <ul class="news_info">
        <?php
        if (!$data['data']['data1']) {
            ?>
            <div style="padding-top:30%;text-align: center;font-size:16px;color: #8a8a8a;">
                <img src="/public/images/notk.png" style="width:108px;">
                <p><?=Yii::t('app','暂无限时优惠!')?></p>
            </div>
            <?php
        }
        ?>
         <?php foreach ($data['data']['data1'] as $k1 =>$v1):?>
            <?php if (!empty($v1['title'])):?>
        <li <?php if ($v1['is_look'] == 0): ?>class="already"<?php endif; ?>>
            <a href="javascript:void(0)" data-url="<?=Url::toRoute(['discount/detail','type'=>3,'children_type'=>$v1['type'],'id'=>$v1['id']])?>">
                <div class="news_time"><?=$v1['create_time']?></div>
                <div class="news_html"><?=$v1['title']?></div>
            </a>
        </li>
    <?php endif; ?>
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