/**
 * Created by abcqu on 2017/5/24.
 */

$(document).on('click', '.ajaxbtn a', function () {
    var ajaxPost = {};
    var name = $('#name').val();
    var myReg = /^[\u0391-\uFFE5A-Za-z-\s]+$/;
    if (name == ''||name==' ') {
        alertHtml(need_fname);
        return false;
    }

    if (!myReg.test(name)) {
        alertHtml(need_fname);
        return false;
    }
    ajaxPost['name'] = name;
    var nameTwo = $('#nameTwo').val();
    if (nameTwo == ''||nameTwo==' ') {
        alertHtml(need_lname);
        return false;
    }

    if (!myReg.test(nameTwo)) {
        alertHtml(need_lname);
        return false;
    }
    ajaxPost['name_first'] = name;

    ajaxPost['name_last'] = nameTwo;

    ajaxPost['name'] = name + " " + nameTwo;

    ajaxPost['sex'] = $('#sex').val();

    //ajaxPost['birthday'] = $('#birthday').val();
    var thetime = $('#birthday').val();
    var   d=new   Date(Date.parse(thetime .replace(/-/g,"/")));
    var   curDate=new   Date();
    if(d >curDate){
        alertHtml(error_mail);
        return false;
    }
    ajaxPost['birthday']=thetime;

    var email = $('#email').val();
    if (email == '') {
        alertHtml(error_mail);
        return false;
    }
    if (email.length > 30) {
        alertHtml(error_mail);
        return false;
    }
    var objexp = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
    var re = new RegExp(objexp);
    if (!re.test(email)) {
        alertHtml(error_mail);
        return false;
    }

    ajaxPost['email'] = email;

    ajaxPost['area'] = $('#area').val();

    ajaxPost['province'] = $('#province').val();

    ajaxPost['city'] = $('#city').val();

    ajaxPost['town'] = $('#town').val();

    ajaxPost['addr1'] = $('#address').val();

    ajaxPost['marriage'] = $("input[name='wedding']:checked").val();

    ajaxPost['income'] = $('#income').val();

    ajaxPost['career'] = $('#career').val();

    ajaxPost['education'] = $('#education').val();

    var interest = {};
    $('.interest').find("input[type=checkbox]:checked").each(function (i) {
        interest[i] = $(this).attr('value');
    });
    ajaxPost['interest'] = interest;


    ajaxPost['sub_email'] = 0;
    if ($('#info').is(':checked')) {
        ajaxPost['sub_email'] = 1;
    }

    if (!$('#declare').is(':checked')) {
        alertHtml(allow_rule);
        return false;
    }
    $.post(ajaxUrl, ajaxPost, function (ret) {
        if (ret.code == 0) {
            alertHtml(update_ok, function () {
                location.href = home;
            });
        } else if (ret.code == 100) {
            alertHtml(ret.msg, function () {
                location.href = home;
            });
        } else if (ret.code == 101) {
            alertHtml(ret.msg);
        } else {
            alertHtml(ret.msg);
        }
    })
});

//地区选择事件
$('#area').change(function () {
    var $distpicker = $("#distpicker");
    var $province = $('#province');
    var val = $(this).val();
    console.log($(this).val());
    if (val == '香港') {
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "香港特别行政区",
            city: "区",
            district: "街",
            autoSelect: false
        });
        $province.attr('disabled', true);
    }
    else if (val == '澳门') {
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "澳门特别行政区",
            city: "区",
            district: "街",
            autoSelect: false
        });
        $province.attr('disabled', true);
    }
    else if (val == '台湾') {
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "台湾省",
            city: "区",
            district: "街",
            autoSelect: false
        });
        $province.attr('disabled', true);
    }
    else {
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "省",
            city: "市",
            district: "区",
            autoSelect: false
        });
        $province.attr('disabled', false);
    }
});