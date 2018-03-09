<?php use yii\helpers\Url; ?>

<link rel="stylesheet" href="<?= Url::to('@web/backend/public/layui/css/layui.css') ?>">
<script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
<script src="<?= Url::to('@web/backend/public/layui/layui.js') ?>"></script>
    <div style="background: #fff;padding: 15px;margin-bottom: 40px;">
        <form class="layui-form layui-box" action="index.php?r=staff/transfer" method="post">
         <div class="layui-form-item">
            <label class="layui-form-label">目标店员</label>
            <div class="layui-input-block">
                <select name="store_id" id="store_id" onchange='aa()' lay-filter="aihao" lay-search>
                    <option value="">请选择</option>
                    <?php foreach ($store as $k => $v):?>
                     <option value="<?= $v->id; ?>"><?= $v->staff_name; ?>(<?= $v->staff_code; ?>)</option>
                     <?php endforeach;?>
                 </select>
             </div>
         </div>
         <input type="hidden" name="id" id="id" value="<?= $id; ?>">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input type='button' onclick="transfer()" class="layui-btn" lay-submit="" lay-filter="demo1" value="提交">
            </div>
        </div>
    </form>
</div>

<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form()
        ,layer = layui.layer
        ,layedit = layui.layedit
        ,laydate = layui.laydate;
        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor');
    });
    function transfer(){
        $.post("<?=Url::toRoute('staff/transfer-all')?>",
            {id:$('#id').val(),store_id:$('#store_id').val(),extra:''},
            function(data){
                if(data.code == 200){
                    layer.msg('转移成功');
                    setTimeout('parent.location.reload()', 1000); 
                }else{
                    layer.msg(data.msg);
                }
            });
    }
    $('#store').show();
    $('#store').find('li').attr('class','active');
</script>



