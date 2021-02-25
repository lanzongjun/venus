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

ManagerRole.saveEditForm = function () {
    var nodes = $('#pm_power_tree').tree('getChecked');
    var a_id_list = new Array();
    var a_id_temp = [];
    for (var i = 0; i < nodes.length; i++) {
        a_id_list.push(nodes[i].id);
        // a_id_temp = PowerManager.getParentNode(nodes[i],[]);
        // a_id_list = PowerManager.appendSelectedNode(a_id_list, a_id_temp);
    }
    var s_ids = a_id_list.join();
    $('#f_pm_edit_role_perms_ids').val(s_ids);
    $('#f_pm_edit_role').form('submit');
};

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

            $('#f_pm_edit_role').form('load', {
                id: o_response.id,
                name: o_response.name,
                desc: o_response.desc,
                status: o_response.status,
                perm_list: o_response.perm_list

            });
            $('#pm_power_tree').tree({
                data: o_response.perm_list
            });
        }
    });



    // $('#w_edit_manage_role').window('open');
    // $('#f_pm_edit_role').form('load', '../' + __s_c_name + '/getManageRoleInfo?id=' + o_row.id);

    // $('#f_pm_edit_role').form('load', {
    //     id: o_row.goods_id,
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
    var name = $("#name").val();
    var desc = $("#desc").val();
    $('#dg').datagrid('load', {
        name: name,
        desc: desc
    });
}

// 删除
function doRemove() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除进货记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/delete',
                type: "POST",
                data: {"id": o_row.id},
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
    $('#btn_remove').bind('click', function () {
        doRemove();
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

    $('#f_pm_edit_role').form({
        url: '../' + __s_c_name + '/edit',
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