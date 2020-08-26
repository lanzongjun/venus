function newStorageFormat(value, row, index) {
    var storage_old = row.sge_count - 0;
    var storage_new = value - 0;
    if (storage_new === storage_old) {
        return storage_new;
    } else if (storage_new > storage_old) {
        return "<span style='color:#EE5C42;font-weight:bolder'>" + storage_new + "↑</span>";
    } else {
        return "<span style='color:#9ACD32;font-weight:bolder'>" + storage_new + "↓</span>";
    }
}

function upFormat(value, row, index) {
    if (value === '1') {
        return "上架";
    } else {
        return "下架";
    }
}

function opfURLFormat(value, row, index) {
    var s_html = "<a href='../.." + value + "' style='font-weight:bolder;color:#0000CD;text-decoration:underline'>下载</a>";
    return s_html;
}

function shopNameFormat(value, row, index) {
    return value;
}

function updateErrNoFormat(value, row, index) {
    return value==='0' ? '成功':'<span style="font-weight:bold;color:#B22222">失败</span>';
}

function opFormat(value, row, index) {
    return '<a href="#" class="easyui-linkbutton" iconCls="icon-add" onclick="doFreezeStorage(\''+row.sge_barcode+'\')">冻结</a>';                                
}

var toolbar2 = [{
        text: '覆盖导入',
        iconCls: 'icon-add',
        handler: function () {
            coverData();
        }
    }];

$(function () {
    init();
    //预导入CSV文件
    $('#btn_do_input').bind('click', function () {
        $("#form_input").form("submit", {
            type: 'post',
            url: '../' + __s_c_name + '/uploadInfo',
            onSubmit: function () {
                $('#ebsg_win_input').window('close');
                ajaxLoading();
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                if (o_response.state === true) {
                    $('#hid_tbn').val(o_response.tbn);
                    $("#dg2").datagrid("options").url = '../' + __s_c_name + '/loadPreview/';
                    $('#dg2').datagrid('load', {
                        tbn: $('#hid_tbn').val()
                    });
                    ajaxLoadEnd();
                    $.messager.alert('信息', '预导入完成!', 'info');
                    $("#tab_box").tabs("select", 1);
                }
            }
        });
    });
});

function init() {

    $("#dg_log").datagrid({     
        onClickRow: function (index, row) {
            $('#dom_log_msg').text(row.lue_msg);
        }
    });
    $("#dg_goods_new").datagrid({
        rowStyler: function (index, row) {
            if (row.bbp_settlement_price === null) {
                return 'background-color:#FF1493;color:#000000;';
            }
        }
    });
    $("#dg").datagrid({
        rowStyler: function (index, row) {
            if (row.sge_is_freeze === '1') {
                return 'background-color:#CCCCCC;color:#333333;';
            }
        }
    });
    $('#s_shop').combobox({
        url: '../AdShopInfoYJC/getShopEbIdList',
        onLoadSuccess: function () {
            var s_oid = $('#s_shop').combobox('getValue');
            $('#dg').datagrid({url: '../' + __s_c_name + '/getList/'});
            $('#dg').datagrid('load', {
                oid: s_oid
            });
        }
    });
    $('#s_log_shop').combobox({url: '../AdShopInfoYJC/getShopEbIdList'});

    $('#ebsg_win_input').window({
        onOpen: function () {
            $('#dom_shop_id').combobox({
                url: '../AdShopInfoYJC/getShopEbIdList',
                onLoadSuccess: function () {
                    var s_oid = $('#s_shop').combobox('getValue');
                    $('#dom_shop_id').combobox('setValue', s_oid);
                },
                onChange: function (newValue, oldValue) {
                    $('#s_shop').combobox('setValue', newValue);
                }
            });
        }
    });

    $('#btn_todo_input').bind('click', function () {
        $('#ebsg_win_input').window('open');
    });

    $('#btn_search').bind('click', function () {
        var s_oid = $('#s_shop').combobox('getValue');
        var s_gname = $('#s_goods').val();
        var s_barcode = $('#s_barcode').val();
        var s_filter_storage = $('#s_filter_storage').combobox('getValue');
        var s_filter_up = $('#s_filter_up').combobox('getValue');
        $('#dg').datagrid('load', {
            oid: s_oid,
            gn: s_gname,
            bc: s_barcode,
            fs: s_filter_storage,
            fu: s_filter_up
        });
    });
    
    $('#btn_log_search').bind('click', function () {
        var s_oid = $('#s_log_shop').combobox('getValue');
        $('#dg_log').datagrid('load', {eid: s_oid});
    });
    
    $('#btn_log_clear').bind('click', function () {
        doLogClear();
    });

    $('#btn_out_pf_csv').bind('click', function () {
        doOutPfCSV();
    });

    $('#btn_refresh_storage').bind('click', function () {
        doRefreshStorage();
    });

    $('#btn_update_storage').bind('click', function () {
        doUpdateStorage();
    });

    $('#btn_sync_online').bind('click', function () {
        doSyncOnline();
    });
    
    $('#btn_sync_eb_sku_list').bind('click', function () {
        doSyncEBSkuList();
    });
    
    $('#btn_freeze_storage').bind('click', function () {
        doFreezeStorage();
    });
    
    $('#btn_unfreeze_storage').bind('click', function () {
        doUnfreezeStorage();
    });
}

