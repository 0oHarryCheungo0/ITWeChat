<?php use yii\helpers\Url;?>
<form class="layui-form" action="">
  
  <div class="layui-form-item">
    <label class="layui-form-label">组别</label>
    <div class="layui-input-inline">
      	<input type="hidden" name="auth" value="<?=$id?>">
      	<input type="text" class="layui-input" name='name' value="<?=$name?>">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">菜单选择</label>
    <div class="layui-input-block">

      <input type="checkbox" name="1" lay-skin="primary" title="店铺" <?php foreach ($content as $k=>$v): ?>
    		<?php if ($v == 1): ?>checked<?php endif;?>
    		<?php endforeach; ?>>
      <input type="checkbox" name="2" lay-skin="primary" title="会员" <?php foreach ($content as $k=>$v): ?>
    		<?php if ($v == 2): ?>checked<?php endif;?>
    		<?php endforeach; ?>>
      <input type="checkbox" name="3" lay-skin="primary" title="系统配置" <?php foreach ($content as $k=>$v): ?>
    		<?php if ($v == 3): ?>checked<?php endif;?>
    		<?php endforeach; ?>>
      <input type="checkbox" name="4" lay-skin="primary" title="微信配置" <?php foreach ($content as $k=>$v): ?>
    		<?php if ($v == 4): ?>checked<?php endif;?>
    		<?php endforeach; ?>>
      <input type="checkbox" name="5" lay-skin="primary" title="专属优惠" <?php foreach ($content as $k=>$v): ?>
    		<?php if ($v == 5): ?>checked<?php endif;?>
    		<?php endforeach; ?>>
      <input type="checkbox" name="6" lay-skin="primary" title="最新资讯" <?php foreach ($content as $k=>$v): ?>
    		<?php if ($v == 6): ?>checked<?php endif;?>
    		<?php endforeach; ?>>
    </div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
     
    </div>
  </div>
</form>
 
<script>
//Demo
layui.use('form', function(){
  var form = layui.form();
  	
  //监听提交
  form.on('submit(formDemo)', function(data){
    //layer.msg(JSON.stringify(data.field));
    var data = JSON.stringify(data.field);
    $.post('<?=Url::toRoute("user/update-auth")?>',{data:data},function(data){
    	if (data.code == 200){
    		layer.msg('成功');
    		location.reload();   
    	}else{
    		layer.msg('失败');
    	}
    })
    return false;
  });
});
  $('.auth').show();
  $('#auth<?=$id?>').attr('class','active');
</script>