<ol class="am-breadcrumb">
  <li><a href="#">模板消息</a></li>
  <li class="am-active">模板配置</li>
</ol>
<div class="am-panel am-panel-default ">
  <div class="am-panel-hd">模板配置</div>
<div class="am-panel-bd">
<div style="background: #fff;padding: 15px;margin-bottom: 40px;">
<form class="layui-form layui-box"  method="post" enctype='multipart/form-data'>
  
  <!--  -->
    
  <!--  -->
  <div class="layui-form-item tele" >
    <label class="layui-form-label">文章标题</label>
    <div class="layui-input-block">
      <input type="text" name="title"   lay-verify="" placeholder="请输入标题" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item" >
    <label class="layui-form-label">订单编号</label>
    <div class="layui-input-block">
      <input type="text" name="orderid"  lay-verify="" placeholder="请输入描述" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item tele">
    <label class="layui-form-label">订单状态</label>
    <div class="layui-input-block">
      <input type="text" name="orderstatus"  lay-verify="" placeholder="请输入链接" autocomplete="off" class="layui-input">
    </div>
  </div>
   <div class="layui-form-item tele">
    <label class="layui-form-label">文章内容</label>
    <div class="layui-input-block">
       <textarea name="content" placeholder="请输入内容" class="layui-textarea"></textarea>
    </div>
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
<script>
    $('.wxset').show();
    $('#templatenews').attr('class','active');
</script>



