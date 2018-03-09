/**
 * Created by abcqu on 2017/6/8.
 */
var loadingAct=function(){
    var divObj=document.createElement("div");
    divObj.innerHTML='<div style=""><img src="public/images/vip_c_10.gif"style="width: 36%;margin-top: 40%;"><p style="text-align: center;font-size: 18px;color: #fff; font-weight: bold; padding-top: 10px;">...loading...</p></div>';
    var first=document.body.firstChild;
    document.body.insertBefore(divObj,first);

};
loadingAct();