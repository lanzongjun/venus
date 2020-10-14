function showAddWin() {
    $('#d_add_exception_handle').window('open');
    $("#add_exception_handle_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
}

// 新增
function saveAddForm() {
    $('#f_add_exception_handle').form('submit');
}

function closeAddWin() {
    $('#d_add_exception_handle').window('close');
}

function saveEditForm() {
    $('#f_edit_exception_handle').form('submit');
}

function closeEditWin() {
    $('#d_edit_exception_handle').window('close');
}

// 删除
function showRemoveWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.geh_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除进货记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteExceptionHandleRecord',
                type: "POST",
                data: {"id": o_row.geh_id},
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

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.geh_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_exception_handle').window('open');
    $("#edit_exception_handle_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
    $('#f_edit_exception_handle').form('load', {
        goods_id: o_row.goods_id,
        date: o_row.geh_date,
        num: o_row.geh_num,
        unit: o_row.geh_unit,
        order: o_row.geh_order,
        is_reduce_stock: o_row.geh_is_reduce_stock,
        geh_id: o_row.geh_id
    });
}

// 查询
function doSearch() {
    var type = $("#type").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var provider_goods_name = $("#provider_goods_name").val();
    $('#dg').datagrid('load', {
        type: type,
        start_date: start_date,
        end_date: end_date,
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

    $('#f_add_exception_handle').form({
        url: '../' + __s_c_name + '/addExceptionHandle',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_exception_handle').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_exception_handle').form({
        url: '../' + __s_c_name + '/editExceptionHandle',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_exception_handle').window('close');
            $('#dg').datagrid('reload');
        }
    });
});