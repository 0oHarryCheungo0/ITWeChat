<?php use yii\helpers\Url;?>
<style type="text/css">
    .layui-form-item .layui-input-inline {
    float: left;
    width: 350px;
    margin-right: 10px;
}
</style>
<fieldset class="layui-elem-field">
  <legend>新增员工</legend>
  <div class="layui-field-box">
    <a class='layui-btn ' href='<?=Url::toRoute('staff/import')?>' style="float: right;text-decoration: none">员工导入</a>
    <form class="layui-form layui-box" >
   <div class="layui-form-item">
        <label class="layui-form-label">选择店铺</label>
        <input type="hidden" name="id" value="<?=$staff_id?>">
        <div class="layui-input-inline">
            <select name="store_id" lay-filter="aihao" id='st1' disabled>
                <option value="">请选择</option>
               <?php foreach ($store as $k => $v):?>
            <option value="<?= $v->id; ?>" <?php if(isset($store_id)) { if ($store_id == $v->id){ ?>selected<?php }} ?>><?= $v->store_name; ?></option>
            <?php endforeach;?>
            </select>
        </div>
    </div>
     <div class="layui-form-item">
        <label class="layui-form-label">员工姓名</label>
        <div class="layui-input-inline">
            <input type="text" name="staff_name" lay-verify="title" autocomplete="off" placeholder="请输入员工姓名" class="layui-input"  value="<?php if (isset($list)) {
    echo $list->staff_name;
}
?>">
        </div>
    </div>

     <div class="layui-form-item">
        <label class="layui-form-label">员工编号</label>
        <div class="layui-input-inline">
            <input type="text" name="staff_code" lay-verify="title" autocomplete="off" placeholder="请输入员工编号" class="layui-input"  value="<?php if (isset($list)) {
    echo $list->staff_code;
}?>" <?php if (isset($list)): ?>readonly<?php endif;?> >
        </div>
    </div>

     <div class="layui-form-item">
        <label class="layui-form-label">备注</label>
        <div class="layui-input-inline">
            <input type="text" name="extra" lay-verify="title" autocomplete="off" placeholder="请输入备注" class="layui-input"  value="<?php if (isset($list)) {
    echo $list->extra;
}
?>" >
        </div>
    </div>

     <div class="layui-form-item">
                <div class="layui-input-inline">
                    <p class="layui-btn" onclick="sub()">提交</p>
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

    });

    var __SUBURL__ = '<?=Url::toRoute("staff/update")?>';
    var __REURL__ = '<?=Url::toRoute('staff/list')?>?store_id=<?= $store_id ?>';

    function sub(){
        $("#st1").attr("disabled",false);
        $("#st2").attr("disabled",false);
        $.post(__SUBURL__,
            {data:decodeURIComponent($("form").serialize(), true)},
            function(data){
                if (data.code == 200) {
                    layer.msg('新增员工成功');
                    location.href=__REURL__;
                }else{
                    layer.msg(data.msg);
                }
            })
    }
    $('#store').show();
    $('#store').find('li').attr('class','active');
</script>



