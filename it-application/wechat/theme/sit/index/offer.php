<?php
use yii\helpers\Url;
?>
<div class="bodyMain">
<div class="mainCss">
    <img src="<?=Url::to('@web/public/images/invoice_img.jpg')?>">
</div>
<div class="invoiceMain">
    <div class="invoiceBox">
        <div class="invoice-t">
            <img src="<?=Url::to('@web/public/images/invoice_t.png')?>">
        </div>
    </div>
</div>
<div class="offerMain">
    <ul class="news_nav offer-nav">
        <li>
            <a href="">
                <span>●</span><span>常规优惠</span>
            </a>
        </li>
        <li class="active">
            <a href="">
                <span>●</span><span>限时优惠</span>
            </a>
        </li>
    </ul>
    <div class="clear"></div>
    <div class="news_main">
        <ul class="news_info offer-info">
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="singUp-bt">
    <img src="<?=Url::to('@web/public/images/bottom2.png')?>">
</div>
</div>
<script>
    $(document).ready(function () {
    var bh=document.documentElement.clientHeight;
    var viph=Number($('.singUp-bt').height())+10+Number($('.mainCss').height())+40;
    var $mainBg=$('.news_main');
    $mainBg.css({'height':(bh-viph)+'px'});
    });
</script>
