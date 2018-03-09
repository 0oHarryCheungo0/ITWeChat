<?php

use yii\helpers\Url;

?>
<div class="bodyMain recordBOx">
    <ul class="news_nav record_nav">
        <li>
            <a href="javascript:void(0)">
                <span>●</span><span><?=Yii::t('app','您的消费记录')?></span>
            </a>
        </li>
    </ul>
   <div class="recordHide recordMh recordHide0">
    <div class="recordMain recordMain0" id="recordMain">
            <?php
            foreach ($logs as $year => $year_item) {
                if (!$year_item) continue;
                ?>

                <?php
                foreach ($year_item as $month => $log) {
                    ?>
                    <div class="recordTitle">
                        <h4><?= $year ?> <?= $month ?></h4>
                    </div>
                    <?php foreach ($log as $item) {
                        ?>
                        <div class="recordList">
                            <a href="<?= Url::to(['index/record-detail', 'id' => $item['id'], 'brand_id' => Yii::$app->request->get('brand_id')]); ?>">
                                <div class="left time_left">
                                    <h4><?php echo date('Y-m-d', strtotime($item['TXDATE'])) ?></h4>
                                     <p><?=$item['STORE'];?></p>
                                </div>
                                <div class="left order_right">
                                    <div class="orderMain">
                                        <div class="round"><img src="/public/sit/images/html5icon.png"></div>
                                        <div class="round"></div>
                                        <div class="toright iconfont icon-you"></div>
                                        <h4>RMB:<?= $item['DTRAMT'] ?></h4>
                                        <p>Memo No.:<?= $item['MEMONO'] ?></p>
                                        <p>Qty:<?= $item['QTY'] ?></p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } ?>

        </div>
    </div>

    <div class="singUp-bt">
        <img src="<?= Url::to('@web/public/images/bottom2.png') ?>">
    </div>
</div>
<script src="/public/sit/js/common-p.js"></script>
<script src="/public/js/iscroll-probe.js"></script>
<script>

    function isPassive() {
        var supportsPassiveOption = false;
        try {
            addEventListener("test", null, Object.defineProperty({}, 'passive', {
                get: function () {
                    supportsPassiveOption = true;
                }
            }));
        } catch(e) {}
        return supportsPassiveOption;
    }
    var myScroll0,maH,index=0;
    function touchMove(){
        var $recordMain=$('.recordMain'+index);
        var $recordTitle=$recordMain.find('.recordTitle');
        if($recordTitle.length<1){return false;}
        $recordTitle.each(function(){
            var t=$(this).offset().top;
            var zj=maH*0.5;
            var rt=zj/t;
            if(rt>=1){
                rt=1;
            }
            if(rt<0){
                rt=0;
            }
            $(this).find('h4').attr('style','transform:scale('+rt+','+rt+')');
        });
        $recordMain.find('.round').each(function() {
            var t=$(this).offset().top;
            if((maH-t)>(maH*0.8)){
                $(this).addClass('roundAct');
            }else{
                $(this).removeClass('roundAct');
            }
        });
        $recordMain.find('.round:first').addClass('roundAct');
    }

    Pace.on('hide',function(){
        var bh=document.documentElement.clientHeight;
        var viph=Number($('.singUp-bt').height())+Number($('.itokenMain').height())+Number($('.itokenBox').height())+30;
        var $mainBg=$('.recordHide');
        maH=bh-viph;
        $mainBg.css({'height':maH+'px'});
        $('.recordMain').css({'min-height':(maH+20)+'px'});
        index=0;
        myScroll0 = new IScroll('.recordHide0', { probeType:5, mouseWheel: true,click:true,taps:true,
            preventDefault: false,
            preventDefaultException: { tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|A)$/},});
        myScroll0.on('scroll', touchMove);
        myScroll0.on('scrollEnd', touchMove);
    });
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, isPassive() ? {
        capture: false,
        passive: false
    } : false);


</script>
<script>


     // Pace.on('hide', function () {
    //     var bh = document.documentElement.clientHeight;
    //     var viph = Number($('.singUp-bt').height()) + 25;
    //     var $mainBg = $('.recordHide');
    //     var maH = bh - viph;
    //     $mainBg.css({'height': maH + 'px'});
    //     touchMove();

    //     function touchMove(e) {
    //         var $recordMain = $('.recordMain');
    //         $recordMain.find('.recordTitle').each(function () {
    //             var t = $(this).offset().top;
    //             var zj = maH * 0.5;
    //             var rt = zj / t;
    //             if (rt >= 1) {
    //                 rt = 1;
    //             }
    //             if (rt < 0) {
    //                 rt = 0;
    //             }
    //             $(this).find('h4').attr('style', 'transform:scale(' + rt + ',' + rt + ')');
    //         });
    //         $recordMain.find('.round').each(function () {
    //             var t = $(this).offset().top;
    //             if ((maH - t) > (maH * 0.8)) {
    //                 $(this).addClass('roundAct');
    //             } else {
    //                 $(this).removeClass('roundAct');
    //             }

    //         });
    //         $recordMain.find('.round:first').addClass('roundAct');
    //     }

    //     document.getElementById("recordMain").addEventListener('touchmove', touchMove, false);
        <?php
        if (!$logs){
        ?>
        alertHtml("<?=Yii::t('app', '您暂时没有消费记录')?>", function () {
            location.href = "<?=$home?>";
        });
//        setTimeout("location.href='" + "<?//=$home?>//" + "'", 3000);
        <?php
        }
        ?>
    // });

</script>