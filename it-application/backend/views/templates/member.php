<form class="layui-form" action="index.php?r=templates/member" method="post">

  <div class="layui-form-item">
    <label class="layui-form-label">会员</label>
    <div class="layui-input-block">
    <input type='hidden' name="type" value="2" >

    <?php foreach ($store as $k => $v) { ?>
      <input type="checkbox"  name="member[<?php echo $v->id; ?>]" title="<?php echo $v->NickName; ?>"  
      <?php if (!empty($ret)) { foreach ($ret as $k1 => $v1) { if ($v1 == $v->id) { ?>checked<?php }}} ?> >
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
    return true;
  });
});
</script>
