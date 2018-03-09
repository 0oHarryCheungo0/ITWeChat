<?php use yii\helpers\Url;?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style type="text/css">
         .btInfoAk {
        width: 20%;
        margin: 0 auto;
        display: block;
        background: #44b549;
        color: #fff;
        text-align: center;
        padding: 6px 0;
        text-decoration: none;
        border-radius: 2px;
    }
    </style>
    <meta charset="UTF-8">
    <title>Title</title>
<script src="<?= Url::to('@web/backend/public/js/jquery-1.10.2.min.js') ?>"></script>
    <style>
        .jgg-main{width:934px;}
        .mobile_menu_preview{
            position: relative;
            width: 320px;
            height: 568px;
            background: transparent url("<?=Url::toRoute('@web/backend/it/bg_mobile_head_default2968da.png')?>") no-repeat 0 0;
            background-position: 0 0;
            border: 1px solid #e7e7eb;}
        .jgg-main-title{
            color: #fff;
            text-align: center;
            padding-top: 30px;
            font-size: 18px;
            width: auto;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            word-wrap: normal;
            margin: 0 30px;}
        .jgg-main-bottom{
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #e7e7eb;
            background: transparent url("<?=Url::toRoute('@web/backend/it/bg_mobile_foot_default2968da.png')?>") no-repeat 0 0;
            background-position: 0 0;
            background-repeat: no-repeat;
            padding-left: 43px;
            height: 50px;
        }
        .jgg-main-bottom li{line-height: 50px;width: 33.33%;float: left;border-left: 1px solid #e7e7eb;text-align: center;position: relative;height: 50px;font-size: 14px;}
        .jgg-main-bottom li a{border-right: 1px solid #e7e7eb;text-decoration: none;display: block;}
        .jgg-main-bottom .jgg-main-ul{position: absolute;z-index:2;left: 0;bottom: 60px;width: 100%;border: 1px solid #e7e7eb;background-color: #fafafa;display: none;}
        .jgg-main-bottom .jgg-main-ul li{width: 100%;border: none;}
        .jgg-main-bottom .jgg-main-ul li:hover{background: #e7e7eb;}
        .jgg-main-bottom .jgg-main-ul li a{border: none;margin-left: 4px;margin-right: 4px;display: block; border-bottom: 1px solid #e7e7eb;text-decoration: none;}
        .jgg-main-bottom .jgg-main-ul li:last-child a{border-bottom: none;}
        .jgg-main-bottom .active{border:1px solid #44b549!important;color: #44b549;height: 50px;}
        .jgg-main-bottom .active a{color: #44b549;}
        .jgg-main-bottom .active li a{color: #333;}
        .jgg-main-bottom .jgg-main-ul .active:hover{background: #fff;}
        .jgg-main-bottom .jgg-main-ul .active a{color: #44b549;}
        .jgg-info{background: #f4f5f9;margin-left: -15px;height: 568px;}
        .jgg-info h4{font-size: 15px;line-height:36px;border-bottom: 1px solid #ddd;}
        .jgg-info .form-group{margin-top: 12px;}
        .jgg-info p{color: #999;padding-top: 8px;padding-bottom:10px;}
        .bigBtn a,.bigBtnB a,.btInfoAj{width: 20%;margin: 0 auto;display: block;background: #44b549;color: #fff;text-align: center;padding: 6px 0;text-decoration: none;border-radius: 2px;}
        .btInfoAj{margin: inherit;margin-top: 10px;}
        .btInfoAj:hover{text-decoration: none;color: #fff;}
        .jgg-info .loogTs,.jgg-info .loogTb{color: #f00;}
    </style>
</head>
<body>

<div class="jgg-main" style="height: 2000px">
    <div class="col-md-5">
        <div class="mobile_menu_preview">
            <div class="jgg-main-title">I.T</div>
            <ul class="jgg-main-bottom">
                <li class="active">
                    <a href="javascript:void(0);" class="jsSubBox">
                        <span>菜单名称</span>
                    </a>
                    <div class="jgg-main-ul" style="display: block;">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="javascript:void(0);"  class="jsSubBox">
                        <span>菜单名称</span>
                    </a>
                    <div class="jgg-main-ul">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="javascript:void(0);"  class="jsSubBox">
                        <span>菜单名称</span>
                    </a>
                    <div class="jgg-main-ul">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="jsSubView" ><span class="js_l2Title">子菜单名称</span></a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-7 jgg-info">
        <div class="jggBig">
            <h4>菜单名称</h4>
            <p class="doThikg0">已为当前菜单添加了5个子菜单，无法设置其他内容。</p>
            <div class="form-group">
                <div class="col-sm-3 row">选择类型:</div>
                <div class="col-sm-9 row">
                    <select name="doThikg" id="doThikg" class="form-control">
                        <option value="0" >菜单名称</option>
                        <option value="1">跳转网页</option>
                    </select>
                    <p></p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 row">菜单名称:</div>
                <div class="col-sm-9 row">
                    <input type="text" class="form-control" id="inputE">
                    <p><span class="loogTs"></span>字数不超过4个汉字或8个字母</p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="form-group doThikg1" style="display: none;">
                <div class="col-sm-3 row">页面地址:</div>
                <div class="col-sm-9 row">
                    <input type="text" class="form-control" id="domurl">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="bigBtn">
                <a href="javascript:void(0)">确定</a>
            </div>
        </div>
        <div class="jggSmall" style="display: none;">
            <h4>子菜单名称</h4>
            <p></p>
            <div class="form-group">
                <div class="col-sm-3 row">子菜单名称:</div>
                <div class="col-sm-9 row">
                    <input type="text" class="form-control" id="inputB">
                    <p><span class="loogTb"></span>字数不超过8个汉字或16个字母</p>
                </div>
                <div class="col-sm-3 row">子菜单类型:</div>
                <div class="col-sm-9 row">
                    <select name="menu" id="menuA" class="form-control">
                        <option value="view" >链接</option>
                        <option value="click" >点击</option>
                        <option value="2" >禁用</option>
                    </select>
                    <p></p>
                </div>
                <div class="col-sm-3 row">子菜单内容:</div>
                <div class="col-sm-9 row"><input type="text" class="form-control" id="inputU" placeholder="key/value"></div>
                <div class="clearfix"></div>
            </div>
            <div class="bigBtnB">
                <a href="javascript:void(0)">确定</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">&nbsp;</div>
    <div class="col-md-4 row">
    <a href="javascript:void(0)" style="margin-top: 10px!important" class="btInfoAk">保存</a>
    </div>
    <div class="col-md-4 row">
        <a href="javascript:void(0)" class="btInfoAj">发布</a>
        
    </div>

</div>
<script>
//测试数据
    var dataList1=[{"name":"AAA","sub_button":[{"type":"click","name":"AB","key":"ADF"}]}];
    
    var dataList=[
        {
            'name':'搜索1',
            "sub_button":[
                {
                    "type":"view",
                    "name":"搜索",
                    "url":"http://www.soso.com/"
                },
                {
                    "type":"click",
                    "name":"赞一下我们",
                    "key":"V1001_GOOD"
                }]
        },
        {
            'name':'搜索2',
            "sub_button":[
                {
                    "type":"view",
                    "name":"搜索2",
                    "url":"http://www.soso.com/"
                },
                {
                    "type":"view",
                    "name":"搜索444444",
                    "url":"http://www.so2so.com/"
                },
                {
                    "type":"click",
                    "name":"赞一下我们2",
                    "key":"V1001_GOOD"
                }]
        }
    ];
    <?php if (!empty($menu)):  ?>
    dataList = <?=$menu?>;
    <?php endif; ?>
    console.log(dataList);
    var htmlJgg='';
    for(var i=0;i<3;i++){
        var dhtml='';
        var name='菜单名称';
        var da=[];
        var lk=0;
        var nameurl='';
        if(dataList[i]!==undefined){
            if(dataList[i].sub_button!=undefined){
                name=dataList[i].name;
                da=dataList[i].sub_button;
                lk=da.length
            }else{
                name=dataList[i].name;
                nameurl=dataList[i].url;
            }
        }
        for(var t=0;t<5;t++){
            var val='';
            var names='子菜单名称';
            var ht='';
            var ty='';
            if(t<lk){
                val=da[t].key||da[t].url;
                names=ht=da[t].name;
                ty=da[t].type;
            }
            dhtml='<li data-html="'+ht+'" data-type="'+ty+'" data-val="'+val+'"><a href="javascript:void(0);"class="jsSubView"><span class="js_l2Title">'+names+'</span></a></li>'+dhtml;
        }
        htmlJgg=htmlJgg+'<li data-html="'+name+'" data-url="'+nameurl+'"><a href="javascript:void(0);"class="jsSubBox"><span>'+name+'</span></a><div class="jgg-main-ul"><ul>'+dhtml+'</ul></div></li>';
    }
    $('.jgg-main-bottom').html(htmlJgg);



    
    var doURl=0;
    $(document).on('click','.jsSubBox',function () {
        $('.jgg-main-ul').hide();
        $('li').removeClass('active');
        $(this).parent().addClass('active');
        // data-url
        var nameurl=$(this).parent().attr('data-url');

        if(nameurl!=''){
            doURl=1;
            $('.doThikg1').show();
            $('.doThikg0').hide();
            $('#doThikg').val(1);
            $('#domurl').val(nameurl);
            $(this).parent().find('.jgg-main-ul').hide();
        }else {
            $(this).parent().find('.jgg-main-ul').show();
            $('#domurl').val('');
        }
        var ht=$(this).find('span').html();
        if(ht==='菜单名称'){
            ht='';
        }
        $('#inputE').val(ht);
        $('.jggSmall').hide();
        $('.jggBig').show();
    });
    $(document).on('click','.jsSubView',function () {
        $('.active').removeClass('active');
        $(this).parent().addClass('active');
        var ht=$(this).find('span').html();
        if(ht==='子菜单名称'){
            ht='';
        }
        $('#inputB').val(ht);
        var mv=$(this).parent().attr('data-type');
        if(mv===''||mv===undefined){
            mv='view';
        }
        $('#menuA').val(mv);
        $('#inputU').val($(this).parent().attr('data-val'));
        $('.jggBig').hide();
        $('.jggSmall').show();
    });
 layui.use(['layer'], function(){
       layer = layui.layer
    });
    $(document).on('click','.bigBtn a',function () {
        if($('.active').length<1){
            layer.msg('请点击选择输入的位置');
        }
        var inputE=$('#inputE').val();
        if(GetLength(inputE)>8){
            layer.msg('超出长度8个字符长度');
           return false;
        }
        if(GetLength(inputE)<1){
            layer.msg('请输入超过1个字符');
            return false;
        }
        var domurl='';
        if($('#doThikg').val()==1){
            domurl=$('#domurl').val();
            var match = /^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/;
            if(!match.test(domurl)){
                layer.msg('请填写正确的URL');
                return false;
            }
        }
        $('.active').attr('data-html',inputE).attr('data-url',domurl).find('.jsSubBox span').html(inputE);
        layer.msg('已成功添加菜单');
    });
    $('#inputE').bind('input propertychange', function() {
        var $loogTs=$('.loogTs');
        var inputE=$(this).val();
        $loogTs.html('');
        if(GetLength(inputE)>8){
            $loogTs.html('字数超过限制,');
        }
        if(GetLength(inputE)<1){
            $loogTs.html('请输入菜单名称,');
        }
    });
    GetLength = function(str)
    {
        return str.replace(/[^\x00-\xff]/g,"aa").length;
    };
      // 设置一级栏目
    $('#doThikg').change(function() {
        var val=$(this).val();
        //doThikg0 ,doThikg1
        var $active= $('.active').find('.jgg-main-ul');
        var doThikg0=$('.doThikg0');
        var doThikg1=$('.doThikg1');
        if(val==0){
            doURl=0;
            $active.show();
            doThikg0.show();
            doThikg1.hide();
        }else{
            doURl=1;
            $active.hide();
            doThikg1.show();
            doThikg0.hide();
        }
    });

    $(document).on('click','.bigBtnB a',function () {
    var menuVal=$('#menuA option:selected').val();
        if(menuVal===2){
            $('.active').attr('data-html','').attr('data-type','').attr('data-val','').find('span').html('子菜单名称');
            return false;
        }
        var inputB=$('#inputB').val();
        var $loogTs=$('.loogTb');
        $loogTs.html('');
        if(GetLength(inputB)>16){
            $loogTs.html('字数超过限制,');
            return false;
        }
        if(GetLength(inputB)<1){
            $loogTs.html('请输入菜单名称,');
            return false;
        }
        var inputU=$('#inputU').val();
        if(inputU===''&&menuVal!==2){
            //layer.msg('请输入子菜单内容');
            alert('请输入子菜单内容');
            return false;
        }
     
        if(menuVal=='view'){
            var match = /^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/;
            if(!match.test(inputU)){
                layer.msg('请填写正确的URL,必须包含http://或https://');
                return false;
            }
        }

        $('.active').attr('data-html',inputB).attr('data-type',menuVal).attr('data-val',inputU).find('span').html(inputB);
        layer.msg('已成功添加子菜单');
    });
    $('#inputB').bind('input propertychange', function() {
        var inputB=$('#inputB').val();
        var $loogTs=$('.loogTb');
        $loogTs.html('');
        if(GetLength(inputB)>16){
            $loogTs.html('字数超过限制,');
        }
        if(GetLength(inputB)<1){
            $loogTs.html('请输入菜单名称,');
        }
    });

    //保存并提交
    $(document).on('click','.btInfoAj',function () {
        var ajaxPost=[];
        $('.jgg-main-bottom').find('.jsSubBox').each(function (t) {
            var list={};
            var $par=$(this).parent('li');
            var name=$par.attr('data-html');
            if(name==='菜单名称'){return false}
            list['name']=name;
            var list_list=[];
            var lti=0;
            var nameurl=$par.attr('data-url');
            if(nameurl==''){
                $par.find('li').each(function(i){
                    var list_li={};
                    var html=$(this).attr('data-html');
                    if(html!==''){
                        list_li['name']=html;
                        var type=$(this).attr('data-type');
                        list_li['type']=type;
                        var keyUrl=$(this).attr('data-val');
                        if(type==='click'){
                            list_li['key']=keyUrl;
                        }else{
                            list_li['url']=keyUrl;
                        }
                        list_list[lti]=list_li;
                        lti=lti+1;
                    }
                });
                list['sub_button']=list_list.reverse();
            }else{
                list['url']=nameurl;
                list['type']='view';
            }
            ajaxPost[t]=list;
        });
        var postjson=JSON.stringify(ajaxPost);
       
        $.post('<?=Url::toRoute("menu/update")?>',{data:postjson,type:1},function(data){
            if (data.code == 200){
                layer.msg('发布成功',{icon:1});
            }else{
                layer.msg(data.msg,{icon:2});
            }
        })
       
    })
     //保存并提交
    $(document).on('click','.btInfoAk',function () {
        var ajaxPost=[];
        $('.jgg-main-bottom').find('.jsSubBox').each(function (t) {
            var list={};
            var $par=$(this).parent('li');
            var name=$par.attr('data-html');
            if(name==='菜单名称'){return false}
            list['name']=name;
            var list_list=[];
            var lti=0;
            var nameurl=$par.attr('data-url');
            if(nameurl==''){
                $par.find('li').each(function(i){
                    var list_li={};
                    var html=$(this).attr('data-html');
                    if(html!==''){
                        list_li['name']=html;
                        var type=$(this).attr('data-type');
                        list_li['type']=type;
                        var keyUrl=$(this).attr('data-val');
                        if(type==='click'){
                            list_li['key']=keyUrl;
                        }else{
                            list_li['url']=keyUrl;
                        }
                        list_list[lti]=list_li;
                        lti=lti+1;
                    }
                });
                list['sub_button']=list_list.reverse();
            }else{
                list['url']=nameurl;
                list['type']='view';
            }
            ajaxPost[t]=list;
        });
        var postjson=JSON.stringify(ajaxPost);
       
        $.post('<?=Url::toRoute("menu/update")?>',{data:postjson,type:2},function(data){
            if (data.code == 200){
                layer.msg('保存成功',{icon:1});
            }else{
                layer.msg(data.msg,{icon:2});
            }
        })
       
    })
    $('.wxset').show();
    $('#menu').attr('class','active');
</script>
</body>
</html>