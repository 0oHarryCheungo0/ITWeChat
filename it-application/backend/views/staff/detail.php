<?php use yii\helpers\Url; ?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>" media="all">
<script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>
<style type="text/css">
  html {
    
   background-color: #f2f2f2;
}
</style>
<table class="layui-table" lay-skin="row">
  <colgroup>
    <col width="150">
    <col width="200">
    <col>
  </colgroup>
  <thead>
     <tr>
      <td>员工姓名</td>
      <td><?= $detail->staff_name; ?></td>

    </tr>
    <tr>
      <td>员工编号</td>
      <td><?= $detail->staff_code; ?></td>
    </tr>
     <tr>
      <td>员工二维码</td>
      <td><img style="width:100px;height:100px;" src=" <?=$detail->qrcode; ?>" /> </td>
    </tr>
  </thead>
</table>