var ManagerRole = {};

ManagerRole.showAddWin = function() {
    $('#w_add_manage_role').window('open');
};

// 新增
ManagerRole.saveAddForm = function () {
    var nodes = $('#pm_power_tree').tree('getChecked');
    var a_id_list = new Array();
    var a_id_temp = [];
    for (var i = 0; i < nodes.length; i++) {
        a_id_list.push(nodes[i].id);
        // a_id_temp = PowerManager.getParentNode(nodes[i],[]);
        // a_id_list = PowerManager.appendSelectedNode(a_id_list, a_id_temp);
    }
    var s_ids = a_id_list.join();
    $('#f_pm_add_role_perms_ids').val(s_ids);
    $('#f_pm_add_role').form('submit');
};

function closeAddWin() {
    $('#w_add_manage_role').window('close');
}

function saveEditForm() {
    $('#f_edit_sku').form('submit');
}

function closeEditWin() {
    $('#w_edit_manage_role').window('close');
}

// 编辑
ManagerRole.showEditWin = function () {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }

    $.ajax({
        url: '../' + __s_c_name + '/getManageRoleInfo?id=' ,
        type: "GET",
        data: {"id": o_row.id},
        success: function (data) {
            var o_response = $.parseJSON(data);


            $('#w_edit_manage_role').window('open');
                $('#f_edit_sku').form('load', '../' + __s_c_name + '/getManageRoleInfo?id=' + o_row.id);

        }
    });



    $('#w_edit_manage_role').window('open');
    new_row =
    $('#f_edit_sku').form('load', '../' + __s_c_name + '/getManageRoleInfo?id=' + o_row.id);

    // $('#f_edit_exception_handle').form('load', {
    //     goods_id: o_row.goods_id,
    //     date: o_row.geh_date,
    //     num: o_row.geh_num,
    //     unit: o_row.geh_unit,
    //     order: o_row.geh_order,
    //     is_reduce_stock: o_row.geh_is_reduce_stock,
    //     geh_id: o_row.geh_id
    // });
};

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

// 导出
function doPrint() {
    var cs_code = $("#cs_code").val();
    var cs_name = $("#cs_name").val();
    var cs_description = $("#cs_description").val();
    var a = document.createElement('a');
    a.href = '../' + __s_c_name + '/getList?is_download=1&page=1&rows=1000' +
        '&cs_code=' + cs_code +
        '&cs_name=' + cs_name +
        '&cs_description=' + cs_description;
    $("body").append(a);  // 修复firefox中无法触发click
    a.click();
    $(a).remove();
}

$(function () {
    $('#btn_add').bind('click', function () {
        ManagerRole.showAddWin();
    });
    $('#btn_edit').bind('click', function () {
        ManagerRole.showEditWin();
    });
    $('#btn_search').bind('click', function () {
        doSearch();
    });
    $('#btn_print').bind('click', function () {
        doPrint();
    });

    // $('#f_add_sku').form({
    //     url: '../' + __s_c_name + '/addSkuInfo',
    //     type: "POST",
    //     success: function (data) {
    //         var o_response = $.parseJSON(data);
    //         if (o_response.state) {
    //             $.messager.alert('信息-更新成功', o_response.msg, 'info');
    //         } else {
    //             $.messager.alert('错误-更新失败', o_response.msg, 'error');
    //         }
    //         $('#w_add_manage_role').window('close');
    //         $('#dg').datagrid('reload');
    //     }
    // });

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
            $('#w_edit_manage_role').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_pm_add_role').form({
        url: '../' + __s_c_name + '/add',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#w_add_manage_role').window('close');
            $('#dg').datagrid('reload');
        }
    });


});