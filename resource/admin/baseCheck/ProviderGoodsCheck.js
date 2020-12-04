function showAddWin() {
    $('#d_add_provider_goods_check').window('open');
}

function showAddWinDetail() {
    $('#d_add_provider_goods_check_detail').window('open');
    $('#add_provider_goods_check_gid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品名称:',
        labelPosition: 'left',
        labelWidth:'90',
        width:'300'
    });
}

// 新增
function saveAddForm() {
    $('#f_add_provider_goods_check').form('submit');
}

function saveAddFormDetail() {
    var o_row = $("#dg").datagrid('getSelected');
    $('#f_add_provider_goods_check_detail').form('submit', {

        url: '../' + __s_c_name + '/addGoodsCheckDetail',
        type: "POST",
        queryParams: {
            pgc_id: o_row.pgc_id
        },


        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_provider_goods_check_detail').window('close');
            // $('#dg').datagrid('reload');
            $('#dg2').datagrid('reload');
        }
    });

    // $('#f_add_provider_goods_check_detail').form('submit');

}

function closeAddWin() {
    $('#d_add_provider_goods_check').window('close');
}

function closeAddWinDetail() {
    $('#d_add_provider_goods_check_detail').window('close');
}

function saveEditFormDetail() {
    $('#f_edit_provider_goods_check_detail').form('submit');
}

function closeEditWinDetail() {
    $('#d_edit_provider_goods_check_detail').window('close');
}

// 编辑
function showEditWinDetail() {
    var o_row = $("#dg2").datagrid('getSelected');
    if (!o_row || !o_row.pgcd_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_provider_goods_check_detail').window('open');
    $('#edit_provider_goods_check_gid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品名称:',
        labelPosition: 'left',
        labelWidth:'90',
        width:'300'
    });
    $('#f_edit_provider_goods_check_detail').form('load', '../' + __s_c_name + '/getProviderGoodsCheckDetailInfo?id=' + o_row.pgcd_id);
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
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.pgc_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $.messager.confirm('确认', '此操作将删除盘点记录，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/deleteGoodsCheck',
                type: "POST",
                data: {"id": o_row.pgc_id},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $('#dg').datagrid('reload');
                    $('#dg2').datagrid('reload');
                }
            });
        }
    });
}

// 删除详情
function showRemoveWinDetail() {
    var o_row = $("#dg2").datagrid('getSelected');
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

// 查询
function doSearch(){
    var s_db = $('#q_date_begin').val();
    var s_de = $('#q_date_end').val();
    // var s_sid = $('#q_shop').combobox('getValue');

    $('#dg').datagrid('load', {
        start_date: s_db,
        end_date: s_de
    });
}

$(function () {
    init();
});

function init() {

    $("#dg").datagrid({
        url: '../' + __s_c_name + '/getList',
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            var p = $("#layout_room").layout("panel", "east")[0].clientWidth;
            if (p <= 0) {
                $('#layout_room').layout('expand', 'east');
            }
            loadDetailData(row.pgc_id);
        }
    });

    $('#btn_add').bind('click', function () {
        showAddWin();
    });
    $('#btn_add_detail').bind('click', function () {
        showAddWinDetail();
    });
    $('#btn_edit_detail').bind('click', function () {
        showEditWinDetail();
    });
    $('#btn_remove').bind('click', function () {
        showRemoveWin();
    });
    $('#btn_remove_detail').bind('click', function () {
        showRemoveWinDetail();
    });
    $('#btn_reload').bind('click', function () {
        showReloadWin();
    });
    $('#btn_search').bind('click',function (){
        doSearch();
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

    // $('#f_add_provider_goods_check_detail').form({
    //     url: '../' + __s_c_name + '/addGoodsCheckDetail',
    //     type: "POST",
    //
    //     success: function (data) {
    //         var o_response = $.parseJSON(data);
    //         if (o_response.state) {
    //             $.messager.alert('信息-更新成功', o_response.msg, 'info');
    //         } else {
    //             $.messager.alert('错误-更新失败', o_response.msg, 'error');
    //         }
    //         $('#d_add_provider_goods_check_detail').window('close');
    //         $('#dg').datagrid('reload');
    //         $('#dg2').datagrid('reload');
    //     }
    // });

    $('#f_edit_provider_goods_check_detail').form({
        url: '../' + __s_c_name + '/editGoodsCheck',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_provider_goods_check_detail').window('close');
            $('#dg').datagrid('reload');
            $('#dg2').datagrid('reload');
        }
    });
}

function loadDetailData(pgc_id) {
    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadDetailData';
    $('#dg2').datagrid('load', {
        pgc_id: pgc_id
    });
}

