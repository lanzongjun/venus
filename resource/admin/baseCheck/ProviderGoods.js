function showAddWin() {
    $('#d_add_provider_goods').window('open');
}

// 新增
function saveAddForm() {
    $('#f_add_provider_goods').form('submit');
}

function closeAddWin() {
    $('#d_add_provider_goods').window('close');
}

function saveEditForm() {
    $('#f_edit_provider_goods').form('submit');
}

function closeEditWin() {
    $('#d_edit_provider_goods').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.pg_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_provider_goods').window('open');
    $('#f_edit_provider_goods').form('load', '../' + __s_c_name + '/getProviderGoodsInfo?id=' + o_row.pg_id);
}

// 查询
function doSearch() {
    var provider_name = $("#provider_name").val();
    var provider_goods_name = $("#provider_goods_name").val();
    $('#dg').datagrid('load', {
        provider_name: provider_name,
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
    $('#btn_search').bind('click', function () {
        doSearch();
    });

    $('#f_add_provider_goods').form({
        url: '../' + __s_c_name + '/addProviderGoods',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_provider_goods').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_provider_goods').form({
        url: '../' + __s_c_name + '/editProviderGoods',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_provider_goods').window('close');
            $('#dg').datagrid('reload');
        }
    });
});