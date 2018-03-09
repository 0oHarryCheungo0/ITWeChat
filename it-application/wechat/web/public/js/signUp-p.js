/**
 * Created by abcqu on 2017/5/25.
 */
//字体转换
$(document).on('click', '.fontPick a', function () {
    $('.fontPick a').removeClass('active');
    $(this).addClass('active');
    var html = $(this).html();
    lang = 'cn';
    if (html != '简体中文') {
        lang = 'hk';
    }
    $.ajax({
        url: fontUrl,//递交的路径
        type: "post",
        data: {lang: lang},
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        success: function (data) {
            location.reload();
        }
    });
});
//选择地区结果
$('#area').change(function () {
    var val = $(this).val();
    var html = '+86';
    if (val == '香港') {
        html = '+852'
    }
    if (val == '台湾') {
        html = "+886"
    }
    if (val == '澳门') {
        html = "+853"
    }
    $('.telMain .left').eq(0).html(html);
});
//注册
var can_post = true;
$(document).on('click', '.ajaxbtn a', function () {
    if (can_post == false){
        return false;
    }
    can_post = false;
    var ajaxPost = {};

    var area = $('#area').val();
    if (area == '') {
        alertHtml(need_area);
        return false;
    }
    ajaxPost['area'] = area;

    var phonenum = $("#tel").val();
    if (isPhoneNum(phonenum, area) == 0) {
        alertHtml(right_phone);
        return false;
    }
    ajaxPost['phone'] = phonenum;

    var code = $("#code").val();
    if (code == '') {
        alertHtml(need_code);
        return false;
    }
    ajaxPost['code'] = code;
    alertTip(requesting);
    $.ajax({
        url: signUpUrl,//递交的路径
        type: "post",
        data: ajaxPost,
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        success: function (data) {
            $('.tipbox').remove();
            delCookie('countdownCookie');
            if (data.code == 0 || data.code == 201) {
                alertHtml(data.msg, function () {
                    location.href = vipHome;
                });
                can_post = true;
            } else {
                alertHtml(data.msg, function () {
                    location.reload();
                })
            }
        }
    });


});
/**倒计时**/
var countdown = 60;
var codeInfo = 0;
var getcount = getCookie('countdownCookie');
// if (getcount != null) {
//     countdown = getcount;
//     settime('codeHtml');
// }
function settime(tval) {
    var val = $("." + tval);
    var t = setTimeout(function () {
        settime(tval)
    }, 1000);
    if (countdown == 0) {
        clearTimeout(t);
        val.removeAttr('style');
        val.html(getcode);
        countdown = 60;
        codeInfo = 0
    } else {
        codeInfo = 1;
        val.css("background", "#ccc");
        val.html("重发(" + countdown + ")");
        countdown--;
        setCookie("countdownCookie", countdown);
    }
}

//手机验证码
$(document).on("click", '.codeHtml', function () {
    if (codeInfo == 1) {
        return false;
    }
    var area = $('#area').val();
    var phonenum = $("#tel").val();
    if (isPhoneNum(phonenum, area) == 0) {
        alertHtml(right_phone);
        return false;
    }
    settime('codeHtml');
    $.ajax({
        url: codeUrl,//递交的路径
        type: "post",
        data: {phone: phonenum, area: area},
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        success: function (data) {
            if (data.code == 200) {
                alertHtml(code_ok);
                return false;
            } else {
                alertHtml('发送失败,请稍后再试');
                return false;
            }
        }
    });
    // RemainTime();

})

function isPhoneNum(phonenum, aler) {
    //var phonenum = $(".info_phone").val();
    //大陆
    var myreg = /^[1][2-8]\d{9}$/;
    //香港
    var myre = /^([3|6|9])\d{7}$/;
    //台湾
    var mytw = /^([9])\d{8}$/;
    //澳门
    var myam = /^([6])\d{7}$/;
    var num = "";
    var rt = 0;
    console.log(aler);
    if (aler == '香港') {
        if (myre.test(phonenum)) {
            rt = 1;
        }
    }
    else if (aler == '澳门') {
        if (myam.test(phonenum)) {
            rt = 1;
        }
    }
    else if (aler == '台湾') {
        if (mytw.test(phonenum)) {
            rt = 1;
        }
    }
    else {
        if (myreg.test(phonenum)) {
            rt = 1;
        }
    }
    console.log(rt);
    return rt;
}


$('#tel').bind('input propertychange', function () {
    var $colse = $('.icon-colse').parent();
    if ($(this).val() == '') {
        $colse.css('opacity', 0);
    } else {
        $colse.css('opacity', 1);
    }
});
//删除电话号码
$('.telMain .icon-colse').on('click', function () {
    $('#tel').val('');
    $('.icon-colse').parent().css('opacity', 0);
});
//
$("input").focus(function () {
    $('.singUp-bt').hide();
});
$("input").blur(function () {
    $('.singUp-bt').show();
});