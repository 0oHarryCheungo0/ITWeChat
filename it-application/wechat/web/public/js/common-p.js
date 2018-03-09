/**
 * Created by abcqu on 2017/5/24.
 */
//显示错误提示
var callfunc = undefined;

function alertHtml(info, func) {
    if (func != undefined) {
        callfunc = func;
    } else {
        callfunc = undefined;
    }
    var html = '<div class="popBox"><div class="popFloor"><div class="popTitle"><img src="/public/images/close_03.jpg"></div><div class="popBody">' + info + '</div></div></div>';
    callfunc = func;
    $(document.body).append(html);
}

function alertTip(info) {
    var html = '<div class="popBox tipbox"><div class="popFloor"><div class="popBody">' + info + '</div></div></div>';
    $(document.body).append(html);
}

//关闭错误提示
var errorMsg = {
    colse: function () {
        $('.popBox').hide();
    },
    other: function (r) {
        this.colse();
        return r;
    }
};

$(document).on('click', '.popTitle img', function () {
    if (callfunc != undefined) {
        callfunc();
    }
    //纯粹的关闭
    var t = errorMsg.colse();
    //关闭并且返回参数
    //var t=errorMsg.other('111111111');
});