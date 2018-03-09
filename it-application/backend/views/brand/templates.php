<style type="text/css">

</style>
<ol class="am-breadcrumb">
  <li><a href="#">模板</a></li>
  <li class="am-active"><?= $title; ?></li>
  <button style="float:right" class='layui-btn' onclick='selectT()'><i class='layui-icon'>&#x1005;</i>选取模板</button>
</ol>
<div style="background: #fff;padding: 15px;margin-bottom: 40px;">

<form class="layui-form layui-box" action="" >
    <input type="hidden" name="id" id="type" value='<?php echo $form->id; ?>'>
        <input type="hidden" name="type" id="id" value='<?php echo $type; ?>'>
       <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input type="text" name="title" id='title' lay-verify="title" autocomplete="off" placeholder="" class="layui-input" value="<?php echo $form->title; ?>" >
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-block">
             <script id="container" name="contents" type="text/plain">
                <?php echo $form->contents; ?>
            </script>
        </div>
    </div> 
    <!-- 配置文件 -->
    <script type="text/javascript" src="backend/utf8-php/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="backend/utf8-php/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
    </script>

    <input class="layui-btn" type="button" value="提交" onclick="sub()">
</form>
<hr>
备注:<?php echo $ps; ?>


</div>

</div>
<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form()
                ,layer = layui.layer
                ,layedit = layui.layedit
                ,laydate = layui.laydate;
        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor');

        //自定义验证规则
        form.verify({
            title: function(value){
                if(value.length < 5){
                    return '标题至少得5个字符啊';
                }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,content: function(value){
                layedit.sync(editIndex);
            }
        });



        //监听提交
        form.on('submit(demo1)', function(data){
            layer.alert(JSON.stringify(data.field), {
                title: '最终的提交信息'
            })
            return false;
        });


    });

     function sub(){
       var html = ue.getContent();
       var id = $('#id').val();
       var title = $('#title').val();

        $.post('index.php?r=brand/update',
            {id:id,title:title,contents:html},
            function(data){
                if(data.code == 200){
                    layer.msg('成功');
                }else{
                    layer.msg('创建成功');
                }
                return false;
            });
    }

    function selectT(){
        layer.open({
          type: 1, 
          title:'模版',
          area: ['150px', '300px'],
          content: '<br/><ul style="text-align:center"><li><a style="text-decoration:none" href="index.php?r=information/templates&type=1" class="layui-btn">会员权益模版</button></li><li><a href="index.php?r=information/templates&type=2" class="layui-btn">会员降级模版</a></li><li><a href="index.php?r=information/templates&type=3" class="layui-btn">积分到期模版</a></li><li><a href="index.php?r=information/templates&type=4" class="layui-btn">会员等级模版</a></li><li><a href="index.php?r=information/templates&type=5" class="layui-btn">会员生日月模版</a></li></ul>' //这里content是一个普通的String
        });
    }
    $('.brand').show();
     $('#cardtwo').attr('class','active');
</script>



