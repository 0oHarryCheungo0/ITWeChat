<?php use yii\helpers\Url; ?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.css')?>">
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.min.css')?>">
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.min.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.min.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.min.js')?>"></script>
<div class="am-panel am-panel-default" >
<table class="am-table am-table-bordered am-table-striped am-table-compact">
	<tr>
    	<th style="width:28%">品牌</th>
    	<td class="am-text-middle"><?= $detail->brand_name; ?></td>
    </tr>
    <tr>
    	<th >标识符</th>
    	<td class="am-text-middle"><?= $detail->identify; ?></td>
    </tr>
    <tr>
    	<th>APPID</th>
    	<td class="am-text-middle"><?= $detail->appid; ?></td>
    </tr>
     <tr>
    	<th>APPSECRET</th>
    	<td class="am-text-middle"><?= $detail->appsecret; ?></td>
    </tr>
     <tr>
    	<th>TOKEN</th>
    	<td class="am-text-middle"><?= $detail->token; ?></td>
    </tr>
</table>
</div>
