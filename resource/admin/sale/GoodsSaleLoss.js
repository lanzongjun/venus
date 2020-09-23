function showAddWin() {

    if (__s_type == 1) {
        $('#d_add_goods_loss1').window('open');
    } else {
        $('#d_add_goods_loss2').window('open');
    }
}

// 新增
function saveAddForm1() {
    $('#f_add_goods_loss1').form('submit');
}
function saveAddForm2() {
    $('#f_add_goods_loss2').form('submit');
}

function closeAddWin1() {
    $('#d_add_goods_loss1').window('close');
}
function closeAddWin2() {
    $('#d_add_goods_loss2').window('close');
}

function saveEditForm1() {
    $('#f_edit_goods_loss1').form('submit');
}
function saveEditForm2() {
    $('#f_edit_goods_loss2').form('submit');
}

function closeEditWin1() {
    $('#d_edit_goods_loss1').window('close');
}
function closeEditWin2() {
    $('#d_edit_goods_loss2').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gl_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }

    if (__s_type == 1) {
        $('#d_edit_goods_loss1').window('open');
        $('#f_edit_goods_loss1').form('load', '../' + __s_c_name + '/getGoodsLossInfo?id=' + o_row.gl_id);
    } else {
        $('#d_edit_goods_loss2').window('open');
        $('#f_edit_goods_loss2').form('load', '../' + __s_c_name + '/getGoodsLossInfo?id=' + o_row.gl_id);
    }

}

// 删除
function showRemoveWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gl_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除进货记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteGoodsLoss',
                type: "POST",
                data: {"id": o_row.gl_id},
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

// 查询
function doSearch() {
    var provider_goods_name = $("#provider_goods_name").val();
    $('#dg').datagrid('load', {
        provider_goods_name: provider_goods_name
    });
}

$(function () {
    $('#btn_add').bind('click', function () {
        showAddWin();
    });
    $('#btn_edit').bind('click', function () {
        showEditWin();
    });
    $('#btn_remove').bind('click', function () {
        showRemoveWin();
    });
    $('#btn_search').bind('click', function () {
        doSearch();
    });

    // $('#d_add_goods_loss').window({
    //     onBeforeClose:function(){
    //         $('#d_add_goods_loss').window('destroy');
    //         //location.reload();
    //
    //     }
    // });
    //
    // $('#d_edit_goods_loss').window({
    //     onBeforeClose:function(){
    //         $('#d_edit_goods_loss').window('destroy');
    //         //location.reload();
    //
    //     }
    // });

    $('#f_add_goods_loss1').form({
        url: '../' + __s_c_name + '/addGoodsLossInfo',
        type: "POST",
        queryParams: {type: __s_type},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_goods_loss1').window('close');
            //$('#f_add_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_add_goods_loss2').form({
        url: '../' + __s_c_name + '/addGoodsLossInfo',
        type: "POST",
        queryParams: {type: __s_type},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_goods_loss2').window('close');
            //$('#f_add_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_goods_loss1').form({
        url: '../' + __s_c_name + '/editGoodsLossInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_goods_loss1').window('close');
            //$('#f_edit_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_goods_loss2').form({
        url: '../' + __s_c_name + '/editGoodsLossInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_goods_loss2').window('close');
            //$('#f_edit_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });
});