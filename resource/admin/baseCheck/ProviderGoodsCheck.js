function showAddWin() {
    $('#d_add_provider_goods_check').window('open');
}

// 新增
function saveAddForm() {
    $('#f_add_provider_goods_check').form('submit');
}

function closeAddWin() {
    $('#d_add_provider_goods_check').window('close');
}

function saveEditForm() {
    $('#f_edit_provider_goods_check').form('submit');
}

function closeEditWin() {
    $('#d_edit_provider_goods_check').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg2").datagrid('getSelected');
    if (!o_row || !o_row.pgcd_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_provider_goods_check').window('open');
    $('#f_edit_provider_goods_check').form('load', '../' + __s_c_name + '/getProviderGoodsCheckDetailInfo?id=' + o_row.pgcd_id);
}

// 库存校验
function showReloadWin() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.pgc_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将覆盖改店铺现有商品库存，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/reloadGoodsCheck',
                type: "POST",
                data: {"id": o_row.pgc_id},
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

// 删除
function showRemoveWin() {
    var o_row = $("#dg2").datagrid('getSelected');
    console.log(o_row);
    if (!o_row || !o_row.pgcd_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除盘点记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteGoodsCheckDetail',
                type: "POST",
                data: {"id": o_row.pgcd_id},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $('#dg2').datagrid('reload');
                }
            });
        }
    });
}

$(function () {
    init();
});

function init() {
    $('#btn_add').bind('click', function () {
        showAddWin();
    });
    $('#btn_edit').bind('click', function () {
        showEditWin();
    });
    $('#btn_remove').bind('click', function () {
        showRemoveWin();
    });
    $('#btn_reload').bind('click', function () {
        showReloadWin();
    });
    $('#btn_search').bind('click',function (){
        doSearch();
    });

    $("#dg").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            var p = $("#layout_room").layout("panel", "east")[0].clientWidth;
            if (p <= 0) {
                $('#layout_room').layout('expand', 'east');
            }
            loadDetailData(row.pgc_id);
        }
    });

    $('#f_add_provider_goods_check').form({
        url: '../' + __s_c_name + '/addGoodsCheck',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_provider_goods_check').window('close');
            $('#dg').datagrid('reload');
            $('#dg2').datagrid('reload');
        }
    });

    $('#f_edit_provider_goods_check').form({
        url: '../' + __s_c_name + '/editGoodsCheck',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_provider_goods_check').window('close');
            $('#dg').datagrid('reload');
            $('#dg2').datagrid('reload');
        }
    });
}

function doSearch(){
    var s_db = $('#q_date_begin').val();
    var s_de = $('#q_date_end').val();
    var s_sid = $('#q_shop').combobox('getValue');

    $('#dg').datagrid('load', {
        s_db: s_db,
        s_de: s_de,
        s_sid: s_sid
    });
}

function loadDetailData(pgc_id) {
    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadDetailData/';
    $('#dg2').datagrid('load', {
        pgc_id: pgc_id
    });
}

