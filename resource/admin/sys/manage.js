var Manage = {};

Manage.showAddWin = function () {
    $('#d_add_manage').window('open');

    $('#add_manage').combobox({
        url:'../Admin/getUserList',
        method:'get',
        valueField:'u_id',
        textField:'u_name',
        label: '用户列表:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        required:true
    });

    $('#add_role').combobox({
        url:'../ManageRoleController/getList',
        method:'get',
        valueField:'id',
        textField:'name',
        label: '角色列表:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        required:true
    });
};


// 新增
Manage.saveAddForm = function() {
    $('#f_add_manage').form('submit');
};

Manage.closeAddWin = function () {
    $('#d_add_manage').window('close');
};

Manage.saveEditForm = function () {
    $('#f_edit_manage').form('submit');
};

Manage.closeEditWin = function () {
    $('#d_edit_manage').window('close');
};

// 编辑
Manage.showEditWin = function () {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_manage').window('open');
    $('#edit_manage').combobox({
        url:'../Admin/getUserList',
        method:'get',
        valueField:'u_id',
        textField:'u_name',
        label: '用户列表:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        required:true
    });

    $('#edit_role').combobox({
        url:'../ManageRoleController/getList',
        method:'get',
        valueField:'id',
        textField:'name',
        label: '角色列表:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        required:true
    });
    $('#f_edit_manage').form('load', {
        manage_id: o_row.uid,
        role_id: o_row.role_id,
        status: o_row.manage_status,
        id: o_row.id
    });
};

// 查询
Manage.doSearch = function () {
    var name = $("#name").val();
    $('#dg').datagrid('load', {
        name: name
    });
};

// 删除
Manage.doRemove = function () {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除进货记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteManage',
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
};

$(function () {
    $('#btn_add').bind('click', function () {
        Manage.showAddWin();
    });
    $('#btn_edit').bind('click', function () {
        Manage.showEditWin();
    });
    $('#btn_search').bind('click', function () {
        Manage.doSearch();
    });
    $('#btn_remove').bind('click', function () {
        Manage.doRemove();
    });

    $('#f_add_manage').form({
        url: '../' + __s_c_name + '/addManage',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_manage').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_manage').form({
        url: '../' + __s_c_name + '/editManage',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_manage').window('close');
            $('#dg').datagrid('reload');
        }
    });
})