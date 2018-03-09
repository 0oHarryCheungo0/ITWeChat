<?php
/**
 * Created by PhpStorm.
 * User: wangzq
 * Date: 2017/6/9
 * Time: 下午6:12
 */
use yii\helpers\Url;

?>
<style>
    .layui-form-label {
        width: 150px
    }

    legend {
        width: auto;
        border-bottom: none;
        margin-bottom: 0px;
    }
</style>
<div class="layui-tab  layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">会员注册设置</li>
        <li>积分规则参数设置</li>
        <li>签到积分规则</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <fieldset class="layui-elem-field">
                <legend>会员注册设置</legend>
                <div class="layui-field-box">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <label class="layui-form-label">VRID：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="vrid" class="layui-input" required lay-verify="required"
                                       value="<?= $vip_config['vr_prefix'] ?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">注册赠送积分：</label>
                            <div class="layui-input-inline">
                                <input type="number" name="point" class="layui-input" required lay-verify="required"
                                       value="<?= $vip_config['reg_point'] ?>">
                            </div>
                            <div class="layui-form-mid layui-word-aux">微信用户完成会员注册后赠送的积分</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">资料赠送积分：</label>
                            <div class="layui-input-inline">
                                <input type="number" name="finish" class="layui-input" required lay-verify="required"
                                       value="<?= $vip_config['finish_profile'] ?>">
                            </div>
                            <div class="layui-form-mid layui-word-aux">微信用户完成所有选填资料后赠送的积分</div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">过期时间(月)：</label>
                            <div class="layui-input-inline">
                                <input type="number" id='bonus-exp' name="exp" class="layui-input" required
                                       lay-verify="number" value="<?=$vip_config['exp_time']?>">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="regset">保存</button>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="layui-tab-item">
            <fieldset class="layui-elem-field">
                <legend>积分参数规则设置</legend>
                <div class="layui-field-box">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <label class="layui-form-label">类型</label>
                            <div class="layui-input-inline">
                                <select name="type" lay-verify="required" lay-filter="op-type">
                                    <option value="">请选择</option>
                                    <option value="0">签到积分</option>
                                    <option value="1">完成会员注册</option>
                                    <option value="2">完成资料填写</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">VBID：</label>
                            <div class="layui-input-inline">
                                <input type="text" id="bonus-vbid" name="vbid" class="layui-input" required
                                       lay-verify="required">

                            </div>
                            <div class="layui-form-mid layui-word-aux"></div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">VBGROUP值：</label>
                            <div class="layui-input-inline">
                                <input type="text" id='bonus-vbgroup' name="vbgroup" class="layui-input" required
                                       lay-verify="required">

                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">过期时间(月)：</label>
                            <div class="layui-input-inline">
                                <input type="number" id='bonus-exp-extr' name="exp" class="layui-input" required
                                       lay-verify="number">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label"></label>
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit lay-filter="signbp">保存</button>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="layui-tab-item">
            <fieldset class="layui-elem-field">
                <legend>签到积分规则</legend>
                <div class="layui-field-box">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <label class="layui-form-label">签到基础分：</label>
                            <div class="layui-input-inline">
                                <input type="number" id="check_in_base" name="base" class="layui-input" required
                                       lay-verify="number" value="<?=$check_in_rule['base'] ?>">

                            </div>
                            <div class="layui-form-mid layui-word-aux"></div>
                        </div>
                        <table style="width:400px" class="layui-table">
                            <colgroup>
                                <col width="150">
                                <col width="200">
                                <col>
                            </colgroup>
                            <thead>
                            <tr>
                                <th>周期（天）</th>
                                <th>额外获得积分</th>
                                <th>
                                    <button class="layui-btn layui-btn-small layui-btn-normal" id='add'>新增</button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="tbody">
                            <?php
                            foreach ($check_in_rule['config'] as $day => $value) {
                                ?>
                                <tr id="">
                                    <td><input type="text" name="title" id="" value="<?= $day ?>" class="layui-input">
                                    </td>
                                    <td><input type="text" name="title" id="" value="<?= $value ?>" class="layui-input">
                                    </td>
                                    <td><i class="layui-icon" style="font-size: 30px; color: #1E9FFF;">&#xe640;</i></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="layui-btn" id="save">保存</button>
                        </div>
                    </div>
                </div>
        </div>
        </fieldset>
    </div>
</div>
</div>
<script>
    //注意：选项卡 依赖 element 模块，否则无法进行功能性操作
    layui.use(['element', 'form', 'layer'], function () {
        var element = layui.element();
        var form = layui.form();
        var layer = layui.layer;


        form.on('select(op-type)', function (data) {
            console.log(data);
//            $("#bonus-vbid").val('123');
//
//            $("#bonus-vbgroup").val('12123123');
            loadValue(data.value);
        });

        form.on('submit(regset)', function (data) {
            config = JSON.stringify(data.field);
            $.post("<?=Url::to(['system-rules/save-vip-config'])?>", {config: config}, function (ret) {
                if (ret.code == 0) {
                    layer.msg('保存会员设置成功');
                } else {
                    layer.msg(ret.msg);
                }
            })
            return false;
        });

        form.on('submit(signbp)', function (data) {
            if (data.field.vbid.length > 10) {
                layer.msg('vbid前缀长度不能大于10位');
                return false;
            }
            $.post("<?=Url::to(['system-rules/save-bonus-config'])?>", data.field, function (ret) {
                if (ret.code == 0) {
                    layer.msg('保存积分设置成功');
                } else {
                    layer.msg(ret.msg);
                }
            })
            return false;
        });

        function loadValue(type) {
            $.get("<?=Url::to(['system-rules/get-bonus-config'])?>", {type: type}, function (ret) {
                if (ret.code == 0) {
                    data = ret.data;
                    console.log(data);
                    $("#bonus-vbid").val(data.vbid);

                    $("#bonus-vbgroup").val(data.vbgroup);

                    $("#bonus-exp-extr").val(data.exp);
                    form.render();
                } else {
                    layer.msg('加载错误');
                }
            })

        }

        //…
    });


    $(".add-herf").click(function () {
    });


    $("#add").on("click", function () {
        tr = [
            '<tr id="" day="" point="">',
            '<td><input type="text" name="title" id="" value="" class="layui-input">   </td>',
            '<td><input type="text" name="title" id="" value="" class="layui-input">   </td>',
            '<td ><i class="layui-icon" style="font-size: 30px; color: #1E9FFF;">&#xe640;</i></td>',
            '</tr>'
        ];
        tr = tr.join('');
        $("tbody").append(tr);

    })
    $("table").on("click", "i", function () {
        if ($(this).parents("tr").attr("id") == "") {
            $(this).parents("tr").remove();
        }
    })

    $("#save").on("click", function () {
        var data = new Array();
        $("#tbody").find("tr").each(function (index, item) {
            day = $(item).find("td").eq(0).children().val();
            point = $(item).find("td").children().eq(1).val();
            data.push({day: day, point: point})
        })
        data = JSON.stringify(data);
        base = $("#check_in_base").val();
        console.log(data);
        $.post("<?=Url::to(['system-rules/save-check-in-config'])?>", {config: data,base:base}, function (ret) {
            if (ret.code == 0) {
                layer.msg('保存签到规则成功');
            } else {
                layer.msg(ret.msg);
            }
        })
    })


    $('#point').show();
    $('#menu_point').attr('class', 'active');


</script>