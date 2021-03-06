/**
 * Created by abcqu on 2017/5/24.
 */

$(document).on('click','.ajaxbtn a',function(){
    var ajaxPost={};
    var name=$('#name').val();
    var myReg = /^[\u0391-\uFFE5A-Za-z]+$/;
    if(name==''){
        alertHtml('请输入姓名');
        return false;
    }
    if(!myReg.test(name)){
        alertHtml('姓名，请输入中文');
        return false;
    }
    ajaxPost['name']=name;

    var nameTwo=$('#nameTwo').val();
    if(nameTwo==''){
        alertHtml('请输入名字');
        return false;
    }
    if(!myReg.test(nameTwo)){
        alertHtml('名字，请输入中文');
        return false;
    }
    ajaxPost['nameTwo']=nameTwo;

    ajaxPost['sex']=$('#sex').val();

    var level=$('#level').val();
    if(level==''){
        alertHtml('请填写会员级别');
        return false;
    }
    ajaxPost['level']=level;

    ajaxPost['data']=$('#data').val();

    var tel=$('#tel').val();
    if(tel!=''){
        if(!/1[3|4|5|7|8]\d{9}/.test(tel)){
            alertHtml('请输入正确的手机号');
            return false;
        }
    }
    ajaxPost['tel']=tel;

    var email=$('#email').val();
    if(email==''){
        alertHtml('请输入电子邮箱');
        return false;
    }
    var objexp = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
    var re = new RegExp(objexp);
    if(!re.test(email)){
        alertHtml('请输入正确的电子邮箱');
        return false;
    }
    ajaxPost['email']=email;

    ajaxPost['area']=$('#area').val();

    ajaxPost['province']=$('#province').val();

    ajaxPost['city']=$('#city').val();

    ajaxPost['town']=$('#town').val();

    ajaxPost['address']=$('#address').val();

    ajaxPost['wedding']=$("input[name='wedding']:checked").val();

    ajaxPost['income']=$('#income').val();

    ajaxPost['career']=$('#career').val();

    ajaxPost['education']=$('#education').val();

    var interest=[];
    $('.interest').find("input[type=checkbox]:checked").each(function(i){
        interest[i]= $(this).attr('value');
    });
    ajaxPost['interest']=interest;

    ajaxPost['info']=0;
    if($('#info').is(':checked')) {
        ajaxPost['info']=1;
    }

    if(!$('#declare').is(':checked')) {
        alertHtml('接受《I.T个人资料（私隐）政策声明》,才能下一步哦');
        return false;
    }
    ajaxPost['declare']=1;
    //var object = $.extend({}, ajaxPost, interest);
    console.log(ajaxPost);
    $.ajax({
        url:ajaxUrl,//递交的路径
        type:"post",
        data:ajaxPost,
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        dataType: "json",
        success: function (data) {
        }
    });
});

//地区选择事件
$('#area').change(function(){
    var $distpicker= $("#distpicker");
    var $province= $('#province');
    var val=$(this).val();
    console.log($(this).val());
    if(val=='中国香港'){
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "香港特别行政区",
            city: "区",
            district: "街",
            autoSelect: false
        });
        $province.attr('disabled',true);
    }
    else if(val=='中国澳门'){
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "澳门特别行政区",
            city: "区",
            district: "街",
            autoSelect: false
        });
        $province.attr('disabled',true);
    }
    else if(val=='中国台湾'){
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "台湾省",
            city: "区",
            district: "街",
            autoSelect: false
        });
        $province.attr('disabled',true);
    }
    else{
        $distpicker.distpicker('destroy');
        $distpicker.distpicker({
            province: "省",
            city: "市",
            district: "区",
            autoSelect: false
        });
        $province.attr('disabled',false);
    }
});