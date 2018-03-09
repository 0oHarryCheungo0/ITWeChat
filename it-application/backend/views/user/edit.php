<?php use yii\helpers\Url; ?>
<style type="text/css">
    .layui-form-item .layui-input-inline {
        float: left;
        width: 650px;
        margin-right: 10px;
    }
</style>
<fieldset class="layui-elem-field">
<legend>编辑用户</legend>
<div class="layui-field-box">
 
<form class="layui-form layui-box" >
      <div class="layui-form-item">
      <input type="hidden" name="id" value="<?php echo $data->id; ?>">
            <label class="layui-form-label">品牌</label>
            <div class="layui-input-inline">
                <select name="brand_id" lay-filter="aihao">
                    <option value="">请选择</option>
                   <?php foreach ($brands as $k => $v): ?>
    		      <option value="<?= $v->id ?>"<?php if ($v->id == $data->brand_id): ?>selected<?php endif; ?>><?= $v->brand_name ?></option>
    			  <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限</label>
            <div class="layui-input-inline">
                <select name="auth_id" lay-filter="aihao">
                    <option value="">请选择</option>
                   <?php foreach ($auth as $k1 => $v1):?>
    		      <option value="<?= $v1->id; ?>" <?php if ($v1->id == $data->auth_id): ?>selected<?php endif; ?>  ><?= $v1->auth; ?></option>
    			  <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" name="username" lay-verify="title" autocomplete="off" placeholder="请输入管理员用户名" class="layui-input" value="<?php echo $data->username; ?>" readOnly="true" >
            </div>
        </div>

    	 <div class="layui-form-item">
                <div class="layui-input-inline">
                    <p class="layui-btn" onclick="sub()" >立即提交</p>
                </div>
         </div>
    </form>
  </div>
</fieldset>
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
            $.post('<?= Url::toRoute('user/update')?>',
                {data:decodeURIComponent($("form").serialize(), true)},
                function(data){
                    if (data.code == 200) {
                        layer.msg('成功');
                         location.href='<?=Url::toRoute("user/list")?>';
                    }  else if (data.code ==302) {
                        layer.msg(data.msg);
                    }else{
                        layer.msg('失败');
                    }
                });
        }
        $('.brand').show();
        $('#cardone').attr('class','active');
    </script>
