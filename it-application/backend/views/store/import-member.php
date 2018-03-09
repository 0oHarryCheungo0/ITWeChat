<?php use  yii\helpers\Url; ?>
<fieldset class="layui-elem-field">
  <legend>店铺上传</legend>
  <a href="<?= Url::to('@web/backend/public/店铺导入模版.xlsx',true) ?>" class="layui-btn layui-btn-mini" style="float:right;margin-right:10px;">下载模版</a>
  
<form class="layui-form" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">上传文件</label>
    <div class="layui-input-block">
      <input type="file" name="file" lay-type='file' class="layui-upload-file" lay-ext="xlsx">
    </div>
  </div>
  
  <div class="layui-form-item">
    
  </div>
</form>
 
<script>
//Demo
layui.use('form', function(){
  var form = layui.form();
  //监听提交
});
</script>

</fieldset>
<script type="text/javascript">
	
layui.use('upload', function(){
  layui.upload({
  url: '<?=Url::toRoute("store/import-member")?>'
  ,success: function(res){
    console.log(res); //上传成功返回值，必须为json格式
    if (res.code == 200){
    	layer.msg('店铺导入成功',{icon:1});
    }else{
    	layer.msg(res.msg,{icon:2});
    }
  }
});
});
      

</script>
