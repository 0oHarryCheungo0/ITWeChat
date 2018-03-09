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
    if(val=='中国香港'){
        html='+852'
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
        if(isPhoneNum(phonenum)==0){
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
        if(isPhoneNum(phonenum)==0){
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
function isPhoneNum(phonenum){
    //var phonenum = $(".info_phone").val();
    //大陆
    var myreg = /^[1][3-8]\d{9}$/;
    //香港
    var myre=/^([6|9])\d{7}$/;
    var num = "";
    var rt=0;
    if(myreg.test(phonenum)){
        rt=1;
    }
    if(myre.test(phonenum)){
        rt=1;
    }
    return rt;
}
