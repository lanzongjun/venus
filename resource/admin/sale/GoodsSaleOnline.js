var toolbar1 = [
    {
        text: '预导入',
        iconCls: 'icon-add',
        handler: function () {
            $('#sale_online_ele_win_input').window('open');
        }
    },
    {
        text: '修改',
        iconCls: 'icon-edit',
        handler: function () {
            showEditWin();
        }
    },
    {
        text: '删除',
        iconCls: 'icon-remove',
        handler: function () {
            showRemoveWin();
        }
    }
];

function saveEditForm() {
    $('#f_edit_goods_online').form('submit');
}

function closeEditWin() {
    $('#d_edit_goods_online').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gso_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_goods_online').window('open');
    //$('#f_edit_goods_loss').form('load', '../' + __s_c_name + '/getGoodsLossInfo?id=' + o_row.gso_id);
    $('#f_edit_goods_online').form('load', {
        shop_name: o_row.shop_name,
        date: o_row.gso_date,
        sku_code: o_row.gso_sku_code,
        sku_name: o_row.sku_name,
        num: o_row.gso_num,
        id: o_row.gso_id
    });
}

// 删除
function showRemoveWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gso_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除进货记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteGoodsOnlineInfo',
                type: "POST",
                data: {"id": o_row.gso_id},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', "受影响记录数:"+o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $('#dg').datagrid('reload');
                }
            });
        }
    });
}

$(function () {
    //预导入CSV文件

    var status = false;

    // var $btn = $('#btn_sale_online_ele_input');
    // $btn.on('click', function (e) {
    //     $('#goods_sale_online_form_input').form('submit');
    // });
    $('#btn_sale_online_ele_input').bind('click', function () {
        $('#goods_sale_online_form_input').form('submit');
    });

    $("#goods_sale_online_form_input").form({
        type: 'post',
        url: '../'+__s_c_name+'/importExcel',
        onSubmit: function () {

            if(status) {
                return false;
            }

            status = true;

            $('#sale_online_ele_win_input').window('close');
            ajaxLoading();
        },
        success: function (data) {
            status = false;
            //$('#sale_online_ele_win_input').window('destroy');
            ajaxLoadEnd();
            var o_response = $.parseJSON(data);
            if (o_response.state === true) {
                // $('#hid_tbn').val(o_response.tbn);
                // $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadPreview/';
                // $('#dg2').datagrid('load', {
                //     tbn: $('#hid_tbn').val()
                // });
                $.messager.alert('信息', '预导入完成!', 'info');
                //$('#goods_sale_online_form_input').form('clear');
                $("#dg").datagrid("reload");
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
                $("#dg").datagrid("reload");

            }
        }
    });

    $('#f_edit_goods_online').form({
        url: '../' + __s_c_name + '/editGoodsOnlineInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_goods_online').window('close');
            //$('#f_edit_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });
});

function appendData() {
    var s_tbn = $('#hid_tbn').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将追加至正式数据，是否确认追加导入?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            //异步请求
            $.ajax({
                url: '../'+__s_c_name+'/appendData/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                        $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $("#dg2").datagrid("loadData", { total: 0, rows: [] });
                    //返回主界面
                    $("#tab_box").tabs("select", 0);
                    $("#dg").datagrid("reload");
                }
            })
        }
    });
}
