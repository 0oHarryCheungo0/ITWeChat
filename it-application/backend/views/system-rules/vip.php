<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/9
 * Time: 下午6:13
 */
?>
<style>
    .layui-form-label{width:150px}
    legend {
        width: auto;
        border-bottom: none;
        margin-bottom: 0px;
    }
</style>
<fieldset class="layui-elem-field">
    <legend>会员注册设置</legend>
    <div class="layui-field-box">
        <div class="layui-form" >
            <div class="layui-form-item">
                <label class="layui-form-label">VRID前缀：</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" required  lay-verify="required">

                </div>
                <div class="layui-form-mid layui-word-aux">若需要设置VRID为"VR123123123"，则填"VR"即可,最大长度为8个字符</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">赠送积分：</label>
                <div class="layui-input-inline">
                    <input type="number" class="layui-input" required  lay-verify="required">
                </div>
                <div class="layui-form-mid layui-word-aux">微信用户完成会员注册后赠送的积分</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">会员过期时间：</label>
                <div class="layui-input-block">
                    <input type="radio" name="exp" value="0" title="一年后" checked>
                    <input type="radio" name="exp" value="1" title="永不过期">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </div>
    </div>
</fieldset>


<script>
    //Demo
    layui.use('form', function(){
        var form = layui.form();

        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            return false;
        });
    });
</script>
