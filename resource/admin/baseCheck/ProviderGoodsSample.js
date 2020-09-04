function showAddWin() {
    $('#w_add_shop').window('open');
}

// 新增
function saveAddForm() {
    $('#f_add_shop').form('submit');
}

function closeAddWin() {
    $('#w_add_shop').window('close');
}

function saveEditForm() {
    $('#f_edit_shop').form('submit');
}

function closeEditWin() {
    $('#w_edit_shop').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.pgs_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#w_edit_shop').window('open');
    $('#f_edit_shop').form('load', '../' + __s_c_name + '/getProviderGoodsSampleInfo?id=' + o_row.pgs_id);
}

// 查询
function doSearch() {
    var provider_name = $("#provider_name").val();
    $('#dg').datagrid('load', {
        provider_name: provider_name
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

    $('#f_add_shop').form({
        url: '../' + __s_c_name + '/addProviderGoodsSampleInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            console.log(22222);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_add_shop').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_shop').form({
        url: '../' + __s_c_name + '/editProviderGoodsSampleInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_edit_shop').window('close');
            $('#dg').datagrid('reload');
        }
    });
});