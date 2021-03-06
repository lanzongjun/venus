function showAddWin() {
    $('#d_add_goods_loss').window('open');
    $("#add_goods_loss_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
    $('#add_goods_loss_gid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品信息:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        required:true
    });
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
    $("#edit_goods_loss_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
    $('#edit_goods_loss_gid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品信息:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        required:true
    });
    $('#f_edit_goods_loss').form('load', '../' + __s_c_name + '/getGoodsLossInfo?id=' + o_row.gl_id);

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
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var type = $('#type').val();
    var provider_goods_name = $("#provider_goods_name").val();
    $('#dg').datagrid('load', {
        start_date: start_date,
        end_date: end_date,
        type: type,
        provider_goods_name: provider_goods_name
    });
}

// 导出
function doPrint() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var type = $('#type').val();
    var provider_goods_name = $("#provider_goods_name").val();
    var a = document.createElement('a');
    a.href = '../' + __s_c_name + '/getList?is_download=1&page=1&rows=1000' +
        '&start_date=' + start_date +
        '&end_date=' + end_date +
        '&provider_goods_name=' + provider_goods_name +
        '&type=' + type;
    $("body").append(a);  // 修复firefox中无法触发click
    a.click();
    $(a).remove();
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
    $('#btn_print').bind('click', function () {
        doPrint();
    });

    $('#f_add_goods_loss').form({
        url: '../' + __s_c_name + '/addGoodsLossInfo',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_goods_loss').window('close');
            //$('#f_add_goods_loss').form('clear');
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
            //$('#f_edit_goods_loss').form('clear');
            $('#dg').datagrid('reload');
        }
    });
});