function doFreezeStorage(){
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.sge_barcode) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_barcode = o_row.sge_barcode;
    var s_gname = o_row.sge_gname;
    $.messager.confirm('确认', '是否冻结所有店铺['+s_barcode+':'+s_gname+']商品的库存？', function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/doFreezeStorage',
                type: "POST",
                data: {'bc': s_barcode, 'gn': s_gname},
                success: function (data) {
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:'库存冻结结果',
                        msg:'受影响记录数:'+o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#dg").datagrid('reload');
                }
            });
        }
    });
}

 function doUnfreezeStorage() {
    var o_row = $("#dg").datagrid('getSelected');
    if (!o_row || !o_row.sge_barcode) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    var s_barcode = o_row.sge_barcode;
    var s_gname = o_row.sge_gname;
    $.messager.confirm('确认', '是否解冻所有店铺['+s_barcode+':'+s_gname+']商品的库存？', function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/doUnfreezeStorage',
                type: "POST",
                data: {'bc': s_barcode},
                success: function (data) {
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:'库存解冻结果',
                        msg:'受影响记录数:'+o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#dg").datagrid('reload');
                }
            });
        }
    }); 
 }

function doSyncEBSkuList() {
    var s_eid = $('#s_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncEBSkuListAll();
    }else{
        doSyncEBSkuListSingle();
    }
}

function doSyncEBSkuListSingle(){
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据 线上饿百店铺商品信息 对[' + s_sn + '] 本地商品信息 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress_sl = $.messager.progress({
                title:'Please waiting',
                msg:'正在同步[饿了么-'+s_sn+']本地商品信息......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncSkuList',
                type: "POST",
                data: {'eid': s_eid},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    $.messager.show({
                        title:s_sn+' 同步结果',
                        msg:o_res.msg,
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#dg").datagrid('reload');
                }
            });
        }
    });
}

var __a_sync_shops_sl = [];
var __i_sync_index_sl = 0;

function doSyncEBSkuListAll(){
    var s_msg = '当前未选择任何特定店铺，将根据 线上饿百店铺商品信息 对 所有本地商品信息 进行同步，是否继续此操作？';    
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllSyncShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops_sl = $.parseJSON(data);
                    $("#tab_box").tabs("select", 0);
                    doSyncESkuList();
                }
            });
        }
    });
}

var win_progress_sl = null;

function doSyncESkuList() {
    $.messager.progress('close');
    if (__i_sync_index_sl >= __a_sync_shops_sl.length){return ;}
    var o_data = __a_sync_shops_sl[__i_sync_index_sl++];
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress_sl = $.messager.progress({
        title:'Please waiting',
        msg:'正在同步[饿了么-'+s_shop_name+']本地商品信息......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncSkuList',
        type: "POST",
        data: {'eid': s_eid},
        success: function (data) {
            var o_res = $.parseJSON(data);
            $.messager.show({
                title:'[饿了么-'+s_shop_name+'] 同步结果',
                msg:o_res.msg,
                timeout:5000,
                showType:'slide'
            });
            $("#dg").datagrid('reload');
            doSyncESkuList();
        }
    });
}

function doSyncOnline() {
    var s_eid = $('#s_shop').combobox('getValue');
    if (s_eid === '') {
        doSyncAll();
    }else{
        doSyncSingle();
    }
}

function doSyncSingle() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    if (!s_eid || s_eid === '') {
        $.messager.alert('错误', '请选择一个店铺后，在进行此操作', 'error');
        return;
    }    
    $.messager.confirm('确认', '即将根据 站点库存 对[' + s_sn + '] 饿百线上库存 进行同步，是否继续此操作？', function (r) {
        if (r) {
            win_progress = $.messager.progress({
                title:'Please waiting',
                msg:'正在更新[饿了么-易捷'+s_sn+'站店]线上库存......'
            });
            $.ajax({
                url: '../' + __s_c_name + '/syncOnlineStorage',
                type: "POST",
                data: {'eid': s_eid},
                success: function (data) {
                    $.messager.progress('close');
                    var o_res = $.parseJSON(data);
                    var i_suc = o_res.suc;
                    var i_fail = o_res.fail;
                    var i_pages = o_res.pages;
                    var s_shop_name = o_res.shop_name;
                    $.messager.show({
                        title:s_shop_name+' 更新结果',
                        msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页',
                        timeout:5000,
                        showType:'slide'
                    });
                    $("#tab_box").tabs("select", 2);
                    $("#dg_log").datagrid('reload');
                }
            });
        }
    });
}

var __a_sync_shops = [];
var __i_sync_index = 0;

