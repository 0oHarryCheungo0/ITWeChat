<form class="layui-form" action="">

  <div class="layui-form-item">
    <label class="layui-form-label">店铺</label>
    <div class="layui-input-block">

    <?php foreach ($store as $k => $v) {?>
      <input type="checkbox"  name="like[write]" title="<?php echo $v->store_name; ?>">
    <?php }?>

    </div>
  </div>
   <div class="layui-form-item">
    <label class="layui-form-label">会员</label>
    <div class="layui-input-block">
    <?php foreach ($store as $k => $v) {?>
      <input type="checkbox"  name="like[write]" title="<?php echo $v->store_name; ?>">
    <?php }?>
    </div>
  </div>

  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="formDemo">确定</button>

    </div>
  </div>
</form>

<script>
//Demo
layui.use('form', function(){
  var form = layui.form();

  //监听提交
  form.on('submit(formDemo)', function(data){
    layer.msg(JSON.stringify(data.field));
    return false;
  });
});
</script>
