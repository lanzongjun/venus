function showAddWin() {
    $('#w_add_sku').window('open');
}

// 新增
function saveAddForm() {
    $('#f_add_sku').form('submit');
}

function closeAddWin() {
    $('#w_add_sku').window('close');
}

function saveEditForm() {
    $('#f_edit_sku').form('submit');
}

function closeEditWin() {
    $('#w_edit_sku').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.cs_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#w_edit_sku').window('open');
    $('#f_edit_sku').form('load', '../' + __s_c_name + '/getSkuInfo?id=' + o_row.cs_id);
}

// 查询
function doSearch() {
    var cs_code = $("#cs_code").val();
    var cs_name = $("#cs_name").val();
    var cs_description = $("#cs_description").val();
    $('#dg').datagrid('load', {
        cs_code: cs_code,
        cs_name: cs_name,
        cs_description: cs_description
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

    $('#f_add_sku').form({
        url: '../' + __s_c_name + '/addSkuInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_add_sku').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_sku').form({
        url: '../' + __s_c_name + '/editSkuInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_edit_sku').window('close');
            $('#dg').datagrid('reload');
        }
    });
})