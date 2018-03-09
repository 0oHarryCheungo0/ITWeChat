<?php use yii\helpers\Url; ?>
<style type="text/css">
  .am-form-label{
    text-align: left!important;
  }

    input, select, textarea {
    height: 38px;
    line-height: 38px;
    line-height: 36px\9;
    border: 1px solid #e6e6e6;
    background-color: #fff;
    border-radius: 2px;
        outline: 0;
    font-size:14px!important;
}
.am-form-label {
    float: left;
    display: block;
    padding: 9px 15px;
    width: 100px;
    font-weight: 400;
    text-align: right;
}
</style>

<fieldset class="layui-elem-field">
  <legend>新增-品牌</legend>
  <div class="layui-field-box">


<form class="am-form am-form-horizontal" style='height:1700px'>
 <fieldset>
   <input type="hidden" name="id" value="<?= $data->id; ?>" >
    <div class="am-form-group">
      <label for="doc-select-1" class="am-u-sm-2 am-form-label">选择品牌</label>
      <div class="am-u-sm-5 am-u-end">
      <select name="p_id"  name="p_id">
      <option value=''>请选择</option>
      <?php foreach ($parent as $k => $v): ?>
      <option value='<?= $v->id; ?>' <?php if (isset($data)) {if ($data->p_id == $v->id) {?>selected  <?php }}?>  ><?php echo $v->name; ?></option>
      <?php endforeach; ?>
      </select>
      </div>
      <span class="am-form-caret"></span>
    </div>
    <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">品牌名称</label>
       <div  class="am-u-sm-5 am-u-end" >
      <input type="text" class="" name="brand_name"  placeholder="品牌名称" value="<?= $data->brand_name; ?>">
      </div>
    </div>
     <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">标识符</label>
      <div  class="am-u-sm-5 am-u-end" >
      <input type="text" class="" name="identify"  placeholder="标识符" value="<?= $data->identify; ?>">
      </div>
    </div>
     <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label" >Appid</label>
       <div  class="am-u-sm-7 am-u-end" >
      <input type="text" class="" name="appid"  placeholder="微信appid" value="<?= $data->appid; ?>">
      </div>
    </div>
     <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">Appsecret</label>
       <div  class="am-u-sm-7 am-u-end" >
      <input type="text" class="" name="appsecret"  placeholder="微信secret" value="<?= $data->appsecret; ?>">
      </div>
    </div>
     <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">Token</label>
       <div  class="am-u-sm-7 am-u-end" >
      <input type="text" class="" name="token"  placeholder="微信token" value="<?= $data->token; ?>">
      </div>
    </div>
    <div class="am-form-group">
    <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">会员等级</label>
    <div class="am-u-sm-3 am-u-end" id="addmember">
    <input type="button" class="layui-btn" value='添加等级'>
    </div>
    </div>
    <div id="rank">
     <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label">会员等级</label>
              <div class="am-u"  > 
              <div class="am-u-sm-3">
              <input type="text" name="">
              </div>
              <div class="am-u-sm-1">
              －
              </div>
              <div class="am-u-sm-3">
              <input type="text" name="">
              </div>
              <div class="am-u-sm-3 am-u-end">
              删除
              </div>
              </div>
    </div>
    <div class="am-form-group">
      <label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label"></label>
          <div class="am-u"  > 
            <div class="am-u-sm-3">
              <input type="text" name="">
            </div>
            <div class="am-u-sm-1">
              －
            </div>
            <div class="am-u-sm-3">
              <input type="text" name="">
            </div>
            <div class="am-u-sm-3 am-u-end">
              删除
            </div>
          </div>
    </div>
    </div>
    <div class="am-u-sm-10 am-u-sm-offset-2">
	   <p><input type="button"  class="am-btn am-btn-default" value='提交' onclick="sub()"></p>
	  </div>
  </div>
 </fieldset>
</form>
</div>
</fieldset>
<script type="text/javascript">
      $(function() {
       <?php if (empty($data->rank)):?>
            var array=[
                {"rank":"1","name":""},
            ];
            <?php else: ?>
            var array = JSON.parse('<?=$data->rank?>');
            <?php endif; ?>
            var html='';
      if(array.length>0){
        for(var i=0;i<array.length;i++){
          html += '<div class="am-form-group"><label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label"></label><div class="am-u"><div class="am-u-sm-3">'+
            '<input class="input1" type="text" value="'+array[i].rank+'"/>'+'</div>'+'<div class="am-u-sm-1">'+'—'+'</div>'+'<div class="am-u-sm-3">'+'<input type="text" class="input2" value="'+array[i].name+'"/>'+'</div>'+'<div class="am-u-sm-3 am-u-end delete">'+
            '删除'+'</div></div></div>'; 
        }
        $('#rank').html(html);   
      }  
      $('#addmember').click(function(){
        var html="";
        html += '<div class="am-form-group"><label for="doc-ipt-3-1" class="am-u-sm-2 am-form-label"></label><div class="am-u"><div class="am-u-sm-3">'+
            '<input class="input1" type="text" />'+'</div>'+'<div class="am-u-sm-1">'+'—'+'</div>'+'<div class="am-u-sm-3">'+'<input type="text" class="input2" />'+'</div>'+'<div class="am-u-sm-3 am-u-end delete">'+
            '删除'+'</div></div></div>'; 
        $('#rank').append(html); 
      });
      $(document).on("click",".delete",function(){
        $(this).parent().parent().remove();
        console.log($(this));
      });
      $(document).on('click','button',function(){
        var array=[];
        $('#rank div .am-u').each(function(){
          //定义一个json
          var json={}
          var v1=$(this).find('.input1').val();
          var v2=$(this).find('.input2').val();
          json.rank=v1;
          json.name=v2;
          array.push(json);
        })
        console.log(array);
      });
    });

    function sub(){
        var array=[];
        $('#rank div .am-u').each(function(){
            //定义一个json
            var json={}
            var v1=$(this).find('.input1').val();
            var v2=$(this).find('.input2').val();
            json.rank=v1;
            json.name=v2;
            console.log($('.ul li'));
            array.push(json);
        })
        $.post('<?= Url::toRoute('brand/add')?>',
            {data:decodeURIComponent($("form").serialize(), true),array:array},
            function(data){
                if(data.code == 200){
                    layer.msg('成功');
                    location.href="<?= Url::toRoute('brand/list')?>";
                }else{
                    layer.msg(data.msg);
                }
                return false;
            });
    }

 </script>
