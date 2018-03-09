/**
 * Created by abcqu on 2017/5/24.
 */
//显示错误提示
function alertHtml(info){
    var html='<div class="popBox"><div class="popFloor"><div class="popTitle"><img src="data:image/jpeg;base64,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AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA="></div><div class="popBody">'+info+'</div></div></div>';
    $(document.body).append(html);
}
//关闭错误提示
var errorMsg={
    colse:function(){
        $('.popBox').hide();
    },
    other:function(r){
        this.colse();
        return r;
    }
};
$(document).on('click','.popTitle img',function(){
    //纯粹的关闭
    var t=errorMsg.colse();
    //关闭并且返回参数
    //var t=errorMsg.other('111111111');
});
