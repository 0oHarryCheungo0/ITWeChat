<form class="layui-form" action="index.php?r=templates/store" method='post'>

  <div class="layui-form-item">
    <label class="layui-form-label">店铺</label>
    <div class="layui-input-block">
    <input type='hidden' name="type" value="1" >
    <?php foreach ($store as $k => $v) {
    ?>
      <input lay-skin="primary" type="checkbox"  name="store[<?php echo $v->id; ?>]"   title="<?php echo $v->store_name; ?>" <?php if (!empty($ret)) {

        foreach ($ret as $k1 => $v1) {
            if ($v1 == $v->id) {
                ?>checked

         <?php

            }
        }

    }?>  >
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