function doSyncAll(){
    var s_msg = '当前未选择任何特定店铺，将根据当前整体库存更新线上所有店铺，是否继续此操作？';    
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            $.ajax({
                url: '../' + __s_c_name + '/getAllSyncShop',
                type: "POST",
                success: function (data) {                    
                    __a_sync_shops = $.parseJSON(data);
                    $("#tab_box").tabs("select", 2);
                    doSyncShop();
                }
            });
        }
    });
}

var win_progress = null;

function doSyncShop() {
    $.messager.progress('close');
    if (__i_sync_index >= __a_sync_shops.length){return ;}
    var o_data = __a_sync_shops[__i_sync_index++];
    var s_eid = o_data.id;
    var s_shop_name = o_data.text;
    win_progress = $.messager.progress({
        title:'Please waiting',
        msg:'正在更新[饿了么-'+s_shop_name+']线上库存......'
    });
    $.ajax({
        url: '../' + __s_c_name + '/syncOnlineStorage',
        type: "POST",
        data: {'eid': s_eid},
        success: function (data) {
            var o_res = $.parseJSON(data);
            var i_suc = o_res.suc;
            var i_fail = o_res.fail;
            var i_pages = o_res.pages;
            var s_shop_name = o_res.shop_name;
            $.messager.show({
                title:s_shop_name+' 更新结果',
                msg:'拆分为['+i_pages+']页进行上传<br/>'+'成功:'+i_suc+'页<br/>'+'失败:'+i_fail+'页',
                timeout:5000,
                showType:'slide'
            });
            $("#dg_log").datagrid('reload');
            doSyncShop();
        }
    });
}

function doLogClear(){
    $.messager.confirm('确认', '此操作将删除所有日志数据，仅保留当日数据，是否进行此操作？', function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/keepUpdateTodayLog',
                type: "POST",
                success: function (data) {
                    ajaxLoadEnd();
                    $.messager.alert('信息', "受影响记录数:"+data, 'info');
                }
            });
        }
    });    
}

function getNewGoods() {
    var s_oid = $('#s_shop').combobox('getValue');
    $("#dg_goods_new").datagrid("load",{
        'sid': s_oid
    });
    $.ajax({
        url: '../' + __s_c_name + '/outputNewGoods',
        type: "POST",
        data: {'sid': s_oid},
        success: function (data) {
            var o_response = $.parseJSON(data);
            $("#tb_ng_dl").attr('href','../../'+o_response.filepath);
        }
    });
}

function doRefreshStorage() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_eid === '') {
        s_msg = '当前未选择任何特定店铺，将根据站点库存刷新所有饿百库存，是否继续此操作？';
    } else {
        s_msg = '即将根据站点库存对[' + s_sn + ']饿百库存进行刷新，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/refreshStorage',
                type: "POST",
                data: {'oid': s_eid},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#dg").datagrid("reload");
                }
            });
        }
    });
}

function doUpdateStorage() {
    var s_eid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_eid === '') {
        s_msg = '当前未选择任何特定店铺，将更新所有饿百店铺库存，是否继续此操作？';
    } else {
        s_msg = '即将对[' + s_sn + ']饿百库存进行更新，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/updateStorage',
                type: "POST",
                data: {'oid': s_eid},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        $.messager.alert('信息', o_response.msg, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $("#dg").datagrid("reload");
                }
            });
        }
    });
}

function doOutPfCSV() {
    var s_oid = $('#s_shop').combobox('getValue');
    var s_sn = $('#s_shop').combobox('getText');
    var s_msg = '';
    if (s_oid === '') {
        s_msg = '当前未选择任何特定店铺，将导出所有饿百店铺库存平台，是否继续此操作？';
    } else {
        s_msg = '即将导出[' + s_sn + ']饿百库存平台表，是否继续此操作？';
    }
    $.messager.confirm('确认', s_msg, function (r) {
        if (r) {
            ajaxLoading();
            $.ajax({
                url: '../' + __s_c_name + '/outputPlatformCSV',
                type: "POST",
                data: {'oid': s_oid, 'sn': s_sn},
                success: function (data) {
                    ajaxLoadEnd();
                    var o_response = $.parseJSON(data);
                    if (o_response.state) {
                        var s_html = '';
                        var o_msg = [];
                        for (var i = 0; i < o_response.msg.length; i++) {
                            o_msg = o_response.msg[i];
                            s_html += "<a href='../." + o_msg.filepath + "' style='color:#0000CD;text-decoration:underline'>" + o_msg.filename + "</a><br/>";
                        }
                        $.messager.alert('信息', s_html, 'info');
                    } else {
                        $.messager.alert('错误', o_response.msg, 'error');
                    }
                    $('#dg_opfl').datagrid('reload');
                }
            });
        }
    });
}

function coverData() {
    var s_tbn = $('#hid_tbn').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将完全覆盖正式数据，是否确认覆盖导入?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            //异步请求
            $.ajax({
                url: '../' + __s_c_name + '/coverData/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                        $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                        $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $("#dg2").datagrid("loadData", {total: 0, rows: []});
                    //返回主界面
                    $("#tab_box").tabs("select", 0);

                    $("#dg").datagrid("reload");
                }
            });
        }
    });
}

