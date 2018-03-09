/**
 * Created by abcqu on 2017/5/24.
 */

$(document).on('click','.ajaxbtn a',function(){
    var ajaxPost={};
    var name=$('#name').val();
    if(name==''){
        alertHtml('请输入姓名');
        return false;
    }
    ajaxPost['name']=name;

    ajaxPost['sex']=$('#sex').val();

    var level=$('#level').val();

    ajaxPost['vip_type']=level;

    ajaxPost['birthday']=$('#data').val();

    var tel=$('#tel').val();
    if(tel!=''){
        if(!/1[3|4|5|7|8]\d{9}/.test(tel)){
            alertHtml('请输入正确的手机号');
            return false;
        }
    }
    ajaxPost['phone']=tel;

    var email=$('#email').val();
    if(email!=''){
        var objexp = "^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$";
        var re = new RegExp(objexp);
        if(!re.test(email)){
            alertHtml('请输入正确的电子邮箱');
            return false;
        }
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

    var interest= {};
    $('.interest').find("input[type=checkbox]:checked").each(function(i){
        interest[i]= $(this).attr('value');
    });
    ajaxPost['interest']=interest;


    ajaxPost['email_sub']=0;
    if($('#info').is(':checked')) {
        ajaxPost['email_sub']=1;
    }

    if(!$('#declare').is(':checked')) {
        alertHtml('接受《I.T个人资料（私隐）政策声明》,才能下一步哦');
        return false;
    }
    console.log(ajaxPost);
    $.post(ajaxUrl,ajaxPost,function(ret){
        if(ret.code == 0){
            location.href= vipHome;
        } else {
            alertHtml(ret.msg);
        }
    })
});

//地区选择事件
$('#area').change(function(){
    var $distpicker= $("#distpicker");
    if($(this).val()=='中国香港'){
        console.log($(this).val());
        $("#distpicker").distpicker('destroy');
        $distpicker.distpicker({
            province: "香港特别行政区",
            city: "区",
            district: "街",
            autoSelect: false
        });
    }else{
        $("#distpicker").distpicker('destroy');
        $distpicker.distpicker({
            province: "省",
            city: "市",
            district: "区",
            autoSelect: false
        });
    }
});