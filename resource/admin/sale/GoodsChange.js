function showAddWin() {
    $('#d_add_goods_change').window('open');
    $("#add_goods_change_date").datebox().datebox('calendar').calendar({
        validator : function(date){
            var now = new Date();
            var d1 = new Date(now.getFullYear(),now.getMonth(),now.getDate());
            return d1 >= date;
        }
    });
    $('#add_goods_change_gid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品信息:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400'
    });
    $('#add_goods_change_shopid').combobox({
        url:'../CoreShopController/getList?rows_only=true',
        method:'get',
        valueField:'cs_id',
        textField:'cs_name',
        label: '调度店铺:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        panelHeight:'auto'
    });
}

// 新增
function saveAddForm() {
    $('#f_add_goods_change').form('submit');
}

function closeAddWin() {
    $('#d_add_goods_change').window('close');
}

function saveEditForm() {
    $('#f_edit_goods_change').form('submit');
}

function closeEditWin() {
    $('#d_edit_goods_change').window('close');
}

// 删除
function showRemoveWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.gc_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除进货记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteGoodsChangeRecord',
                type: "POST",
                data: {"id": o_row.gc_id},
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
    console.log(o_row);
    if (!o_row || !o_row.gc_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_goods_change').window('open');
    $('#f_edit_goods_change').form('load', '../' + __s_c_name + '/getGoodsChangeInfo?id=' + o_row.gc_id);
}

// 查询
function doSearch() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var provider_goods_name = $("#provider_goods_name").val();
    $('#dg').datagrid('load', {
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

    $('#f_add_goods_change').form({
        url: '../' + __s_c_name + '/addGoodsChange',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_goods_change').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_goods_change').form({
        url: '../' + __s_c_name + '/editGoodsChange',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_goods_change').window('close');
            $('#dg').datagrid('reload');
        }
    });
})