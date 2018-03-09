<?php

use yii\helpers\Url;

?>
<fieldset class="layui-elem-field" style="padding-bottom: 20px">
    <legend>筛选条件</legend>

    <div class="layui-form">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">VIP卡号</label>
                <div class="layui-input-inline">
                    <input type="text" name="vip_code" placeholder="VIP卡号" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">会员类型</label>
                <div class="layui-input-inline">
                    <input type="text" name="vip_type" placeholder="会员类型" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">手机号</label>
                <div class="layui-input-inline">
                    <input type="tel" name="phone" placeholder="会员手机号码" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">会员姓名</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" placeholder="会员姓名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">邮箱地址</label>
                <div class="layui-input-inline">
                    <input type="text" name="email" placeholder="邮箱地址" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">会员性别</label>
                <div class="layui-input-inline">
                    <select name="sex" lay-filter="aihao">
                        <option value="">不限</option>
                        <option value="0">女</option>
                        <option value="1">男</option>
                        <option value="2">保密</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">I.TOKEN大于</label>
                <div class="layui-input-inline">
                    <input type="number" name="itoken" placeholder="I.TOKEN" autocomplete="off" class="layui-input"
                           value="0">
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-inline">
                    <label class="layui-form-label">生日</label>
                    <div class="layui-input-inline">
                        <select name="mob" lay-filter="mob">
                            <option value="0">ALL</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">绑定时间</label>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="bind_start" id="date" placeholder="" autocomplete="off"
                           class="layui-input" onclick="layui.laydate({elem: this})">
                </div>
                <div class="layui-form-mid">-</div>
                <div class="layui-input-inline" style="width: 100px;">
                    <input type="text" name="bind_end" id="date" placeholder="" autocomplete="off"
                           class="layui-input" onclick="layui.laydate({elem: this})">
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-block">
                    <label class="layui-form-label">地区</label>
                    <div class="layui-input-inline">
                        <select id="area" lay-ignore class="layui-form-select layui-input">
                            <option value="大陆" selected>大陆</option>
                            <option value="香港">香港</option>
                            <option value="澳门">澳门</option>
                            <option value="台湾">台湾</option>
                        </select>
                    </div>
                    <div id="distpicker">
                        <div class="layui-input-inline">
                            <select name="province" id="province" lay-ignore
                                    class="layui-form-select layui-input"></select>
                        </div>
                        <div class="layui-input-inline">
                            <select name='city' id="city" lay-ignore class="layui-form-select layui-input"></select>
                        </div>

                    </div>
                </div>

            </div>
            <div class="layui-form-item">
                <div class="layui-block">
                    <label class="layui-form-label">店铺</label>
                    <div class="layui-input-inline">
                        <select name="store" lay-verify="" lay-search>
                            <option value="0">全部</option>
                            <option value="-1">无绑定店铺</option>
                            <?php
                            foreach ($stores as $store) {
                                ?>
                                <option value="<?= $store['id'] ?>"><?= $store['store_name'] ?>
                                    (<?= $store['store_code'] ?>)
                                </option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>


        </div>
        <div class="layui-inline">
            <label class="layui-form-label"></label>
            <div class="layui-input-inline">
                <button class="layui-btn" lay-submit="" lay-filter="search">查询</button>
                <a href="<?= Url::to(['vips/all']) ?>">
                    <button class="layui-btn layui-btn-warm">重置</button>
                </a>
                <button class="layui-btn layui-btn-normal export">导出</button>
            </div>
        </div>
    </div>
</fieldset>
<div class="layui-field-box">
    <table id="table" lay-skin="row"></table>
</div>

<script src="<?= Url::to('@web/backend/distpicker.data.js') ?>"></script>
<script src="<?= Url::to('@web/backend/distpicker.js') ?>"></script>
<script type="text/javascript">

    var search = new Object();

    layui.use(['form', 'layer', 'laydate'], function () {
        var layer = layui.layer;
        var $ = layui.jquery;
        form = layui.form();

        form.on('submit(search)', function (data) {
            search = new Object();
            field = data.field;
            if (field.vip_code != '') {
                search.vip_code = field.vip_code;
            }
            if (field.vip_type != '') {
                search.vip_type = field.vip_type;
            }
            if (field.phone != '') {
                search.phone = field.phone;
            }
            if (field.name != '') {
                search.name = field.name
            }
            if (field.email != '') {
                search.email = field.email
            }
            if (field.sex != '') {
                search.sex = field.sex;
            }
            search.itoken = field.itoken;
            if (field.mob != 0) {
                search.mob = field.mob;
            }

            if (field.bind_start != '') {
                search.bindstart = field.bind_start;
            }
            if (field.bind_end != '') {
                search.bindend = field.bind_end;
            }
            if (field.province != '') {
                search.province = field.province;
            }
            if (field.city != '') {
                search.city = field.city;
            }
            search.store = field.store;
            console.log(search);
            $("#table").bootstrapTable('refresh');
            return false;
        });

    });


    var _AJAXURL__ = "<?=Url::to(['vips/table-data'])?>";
    $('#table').bootstrapTable({
        pagination: true,
        classes: 'table-no-bordered layui-table',
        queryParams: queryParams,
        pageSize: 10,
        sortOrder: 'desc',
        // search: true,
        sidePagination: 'server',
        // showRefresh: true,
        columns: [{
            field: 'id',
            title: 'ID',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'vip_no',
            title: 'VIP卡号',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'vip_type',
            title: '会员类型',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'name',
            title: 'VIP姓名',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'sex',
            title: '性别',
            align: 'center',
            valign: 'middle',
            formatter: showSex,
        }, {
            field: 'wechat.nickname',
            title: '微信昵称',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'phone',
            title: '手机号',
            align: 'center',
            valign: 'middle',

        }, {
            field: 'email',
            title: 'E-mail',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'info.province',
            title: '省',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'info.city',
            title: '城市',
            align: 'center',
            valign: 'middle',
        }, {
            title: '绑定店铺',
            align: 'center',
            valign: 'middle',
            formatter: showStore,
        }, {
            field: 'bind_time',
            title: '绑定日期',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'bonus.bonus',
            title: 'I.TOKEN',
            align: 'center',
            valign: 'middle',
        }, {
            field: 'birthday',
            title: '生日',
            align: 'center',
            valign: 'middle',
        }, {
            title: '操作',
            width: '180px',
            align: 'center',
            valign: 'middle',
            formatter: operateFormatter,
            events: 'operateEvents',
        }],
        method: 'GET',
        url: _AJAXURL__,
        contentType: 'application/json',
        dataType: 'json',
        responseHandler: function (ret) {
            return ret.data;
        }
    });

    function operateFormatter(value, row, index) {
        return '<button class="layui-btn layui-btn-mini layui-btn-danger unbind">解除绑定</button>';
    }

    window.operateEvents = {
        'click .unbind': function (e, value, row, index) {
            layer.confirm('确认移除' + row.vip_no + '的绑定关系？', {
                icon: 3,
                title: '确认移除？',
                shadeClose: true,
            }, function (layer_index) {
                $.post("<?=Url::to(['vips/unbind'])?>", {user_id: row.id}, function (ret) {
                    if (ret.code == 0) {
                        layer.msg('移除成功');
                        $('#table').bootstrapTable('refresh');
                    } else {
                        layer.msg('移除失败');
                    }
                })
                layer.close(layer_index);
            });
        },
    };

    function showSex(value) {
        switch (value) {
            case "1":
                status = '男';
                break;
            case "0":
                status = '女';
                break;
            default:
                status = '保密';
        }
        return status;
    }

    function showStore(value, row) {
        if (row.store != null) {
            return row.store.store_name;
        } else {
            return '-';
        }

    }

    function queryParams(params) {
        search_params = $.extend({}, params, search)
        return search_params;
    }

    var urlEncode = function (param, key, encode) {
        if (param == null) return '';
        var paramStr = '';
        var t = typeof (param);
        if (t == 'string' || t == 'number' || t == 'boolean') {
            paramStr += '&' + key + '=' + ((encode == null || encode) ? encodeURIComponent(param) : param);
        } else {
            for (var i in param) {
                var k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i);
                paramStr += urlEncode(param[i], k, encode);
            }
        }
        return paramStr;
    };

    $(".export").click(function () {
        base = "<?=Url::to(['vips/export'])?>";
        console.log(search);
        params = urlEncode(search);

        url = base + "?" + params;
        console.log(url);
        window.open(url);

    })

    var $distpicker = $("#distpicker");
    $distpicker.distpicker('destroy');

    $('#area').change(function () {
        var $distpicker = $("#distpicker");
        var $province = $('#province');
        var val = $(this).val();
        console.log($(this).val());
        if (val == '香港') {
            $distpicker.distpicker('destroy');
            $distpicker.distpicker({
                province: "香港特别行政区",
                city: "区",
                district: "街",
                autoSelect: false
            });
            $province.attr('disabled', true);
        }
        else if (val == '澳门') {
            $distpicker.distpicker('destroy');
            $distpicker.distpicker({
                province: "澳门特别行政区",
                city: "区",
                district: "街",
                autoSelect: false
            });
            $province.attr('disabled', true);
        }
        else if (val == '台湾') {
            $distpicker.distpicker('destroy');
            $distpicker.distpicker({
                province: "台湾省",
                city: "区",
                district: "街",
                autoSelect: false
            });
            $province.attr('disabled', true);
        }
        else {
            $distpicker.distpicker('destroy');
            $distpicker.distpicker({
                province: "省",
                city: "市",
                district: "区",
                autoSelect: false
            });
            $province.attr('disabled', false);
        }
    });

    $('#member').show();
    $('#menu_vip').attr('class', 'active');
</script>
