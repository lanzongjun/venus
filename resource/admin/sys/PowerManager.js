
$(function () {
    $('#f_pm_add_role').form({
        url: '../ManageRoleController/add',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息', o_response.msg, 'info');
            } else {
                $.messager.alert('错误', o_response.msg, 'error');
            }
        }
    });
});

var PowerManager = {};
PowerManager.saveAddForm = function () {
    var nodes = $('#pm_power_tree').tree('getChecked');
    var a_id_list = new Array();
    var a_id_temp = [];
    for (var i = 0; i < nodes.length; i++) {
        a_id_list.push(nodes[i].id);
        // a_id_temp = PowerManager.getParentNode(nodes[i],[]);
        // a_id_list = PowerManager.appendSelectedNode(a_id_list, a_id_temp);
    }
    var s_ids = a_id_list.join();
    console.log(s_ids);
    $('#f_pm_add_role_perms_ids').val(s_ids);
    $('#f_pm_add_role').form('submit');
};

PowerManager.closeAddWin = function () {
    $('#w_ pm_add_role').window('close');
};




PowerManager.appendSelectedNode = function (a_id_list, a_id_temp){
    for (var i=0; i<a_id_temp.length; i++){
        if (!PowerManager.isNodeExist(a_id_temp[i], a_id_list)){
            a_id_list.push(a_id_temp[i]);
        }
    }
    return a_id_list;
};

PowerManager.isNodeExist = function (id, a_id_list){
    for (var j=0; j<a_id_list.length; j++){
         if (a_id_list[j] === id){
             return true;
         }
    }
    return false;
};

PowerManager.root_id = '0';

PowerManager.getParentNode = function (o_node,a_node){
    if (!o_node){return a_node;}
    a_node.push(o_node.id);
    if (o_node.parent_id === PowerManager.root_id) {
        return a_node;
    } else {
        var parent_node = $('#pm_power_tree').tree('find', o_node.parent_id);
        return PowerManager.getParentNode(parent_node,a_node);
    }
};

