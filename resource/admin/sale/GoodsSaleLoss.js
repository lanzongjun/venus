function showAddWin() {
    $('#d_add_goods_loss').window('open');
}

// 新增
function saveAddForm() {
    $('#f_add_goods_loss').form('submit');
}

function closeAddWin() {
    $('#d_add_goods_loss').window('close');
}

function saveEditForm() {
    $('#f_edit_goods_loss').form('submit');
}

function closeEditWin() {
    $('#d_edit_goods_loss').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gl_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_goods_loss').window('open');
    $('#f_edit_goods_loss').form('load', '../' + __s_c_name + '/getGoodsLossInfo?id=' + o_row.gl_id);
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
    $('#btn_search').bind('click', function () {
        doSearch();
    });

    $('#d_add_goods_loss').window({
        onBeforeClose:function(){
            $('#d_add_goods_loss').window('destroy');
            //location.reload();

        }
    });

    $('#d_edit_goods_loss').window({
        onBeforeClose:function(){
            $('#d_edit_goods_loss').window('destroy');
            //location.reload();

        }
    });

    $('#f_add_goods_loss').form({
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
            $('#d_add_goods_loss').window('close');
            $('#f_add_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_goods_loss').form({
        url: '../' + __s_c_name + '/editGoodsLossInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_goods_loss').window('close');
            $('#f_edit_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });
});