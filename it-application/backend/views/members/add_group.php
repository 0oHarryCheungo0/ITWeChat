<?php use yii\helpers\Url; ?>
<form class="layui-form" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">分组名称</label>
    <div class="layui-input-block">
      <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
    </div>
  </div>

  <div class="layui-form-item">
    <label class="layui-form-label">选择会员</label>
    <div class="layui-input-block" id="member">
    <input lay-skin="primary" type="checkbox" name="" title="写作"  checked>
    <input type="checkbox" name="" title="发呆" lay-skin="primary" > 
    <input type="checkbox" name="" title="禁用" lay-skin="primary" > 
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
  </div>
</form>
 
<script>
//Demo
layui.use('form', function(){
  var form = layui.form(); 
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});

var __URL = '<?=Url::toRoute('members/getmember')?>';
var html = '<input type="checkbox" name="" title="发呆" lay-skin="primary" >';
$.post(__URL,{brand_id:brand_id,page:page});
$("#member").append('');
</script>