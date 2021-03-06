function showAddWin() {
    $('#d_add_sale_offline').window('open');
    $("#add_sale_offline_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
    $('#add_sale_offline_gid').combobox({
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
    $('#f_add_sale_offline').form('submit');
}

function closeAddWin() {
    $('#d_add_sale_offline').window('close');
}

function saveEditForm() {
    $('#f_edit_sale_offline').form('submit');
}

function closeEditWin() {
    $('#d_edit_sale_offline').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gso_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_sale_offline').window('open');
    $("#edit_sale_offline_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
    $('#edit_sale_offline_gid').combobox({
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
    $('#f_edit_sale_offline').form('load', '../' + __s_c_name + '/getSaleOfflineInfo?id=' + o_row.gso_id);
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
                url: '../' + __s_c_name + '/deleteGoodsSaleOffline',
                type: "POST",
                data: {"gso_id": o_row.gso_id},
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

// 导出
function doPrint() {
    var type = $("#type").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var provider_goods_name = $("#provider_goods_name").val();
    var a = document.createElement('a');
    a.href = '../' + __s_c_name + '/getList?is_download=1&page=1&rows=1000' +
        '&start_date=' + start_date +
        '&end_date=' + end_date +
        '&type=' + type +
        '&provider_goods_name=' + provider_goods_name;
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

    $('#f_add_sale_offline').form({
        url: '../' + __s_c_name + '/addGoodsSaleOffline',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_sale_offline').window('close');
            //$('#f_add_sale_offline').form('clear');

            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_sale_offline').form({
        url: '../' + __s_c_name + '/editGoodsSaleOffline',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_sale_offline').window('close');
            //$('#f_edit_sale_offline').form('clear');

            $('#dg').datagrid('reload');
        }
    });
});