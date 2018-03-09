<?php use yii\helpers\Url;?>
<link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>" media="all">
<script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
<script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>
<form class="layui-form" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">输入框</label>
    <div class="layui-input-inline">
        <input type="text" name="username"  value=" <?php echo Yii::$app->user->identity->username;?>" class="layui-input" disabled>
       <input type="hidden" id="id"  name="id" value="<?php echo Yii::$app->user->identity->id;?>" h>
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">密码框</label>
    <div class="layui-input-inline">
      <input type="password" id='password1'  placeholder="请输入密码" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">密码</div>
  </div>
 <div class="layui-form-item">
    <label class="layui-form-label">重复密码</label>
    <div class="layui-input-inline">
      <input type="password" id='password2'  placeholder="请输入重复密码" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">重复密码</div>
  </div>
  <div class="layui-form-item">
    <div class="layui-input-block">
      <p class="layui-btn" onclick="change()" >立即提交</p>
    </div>
  </div>
</form>
 
<script>
  layui.use('layer', function(){
  var layer = layui.layer;

});  

function change(){
  var id = $('#id').val();
  var password1 = $('#password1').val();
  console.log(password1);
  var password2 = $('#password2').val();
  if (password1== ''||password2 == ''){
    layer.msg('密码不能为空',{icon:2});
    return false;
  }
  $.post('<?=Url::to("resetpassword")?>',{id:id,password1:password1,password2:password2},function(data){
    if (data.code == 200){
      layer.msg('修改成功');
      setTimeout('location="<?=Url::toRoute("user/list")?>"',1000);
    }else{
      layer.msg('修改失败&nbsp'+data.msg);
    }
  })
}
$('.brand').show();
    $('#cardone').attr('class','active');


</script>
      