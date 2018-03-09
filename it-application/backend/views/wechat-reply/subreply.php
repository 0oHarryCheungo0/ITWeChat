<?php
use yii\helpers\Url;

?>
<textarea id="demo" style="width:90%;margin: 2%; padding: 10px; height:200px; border:solid 1px #009688; border-radius:3px; resize:none;"><?= $text ?></textarea>
<div style="padding: 20px">
    <button class="layui-btn sub">提交</button>
</div>
<script>
    if (top.location != location) {
        var pindex = parent.layer.getFrameIndex(window.name); //获取窗口索引
    }
    layui.use('layedit', function () {
//        var layedit = layui.layedit;
//        var layindex = layedit.build('demo',
//            {
//                tool: ['link', 'unlink']
//            }); //建立编辑器


        $(".sub").click(function () {
//            content = layedit.getText(layindex);
            content = $("#demo").val();
            console.log(content);
            $.post("<?=Url::to(['wechat-reply/save-sub'])?>", {content: content}, function (ret) {
                if (ret.code == 0) {
                    parent.layer.msg('操作成功');
                    parent.layer.close(pindex);
                } else {
                    layer.msg(ret.msg);
                }
            })
        })
    });


</script>