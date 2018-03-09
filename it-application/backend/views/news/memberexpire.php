<?php use yii\helpers\Url;
use yii\helpers\Html; ?>

<style type="text/css">
    .layui-form-item .layui-input-inline {
    float: left;
    width: 650px;
    margin-right: 10px;
}
</style>

<fieldset class="layui-elem-field">
  <legend>资讯-会员-<?=$type_children==1?'一月到期':'三月到期'?>模版</legend>
  <div class="layui-field-box">
  <li style='float:right'>
  <a style="text-decoration:none" class="layui-btn layui-btn-small layui-btn-normal" href="<?=Url::toRoute(['news/memberexpire','type_children'=>1])?>">一个月到期</a>
  <a style="text-decoration:none" class='layui-btn layui-btn-small' href="<?=Url::toRoute(['news/memberexpire','type_children'=>2])?>">三个月到期</a>
</li>

<div style="background: #fff;padding: 15px;margin-bottom: 40px;height:1800px">
<form class="layui-form layui-box" action="" >
    <input type="hidden" name="id" id="id" value=''>
    <input type="hidden" name="type" id='type' value='<?=$type?>'>
    <input type="hidden" name="type_children" id="type_children" value='<?=$type_children;?>'>
        <div class="layui-form-item">
        <label class="layui-form-label">会员等级</label>
        <div class="layui-input-inline">
            <select name="p_id" id='p_id' lay-filter="aihao">
            <?php foreach ($brand as $k => $v): ?>
            <option value="<?=$v['rank'];?>"><?=$v['name']?></option>
            <?php endforeach; ?>
            </select>
        </div>
    </div>
       <div class="layui-form-item">
        <?= Html::tag('label', '标题', ['class' => 'layui-form-label']) ?>
        <div class="layui-input-inline">
            <input type="text" name="title" id='title' lay-verify="title" autocomplete="off" placeholder="" class="layui-input"  >
        </div>
    </div>

    <div class="layui-form-item">
        <?= Html::tag('label', '標題', ['class' => 'layui-form-label']) ?>
        <div class="layui-input-inline">
            <input type="text" name="hk_title" id='hk_title' lay-verify="title" autocomplete="off" placeholder="" class="layui-input"  >
        </div>
    </div>
    <br>
    <br>
    <br>
       <div class="layui-form-item">
        <label class="layui-form-label">内容</label>
        <div class="layui-input-inline">
             <script id="container" name="contents" type="text/plain">   
            </script>
        </div>

        <div class="layui-form-item">
        <label class="layui-form-label">內容(繁体字)</label>
        <div class="layui-input-inline">
             <script id="hk_container" name="hk_contents" type="text/plain">   
            </script>
        </div>
 
      <script type="text/javascript" src="<?=Url::to('@web/backend/utf8-php/ueditor.config.js',true);?>"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="<?=Url::to('@web/backend/utf8-php/ueditor.all.js',true);?>"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
           var url  = '<?=Url::to(["helper/editor"],true)?>';
         var ue = UE.getEditor('container',{serverUrl:url,autoFloatEnabled:false});
         var hk = UE.getEditor('hk_container',{serverUrl:url,autoFloatEnabled:false});
    </script>
    </div> 
    <p class="layui-btn"  onclick="sub()">提交</p>
    <p class="layui-btn"  onclick="pub()">发布</p>
</form>
<hr>
</div>

</div>

  </div>
</fieldset>
<script>

    layui.use(['form', 'layedit', 'laydate'], function(){
           var  form     = layui.form()
                ,layer   = layui.layer
                ,layedit = layui.layedit
                ,laydate = layui.laydate;
                  layedit = layui.layedit;
        var editIndex = layedit.build('contain'); 

    var __init = $('#p_id').val();
    var type = $('#type').val();
    form.on('select(aihao)',function(data){
        __init =data.value;
        $.post('<?=Url::toRoute("news/getexpire")?>',
        {__init:__init,type:type,type_children:<?=$type_children?>},
        function(data){
            if (data.code == 200) {
                $('#id').val(data.data.id);
                $('#title').val(data.data.title);
                $('#hk_title').val(data.data.hk_title);
                hk.setContent(data.data.hk_content);
                ue.setContent(data.data.content);
            }else{
                 $('#id').val('');
                $('#title').val('');
                $('#hk_title').val('');
                hk.setContent('');
                ue.setContent('');
            }
        })
    });
    $.post('<?=Url::toRoute("news/getexpire")?>',
        {__init:__init,type:type,type_children:<?=$type_children?>},
        function(data){
            if (data.code == 200) {
                $('#id').val(data.data.id);
                $('#title').val(data.data.title);
                 $('#hk_title').val(data.data.hk_title);
                hk.setContent(data.data.hk_content);
                ue.setContent(data.data.content);
            }else{
                 $('#id').val('');
                 $('#title').val('');
                     $('#hk_title').val('');
                hk.setContent('');
                 ue.setContent('');
            }
        })
    });

    function sub(){
           var html = ue.getContent();
           var hk_content = hk.getContent();
           var hk_title = $('#hk_title').val();
           var id    = $('#id').val();
           var type = $('#type').val();
           var title = $('#title').val();
           var p_id  = $('#p_id').val();
           var type_children = $('#type_children').val();

        $.post("<?=Url::toRoute('news/updateexpire')?>",
            {id:id,type:type,title:title,hk_title:hk_title,hk_content:hk_content,content:html,rank:p_id,type_children:type_children},
            function(data){
                if(data.code == 200){
                   if (data.msg != ''){
                        $('#id').val(data.msg);
                    }
                    layer.msg('成功');
                }else{
                    layer.msg('创建成功');
                }
                return false;
            });
    }

    function pub(){
       var id    = $('#id').val();
       var type = '<?=$type_children?>';
       if (id == ''){
        layer.msg('请编辑模版后再发布');
       }else{
        $.post('<?=Url::toRoute("news/pubexpire")?>',{id:id,type:type},function(data){
          if (data.code == 200){
            layer.msg('发布成功');
          }
        })
       }

    }

       $('.news').show();
        $('#expire').attr('class','active');
</script>
<script>
      
       
</script>



