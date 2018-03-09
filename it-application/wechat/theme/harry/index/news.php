<?php
use yii\helpers\Url;
?>
<div class="bodyMain">
    <ul class="news_nav">
        <li class="active">
            <a href="<?=$urls['news']?>">
                <span>●</span><span><?= Yii::t('app', '会员资讯') ?></span>
            </a>
        </li>
        <li>
            <a href="<?=$urls['point-news']?>">
                <span>●</span><span><?= Yii::t('app', '积分资讯') ?></span>
            </a>
        </li>
    </ul>
    <div class="clear"></div>
    <div class="news_main">
        <ul class="news_info">
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
            <li>
                <a href="new-info.html">
                    <div class="news_time">2017.02.05 11:23</div>
                    <div class="news_html">恭喜成为i.t金卡会员。</div>
                </a>
            </li>
        </ul>
    </div>
    <div class="singUp-bt">
        <img src="/public/images/bottom2.png">
    </div>
</div>
<script src="/public/js/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        var bh=document.documentElement.clientHeight;
        var viph=Number($('.singUp-bt').height())+10+45+40;
        var $mainBg=$('.news_main');
        $mainBg.css({'height':(bh-viph)+'px'});
    });
</script>
