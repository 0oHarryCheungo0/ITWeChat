<?php use yii\helpers\Html; ?>
<?php use yii\helpers\Url; ?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.css')?>">
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.min.css')?>">
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.min.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.min.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.js')?>"></script>
<script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.min.js')?>"></script>

<ol class="am-breadcrumb">
  <li><a href="#">员工</a></li>
  <li class="am-active">员工二维码列表</li>
</ol>
<div class="am-panel am-panel-default">
    <div class="am-panel-bd">
    	<ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-thumbnails">
		<?php foreach ($list as $k=>$v): ?>  
		    <li> 
		    	<strong style="font-size: 45px"><?= Html::encode("{$v->staff_name}") ?>--<?= Html::encode("{$v->staff_code}") ?> </strong>
		    	<br/>
		    	<img class="am-thumbnail" src=" <?= $v->qrcode ?>" />    
		    </li>  
		<?php endforeach; ?> 
		</ul>
    </div>
</div>
