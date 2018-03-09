<?php use yii\helpers\Url; ?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/amazeui/dist/css/amazeui.min.css') ?>">
 <link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>" media="all">
    <script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
 <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.min.js') ?>"></script>
    <!--    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.js') ?>"></script> -->
    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.ie8polyfill.min.js') ?>"></script>
    <!--  <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.js') ?>"></script> -->
    <script src="<?= Url::to('@web/backend/amazeui/dist/js/amazeui.widgets.helper.min.js') ?>"></script>
     <script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>

<form class="am-form">
  <fieldset>
    <input type="hidden" name="" id="id" value="<?=$id?>">
    <div class="am-form-group">
      <label for="doc-select-1">选择店员</label>
      <select id="doc-select-1">
        <?php foreach ($staff as $k=>$v): ?>
        <option value="<?=$v->id?>"><?=$v->staff_name; ?></option>
      <?php endforeach; ?>
      </select>
      <span class="am-form-caret"></span>
    </div>
    <p onclick="tran()" class="layui-btn">提交</p>
  </fieldset>
</form>
<script type="text/javascript">
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form()
        ,layer = layui.layer
        ,layedit = layui.layedit
        ,laydate = layui.laydate;
        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor');
    });
  function tran(){
    $.post('<?=Url::toRoute("fans/update")?>',{id:$('#id').val(),staff_id:$('#doc-select-1').val()},function(data){
      if (data.code == 200) {
        layer.msg('成功');
        setTimeout('parent.location.reload()', 1000); 
      }
    })
  }
</script>