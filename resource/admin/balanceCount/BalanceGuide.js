
function orderModifyFormat(value, row, index) {
    if (value !== null) {
        var s_tip = "更新时间:"+row.eoi_update_date+" \r\n更新说明:"+row.eoi_update_memo;
        return '<a href="javascript:void(0)" class="easyui-tooltip" title="'+s_tip+'" style="color:#CC3333">已更新</a>';
    } else {
        return '无修改';
    }
}
function orderCheckFormat(value, row, index) {
    if (row.eoi_ba_bat_id === '0') {
        return '未结算';
    } else {
        $("#dg_orders").datagrid('uncheckRow', index);
        return '已结算';
    }
}

var tb_balance = [{
        text: '移除',
        iconCls: 'icon-remove',
        handler: function () {
            $.messager.confirm('确认', '确认移除此订单不进行结算吗?', function (r) {
                if (r) {
                    bal_doDelete();
                }
            });
        }
    }, '-', {
        text: '结算',
        iconCls: 'icon-sum',
        handler: function () {
            doBalance();
        }
    }, '-'];
$(function () {
    $('#btn_search').bind('click', function () {
        doSearch();
    });
    $('#btn_select').bind('click', function () {
        doSelect();
    });
    $("#dg_orders").datagrid({
        onLoadSuccess: function (data) {
            if (data) {
                $.each(data.rows, function (index, row) {
                    if (row.eoi_order_state_enum === ENUM_ORDER_STATE_FINISH) {
//                        $("#dg_orders").datagrid('selectRow', index);
                        $("#dg_orders").datagrid('checkRow', index);
                        if (row.eoi_ba_bat_id - 0 !== 0) {
//                        $("#dg_orders").datagrid('unselectRow', index);
                            $("#dg_orders").datagrid('uncheckRow', index);
                        }
                    } else {
//                        $("#dg_orders").datagrid('unselectRow', index);
                        $("#dg_orders").datagrid('uncheckRow', index);
                    }
                });
            }
        }
    });
    initSearch();
    initEdit();
});
function initSearch() {
    var now = new Date();
    var yesterday = myformatter(new Date(now.getFullYear(), now.getMonth(), now.getDate() - 1));
    $("#q_date_begin").datebox('setValue', yesterday);
    $("#q_date_end").datebox('setValue', yesterday);
    doSearch();
}

function bal_doDelete() {
    var o_row = $("#dg-balance").datagrid('getSelected');
    var a_rows = $("#dg-balance").datagrid('getRows');
    for (var i = 0; i < a_rows.length; i++) {
        if (a_rows[i].order_code === o_row.order_code) {
            $("#dg-balance").datagrid('deleteRow', i);
            break;
        }
    }
}

function doSelect() {
    var a_rows = $("#dg_orders").datagrid('getChecked');
    var a_rows_balance = $("#dg-balance").datagrid('getRows');
    var b_is_same = false;
    
    for (var i = 0; i < a_rows.length; i++) {
        if (a_rows[i].eoi_order_state_enum !== ENUM_ORDER_STATE_FINISH) {
            $.messager.alert('错误', '将要纳入的订单中存在异常订单，请重新选择或者编辑处理异常', 'error');
            return;
        }
        for (var j = 0; j < a_rows_balance.length; j++) {
            if (a_rows[i].eoi_code === a_rows_balance[j].order_code) {
                b_is_same = true;
                break;
            }
        }
        if (b_is_same) {
            b_is_same = false;
            //$.messager.alert('错误', '存在相同订单', 'error');
            continue;
        }
        $('#dg-balance').datagrid('appendRow', {
            order_code: a_rows[i].eoi_code,
            order_from: a_rows[i].order_from,
            order_date: a_rows[i].order_date,
            shop_sn: a_rows[i].shop_sn,
            receive_amount: a_rows[i].receive_amount
        });
    }
}

function searchRowStyle() {
    $('#dg_orders').datagrid({
        rowStyler: function (index, row) {
            if (row.eoi_order_state_enum !== ENUM_ORDER_STATE_FINISH) {
                return 'background-color:#DDDDDD;color:#AAAAAA;';
            }
        }
    });
}

function doSearch() {
//TODO 对于更新过的绿色显示，未处理的异常单红色显示
    var s_db = $("#q_date_begin").val();
    var s_de = $("#q_date_end").val();
    var s_s = $("#q_shop").val();
    var s_f = $("#q_from").val();
    if (s_db === '' || s_de === '') {
        $.messager.alert('错误', '起止日期不可为空', 'error');
        return;
    }

    $.ajax({
        url: '../' + __s_c_name + '/getOrderList',
        type: "GET",
        data: {"db": s_db, "de": s_de, "s": s_s, "f": s_f},
        success: function (data) {
            var o_response = $.parseJSON(data);
            $("#dg_orders").datagrid("loadData", o_response);
            searchRowStyle();
        }
    });
}

function doBalance() {
    var s_db = $("#q_date_begin").val();
    var s_de = $("#q_date_end").val();
    var a_rows = $('#dg-balance').datagrid('getRows');
    var a_codes = [];
    if (s_db === '' || s_de === '') {
        $.messager.alert('错误', '起止日期不可为空', 'error');
        return;
    }
    if (a_rows.length === 0) {
        $.messager.alert('错误', '结算目标为空', 'error');
        return;
    }

    for (var i = 0; i < a_rows.length; i++) {
        a_codes.push(a_rows[i].order_code);
    }
    
    $.messager.confirm('确认', '确认要对' + s_db + '至' + s_de + '的' + a_codes.length + '个订单进行结算吗?', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/doBalance',
                type: "POST",
                data: {'db': s_db, 'de': s_de, "codes": a_codes},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                }
            });
        }
    });
}
