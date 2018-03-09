/**
 * Created by abcqu on 2017/5/24.
 */
//显示错误提示
function alertHtml(info){
    var html='<div class="popBox"><div class="popFloor"><div class="popTitle"><img src="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABkAAD/4QQfaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzEzOCA3OS4xNTk4MjQsIDIwMTYvMDkvMTQtMDE6MDk6MDEgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0idXVpZDo1RDIwODkyNDkzQkZEQjExOTE0QTg1OTBEMzE1MDhDOCIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDowQjEyM0VGRDQwNjgxMUU3QTA3RThEMkQ1NzU4N0UyMiIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDowQjEyM0VGQzQwNjgxMUU3QTA3RThEMkQ1NzU4N0UyMiIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBJbGx1c3RyYXRvciBDQyAyMDE3IChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NjRlNzJiOWUtNDExNi00OWIyLTk5MDYtMWQ1YzNhYTdiZDRiIiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6MGEx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"></div><div class="popBody">'+info+'</div></div></div>';
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
