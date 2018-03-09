/**
 * Created by abcqu on 2016/12/15.
 */

/***
 * 左边导航的高度
 * ***/
var windowH=$(window).height();
$('.admin-left-navBox').css('height',windowH);
$('.admin-right-box').css('height',windowH);
$('.admin-rightBox').css('height',windowH);

/***
 * 左边导航-点击收缩下拉菜单
 * ***/
$(document).on('click','.module-top',function(){
    var $module=$(this).find('.module-title');
    //var status=$(this).find('.module-title').is('.icon-downBottom');
    if($module.is('.icon-downBottom')==true){
        $module.attr('class','iconfont icon-downRight-copy module-title');
    }else{
        $module.attr('class','iconfont icon-downBottom module-title');
    }
    $(this).parent().find('ul').slideToggle("slow");
    //console.log(status);
});
/***
 * 左边导航-点击向左边伸缩
 * ***/
$(document).on('click','.extend',function(){
    //console.log( $("#adminNav").attr('class'));
    $('#adminNav').toggleClass("admin-nav-icon");
    var $thisSpan=$(this).find('span');
    var $adminMain=$('.admin-right-main');
    var $adminRight=$('.admin-right-main-left');
    var rw=$adminRight.width();
    if(rw==null){
        rw=0;
    }
    var w=($('.admin-left-navBox').width())-20;
    var $adminRspan=$('.admin-rightBox-span');
    if(($thisSpan.is('.icon-systole'))==true){
        $thisSpan.attr('class','iconfont icon-expand');
        if(rw==50){
            $adminMain.css('margin-left','50px');
            $adminRight.css('left','0px');
        }else{
            $adminMain.css('margin-left',(w+rw)+'px');
            $adminRight.css('left','50px');
        }
    }else{
        $thisSpan.attr('class','iconfont icon-systole');
        if(rw==50){
            $adminMain.css('margin-left','180px');
            $adminRight.css('left','0px');
        }else{
            $adminMain.css('margin-left',(w+rw)+'px');
            $adminRight.css('left','180px');
        }
    }

    if(rw==50){
        $adminRspan.css('left',(w-2)+'px');
    }else{
        $adminRspan.css('left',(w+rw-18)+'px');
    }

});
/***
 * 左边导航-显示tips
 * ***/
$(document).on('mouseover','.module a',function(){
    if(($('#adminNav').is('.admin-nav-icon'))==true){
        $(this).tooltip('show');
       // console.log(1);
    }else{
        $(this).tooltip('hide');
        ///console.log(2);
    }
});
/***
 * 2级导航-缩进
 * ***/
$(document).on('click','.admin-rightBox-span',function(){
  var w=($('.admin-left-navBox').width())-22;
  var $adminRight=$('.admin-right-main-left');
  var rw=$adminRight.width();
    if(rw==50){
        $adminRight.css({'left':(w)+'px','width':'180px'});
        $(this).css({'left':(w+160)+'px'});
        $('.admin-right-main').css('margin-left',(w+180+2)+'px');
    }else{
        $adminRight.css({'left':'0px','width':'50px'});
        $(this).css({'left':(w-4)+'px'});
        $('.admin-right-main').css('margin-left',(w+2)+'px');
    }
    //var bw=$adminRight.width();
  $(this).toggleClass("admin-rightBox-spanBg");
});
/***
 * 2级导航-点击选择
 * ***/
$(document).on('click','.admin-rightBox-nav ul li',function(){
    $('.admin-rightBox-nav ').find('li').each(function(){
        $(this).removeClass('active');
    });
    $(this).addClass('active');
});

