<ol class="am-breadcrumb">
  <li><a href="#">积分</a></li>
  <li class="am-active">积分配置</li>
</ol>
<div class="am-panel am-panel-default ">
  <div class="am-panel-hd">新增关键字</div>
<div class="am-panel-bd">
<div class="layui-tab layui-tab-card" style="height:1700px;">
<div style="background: #fff;padding: 15px;margin-bottom: 40px;">
<form class="layui-form layui-box" action="index.php?r=replymenu/index" method="post" enctype='multipart/form-data'>
  <div class="layui-form-item">
    <label class="layui-form-label">关键词</label>
    <div class="layui-input-block">
      <input type="text" name="keyword" required  lay-verify="required"  placeholder="请输入关键词" autocomplete="off" class="layui-input">
    </div>
  </div>
  <!--  -->
    <div class="layui-form-item">
    <label class="layui-form-label">添加类型</label>
    <div class="layui-input-block">
      <select name="type" lay-verify="required" lay-filter="type">
        <option value="0">图文</option>
        <option value="1">文本</option>
      </select>
    </div>
  </div>
  <!--  -->
  <div class="layui-form-item tele" >
    <label class="layui-form-label">文章标题</label>
    <div class="layui-input-block">
      <input type="text" name="title"   lay-verify="" placeholder="请输入标题" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item" >
    <label class="layui-form-label">文章描述</label>
    <div class="layui-input-block">
       <textarea name="description" placeholder="请输入内容" required class="layui-textarea"></textarea>
    </div>
  </div>
  <div class="layui-form-item tele">
    <label class="layui-form-label">文章链接</label>
    <div class="layui-input-block">
      <input type="text" name="url"  lay-verify="" placeholder="请输入链接" autocomplete="off" class="layui-input">
    </div>
  </div>
   <div class="layui-form-item tele">
    <label class="layui-form-label">文章排序</label>
    <div class="layui-input-block">
      <input type="text" name="sort"  value="8" lay-verify="member" placeholder="排序权值,值越小 ,优先级越高" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item tele">
    <label class="layui-form-label">上传图片</label>
  
      <input type="file" name="UploadForm[pic]" >
     
   
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
</div>
<script>
//验证关键字是否重复;
          layui.use('form', function(){
        	 var form = layui.form();
        	 var layer = layui.layer;
        	  form.on('submit(*)', function(data){
        		var value=data.field.keyword;
        		var flag=1;
				//当前容器的全部表单字段，名值对形式：{name: value}
				/*$.ajax({
					url:"<?php echo yii::$app->urlManager->createUrl("replymenu/validatekeword")?>",
					type:"post",
					dataType:"json",
					data:{"keyword":value},
					async:false,
					success:function(msg){
						if(msg.code==0){
						flag=0;
					}else{
						flag=1;
						layer.msg("关键字已经设置");
						}	
					}
					})

				if(flag==1){
					return false;
				}*/
				
            	});

        	  form.on('select(type)', function(data){
        		 
        		  console.log(data.value); //得到被选中的值
        		  	if(data.value==1){
            		  $(".tele").hide();
        		  	}
        		 	if(data.value==0){
        		 		$(".tele").show();
        		 	}
        		});      
    
        	 
        	});
          $('.wxset').show();
          $('#replymenu').attr('class','active');
    
</script>



