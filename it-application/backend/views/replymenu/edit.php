<style>
.pic {
    width: 150px;
    height: 150px;
}
</style>
<ol class="am-breadcrumb">
  <li><a href="#">积分</a></li>
  <li class="am-active">积分配置</li>
</ol>
<div class="am-panel am-panel-default ">
  <div class="am-panel-hd">新增关键字</div>
<div class="am-panel-bd">
<div style="background: #fff;padding: 15px;margin-bottom: 40px;">
<div class="layui-tab layui-tab-card" style="height:1700px;">
<form class="layui-form layui-box" action="<?php echo \yii::$app->urlManager->createUrl(['replymenu/keywordedit'])?>" method="post" enctype='multipart/form-data'>
  <div class="layui-form-item">
    <label class="layui-form-label">关键词</label>
    <div class="layui-input-block">
      <input type="text" name="keyword" value="<?php echo $data['keyword']?>" required  lay-verify="required"  placeholder="请输入关键词" autocomplete="off" class="layui-input">
       <input type="hidden" name="id" value="<?php echo $data['id']?>"  class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">文章标题</label>
    <div class="layui-input-block">
      <input type="text" name="title"   value="<?php echo $data['title']?>" lay-verify="" placeholder="请输入标题" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">文章描述</label>
    <div class="layui-input-block">
     <textarea name="description" value="" placeholder="请输入内容" required class="layui-textarea"><?php echo $data['description']?></textarea>     
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">文章链接</label>
    <div class="layui-input-block">
      <input type="text" name="url"  value="<?php echo $data['url']?>" lay-verify="" placeholder="请输入链接" autocomplete="off" class="layui-input">
    </div>
  </div>
    <div class="layui-form-item tele">
    <label class="layui-form-label">文章排序</label>
    <div class="layui-input-block">
      <input type="text" name="sort"  value="<?php echo $data['sort']?>" lay-verify="number" placeholder="排序权值,值越小 ,优先级越高" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">上传图片</label>
  
      <input type="file" name="UploadForm[pic]" >
     <img class="pic" src="<?php echo $data['image']?>" />
   
  </div>
<div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="*" flag=1>立即提交</button>
    </div>
  </div>
   
</form>


</div>
</div>
</div>
<script src="backend/public/js/jquery.nailthumb.1.1.min.js"></script>
<script>
           Query(document).ready(function() {
        	    jQuery('.nailthumb-container').nailthumb();
        	});

</script>



