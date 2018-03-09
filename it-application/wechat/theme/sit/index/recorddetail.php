<?php

use yii\helpers\Url;

?>
<div class="bodyMain">
    <div class="mainCss">
        <img src="/public/sit/images/invoice_img.jpg">
    </div>
    <div class="invoiceMain">
        <div class="invoiceBox">
            <div class="invoice-t">
                <img src="/public/sit/images/vip_c_26f.png">
                <div class="in-Info">
                    <h4>RECORD</h4>
                    <p>消费记录</p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <div class="invoiceInfo">
        <img src="/public/sit/images/invoice_txt.png" class="invo-txt">
        <div class="invoice-text mainCss">
            <img src="/public/sit/images/invoice_bg.png">
            <div class="invoice-body">
                <div class="vipPadd">
                    <div class="invo-memo">
                        Memo No.:<?= $detail['MEMONO'] ?>
                    </div>
                    <div class="invo-html">
                        <span><?php echo date('Y-m-d', strtotime($detail['TXDATE'])) ?></span>消费日期：
                    </div>
                    <div class="invo-html">
                        <span><?= $detail['QTY'] ?> 件</span>消费数量：
                    </div>
                    <div class="invo-html invo-prc">
                        <span>￥ <?= $detail['DTRAMT'] ?></span>消费金额：
                    </div>
                    <div class="clear"></div>
                    <div class="invo-addr">
                        <?= $detail['store'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="singUp-bt">
        <img src="/public/sit/images/bottom.png">
    </div>
</div>
