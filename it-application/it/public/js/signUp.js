/**
 * Created by abcqu on 2017/5/25.
 */

    //字体转换
    $(document).on('click','.fontPick a',function(){
        $('.fontPick a').removeClass('active');
        $(this).addClass('active');
        var html=$(this).html();
        $.ajax({
            url:fontUrl,//递交的路径
            type:"post",
            data:{fontHtml:html},
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            dataType: "json",
            success: function (data) {
            }
        });
    });
    //选择地区结果
    $('#area').change(function(){
        var val=$(this).val();
        var html='+86';
        if(val=='香港地区'){
            html='+852'
        }
        if(val=='澳门地区'){
            html='+853'
        }
        if(val=='台湾地区'){
            html='+886'
        }
        $('.telMain .left').eq(0).html(html);
    });
    //注册
    $(document).on('click','.ajaxbtn a',function(){
        var ajaxPost={};

        var area=$('#area').val();
        if(area==''){
            alertHtml("请选择地区");
            return false;
        }
        ajaxPost['area']=area;

        var phonenum = $("#tel").val();
        if(isPhoneNum(phonenum,area)==0){
            alertHtml("请输入正确的手机号");
            return false;
        }
        ajaxPost['tel']=phonenum;

        var code = $("#code").val();
        if(code==''){
            alertHtml("请输入手机验证码");
            return false;
        }
        ajaxPost['code']=code;




    });
    /**倒计时**/
    var countdown=60;
    var codeInfo=0;
    var getcount=getCookie('countdownCookie');
    if(getcount!=null){
        countdown=getcount;
        settime('codeHtml');
    }
    function settime(tval) {
        var val=$("."+tval);
        var t=setTimeout(function() {
            settime(tval)
        },1000);
        if (countdown == 0) {
            clearTimeout(t);
            val.removeAttr('style');
            val.html("获取验证码");
            countdown = 60;
            codeInfo=0
        } else {
            codeInfo=1;
            val.css("background","#ccc");
            val.html("重发(" + countdown + ")");
            countdown--;
            setCookie("countdownCookie",countdown);
        }
    }
    //手机验证码
    $(document).on("click",'.codeHtml',function () {
        if(codeInfo==1){return false;}
        var area=$('#area').val();
        if(area==''){
            alertHtml("请选择地区");
            return false;
        }
        var phonenum = $("#tel").val();
        if(isPhoneNum(phonenum,area)==0){
            alertHtml("请输入正确的手机号");
            return false;
        }
        //$(this).html('90S');
        settime('codeHtml');
        $.ajax({
            url:codeUrl,//递交的路径
            type:"post",
            data:{phone:phonenum},
            contentType: "application/x-www-form-urlencoded; charset=utf-8",
            dataType: "json",
            success: function (data) {
                if(data!=""){
                    alertHtml("验证码已发送到您的手机，请注意查收");
                    return false;
                }
            }
        });
        // RemainTime();

    })

    //校验手机号是否合法
    function isPhoneNum(phonenum,aler){
        //var phonenum = $(".info_phone").val();
        //大陆
        var myreg = /^[1][3-8]\d{9}$/;
        //香港
        var myre=/^([6|9])\d{7}$/;
        //台湾
        var mytw=/^([9])\d{8}$/;
        //澳门
        var myam=/^([6])\d{7}$/;
        var num = "";
        var rt=0;
        console.log(aler);
        if(aler=='香港地区'){
            if(myre.test(phonenum)){
                rt=1;
            }
        }
        else if(aler=='澳门地区'){
            if(myam.test(phonenum)){
                rt=1;
            }
        }
        else if(aler=='台湾地区'){
            if(mytw.test(phonenum)){
                rt=1;
            }
        }
        else{
            if(myreg.test(phonenum)){
                rt=1;
            }
        }
        console.log(rt);
        return rt;
    }
//注册删除手机号码
$('#tel').bind('input propertychange', function() {
    var $colse=$('.icon-colse').parent();
    if($(this).val()==''){
        $colse.css('opacity',0);
    }else{
        $colse.css('opacity',1);
    }
});
//删除电话号码
$('.telMain .icon-colse').on('click',function () {
    $('#tel').val('');
    $('.icon-colse').parent().css('opacity',0);
});
//
$("input").focus(function(){
   $('.singUp-bt').hide();
});
$("input").blur(function(){
    $('.singUp-bt').show();
});
