<?php
use yii\helpers\Url;

?>
<div class="bodyMain">
    <div class="itokenMain">
        <img src="/public/images/itoken_03.jpg" class="imgcss">
        <div class="itokenInfo">
            <div class="left">
                <img src="/public/images/jf_06.png">
                <p><?= Yii::t('app', '会员积分') ?></p>
            </div>
            <div class="left">
                <div class="jf-text">
                    <h4>I.TOKEN</h4>
                    <h4><?= intval($points['bonus']) ?></h4>
                    <p><?= Yii::t('app', '30天内将要到期') ?> <?= intval($points['exp_bonus']) ?> <?= Yii::t('app', '积分') ?></p>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="itokenBox">
        <div class="left active"><?= Yii::t('app', '积分获得明细') ?>
            <hr>
        </div>
        <div class="left"><?= Yii::t('app', '积分使用明细') ?>
            <hr>
        </div>
        <div class="clear"></div>
    </div>
   <div class="itokenList" style="position: relative">
        <div class="recordHide recordHide0">
            <div class="recordMain recordMain0" id="recordMain">
                <ul>
                <?php
                foreach ($token as $year => $monthdata) {
                    ?>
                    <?php
                    foreach ($monthdata as $month => $log) {
                        ?>
                        <li>
                        <div class="recordTitle">
                            <h4><?= $month ."  ". $year ?></h4>
                        </div>
                     </li>
                        <?php foreach ($log as $item) {
                            ?>
                            <li>
                            <div class="recordList">
                                <a href="javascript:void(0)">
                                    <div class="left time_left">
                                        <h4>+<?= intval($item['BP']) ?></h4>
                                    </div>
                                    <div class="left order_right">
                                        <div class="orderMain">
                                            <div class="round"><img src="/public/images/html5icon.png"></div>

                                            <p><?= date('Y-m-d', strtotime($item['TXDATE'])) ?></p>
                                            <h4><?php if ($item['MEMTYPE'] == 'WI') {
                                                    echo Yii::t('app', '活动赚积分');
                                                } else {
                                                    echo Yii::t('app', '消费赚积分');
                                                } ?></h4>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </a>
                            </div>
                        </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>

                    <?php
                }
                ?>

                </ul>
            </div>
        </div>
       <div class="recordHide recordHide1">
            <div class="recordMain recordMain1" id="recordMain1">
                <ul>
                <?php
                foreach ($spend as $year => $monthdata) {
                    ?>


                    <?php
                    foreach ($monthdata as $month => $log) {
                        ?>
                        <li>
                        <div class="recordTitle">
                            <h4><?= $month ."  ". $year ?></h4>
                        </div>
                    </li>
                        <?php foreach ($log as $item) {
                            ?>
                            <li>
                            <div class="recordList">
                                <a href="javascript:void(0)">
                                    <div class="left time_left">
                                        <h4><?= intval($item['BP']) ?></h4>
                                    </div>
                                    <div class="left order_right">
                                        <div class="orderMain">
                                            <div class="round"><img src="/public/images/html5icon.png"></div>

                                            <p><?= date('Y-m-d', strtotime($item['TXDATE'])) ?></p>
                                            <h4><?= Yii::t('app', '消费使用积分') ?></h4>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </a>
                            </div>
                        </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>

                    <?php
                }
                ?>
            </ul>
            </div>
        </div>
        <div class="record_top"></div>
    </div>
    <div class="singUp-bt">
        <img src="/public/images/bottom2.png">
    </div>
</div>
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
    var myScroll0,myScroll1,maH,index=0;
    function touchMove(){
        var $recordMain=$('.recordMain'+index);
        var $recordTitle=$recordMain.find('.recordTitle');
        if($recordTitle.length<1){return false;}
        $recordTitle.each(function(){
            var t=$(this).offset().top;
            var zj=maH*0.8;
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
            if(t>90&&t<250){
                $(this).addClass('roundAct');
            }else{
                $(this).removeClass('roundAct');
            }
        });
        $recordMain.find('.round:first').addClass('roundAct');
    }
    $(document).on('click','.itokenBox .left',function(){
        $('.itokenBox .left').removeClass('active');
        $(this).addClass('active');
        index=$(this).index();
        var $recordHide=$('.itokenList').find('.recordHide');
        $recordHide.hide();
        $recordHide.eq(index).show();
        myScroll1 = new IScroll('.recordHide'+index, {probeType:5,mouseWheel: true });
        myScroll1.on('scroll', touchMove);
        myScroll1.on('scrollEnd', touchMove);
        touchMove();
    });
    Pace.on('hide',function(){
        var bh=document.documentElement.clientHeight;
        var viph=Number($('.singUp-bt').height())+Number($('.itokenMain').height())+Number($('.itokenBox').height())+30;
        var $mainBg=$('.recordHide');
        maH=bh-viph;
        $mainBg.css({'height':maH+'px'});
        $('.recordMain').css({'min-height':(maH+20)+'px'});
        index=0;
        myScroll0 = new IScroll('.recordHide0', { probeType:5, mouseWheel: true});
        myScroll0.on('scroll', touchMove);
        myScroll0.on('scrollEnd', touchMove);
    });
    document.addEventListener('touchmove', function (e) { e.preventDefault(); }, isPassive() ? {
        capture: false,
        passive: false
    } : false);


</script>