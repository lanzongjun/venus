var tb_detail = [{
        text: '添加商品',
        iconCls: 'icon-add',
        handler: function () {
            $('#w_add_detail_ele').window('open');
            $('#w_add_sgoods').combobox('clear');
            var s_eoi_code = $("#f_edit_eoi_code").val();
            var s_shop_id = $('#f_edit_shop_id').val();
            $('#w_add_sgoods').combobox('reload', '../AdEBShopGoodsC/getShopGoods?sid=' + s_shop_id);
            $("#f_add_detail_ele").form('load', {
                eod_eoi_code: s_eoi_code
            });
        }
    }, {
        text: '删除商品',
        iconCls: 'icon-remove',
        handler: function () {
            var o_row = $('#dg_order_detail').datagrid('getSelected');
            var s_id = o_row.eod_id;
            var s_gname = o_row.eod_goods_name;
            $.messager.prompt('确认', "商品\"" + s_gname + "\"删除后将影响结算结果及对账!<br/>如果要继续此操作，请输入删除原因", function (r) {
                if (r) {
                    //异步请求
                    $.ajax({
                        url: '../AdEBOrderInfoC/delDetail/',
                        type: "POST",
                        data: {"s_id": s_id, "s_memo": r},
                        success: function (data) {
                            var o_response = $.parseJSON(data);
                            if (o_response.state) {
                                $.messager.alert('信息', o_response.msg, 'info');
                            } else {
                                $.messager.alert('错误', o_response.msg, 'error');
                            }
                            $('#dg_order_detail').datagrid('reload');
                        }
                    });
                }
            });
        }
    }];

function initEdit() {
    $('#btn_edit').bind('click', function () {
        doEdit();
    });

    $('#f_add_detail_ele').form({
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $('#w_add_detail_ele').window('close');
            $('#dg_order_detail').datagrid('reload');
        }
    });
    $('#f_edit_detail_ele').form({
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $('#w_edit_detail_ele').window('close');
            $('#dg_order_detail').datagrid('reload');
        }
    });

    $('#f_edit_ele').form({
        url: '../AdEBOrderInfoC/editOrderInfo/',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
                doSearch();
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
        }
    });
}

function BGOE_doSaveOrder() {
    $('#f_edit_ele').submit();
}

function BGOE_doDelOrder() {
    var s_code = $("#f_edit_eoi_code").val();
    $.messager.prompt('确认', "删除订单后将影响结算结果及对账, 确认要继续此操作吗?", function (r) {
        if (r) {
            //异步请求
            $.ajax({
                url: '../AdEBOrderInfoC/delOrderInfo/',
                type: "POST",
                data: {"code": s_code, "memo": r},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    doSearch();
                }
            });
        }
    });
}

function editFormat(value, row, index) {
    return "<button onclick='doEdit(\"" + value + "\"," + index + ")'>编辑</button>";
}

function doEdit(v, i) {
    var a_rows = $('#dg_orders').datagrid('getRows');
    var o_row = a_rows[i];
    var ofrom = o_row.order_from;
    if (ofrom === 'ELE') {
        $('#w_edit_ele').window('open');
        $('#f_edit_ele').form('load', {
            code: v,
            order_date: o_row.order_date,
            order_state: o_row.eoi_order_state_enum,
            order_amount: o_row.eoi_order_amount,
            cus_pay: o_row.eoi_cus_pay,
            receive_amount: o_row.receive_amount,
            refund_state: o_row.eoi_refund_state,
            update_memo: o_row.eoi_update_memo,
            shop_id: o_row.eoi_shop_id
        });
        $("#dg_order_detail").datagrid("options").url = '../AdEBOrderInfoC/loadDetailData/';
        $('#dg_order_detail').datagrid('load', {
            ocode: v
        });
    }
}

function editDetailFormat(value, row, index) {
    return "<button onclick='showEditDetail(\"" + value + "\"," + index + ")'>编辑</button>";
}

function showEditDetail(v, i) {
    var a_rows = $('#dg_order_detail').datagrid('getRows');
    var o_row = a_rows[i];
    $('#w_edit_detail_ele').window('open');
    $('#f_edit_detail_ele').form('load', {
        eod_id: v,
        eod_buy_count: o_row.eod_buy_count,
        eod_update_memo: o_row.eod_update_memo
    });
}