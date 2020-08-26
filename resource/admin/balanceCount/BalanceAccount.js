
$(function () {
    init();
});

//此全自动结算功能废弃
function doBalance(){
    var s_date = $("#b_date").val();
    if (s_date === '') {$.messager.alert('信息', '请选择结算日期', 'info');return;}
    ajaxLoading();
    $.ajax({
        url: '../'+__s_c_name+'/doBalance/',
        type: "POST",
        data: {"dt": s_date},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state === true) {
                $.messager.alert('信息', o_response.msg, 'info');                
            } else {                
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $("#dg").datagrid('reload');
            ajaxLoadEnd();
        }
    });
}

//此全自动结算功能废弃
function doReBalance(){    
    var s_date = $("#b_date").val();
    if (s_date === '') {$.messager.alert('信息', '请选择结算日期', 'info');return;}
    ajaxLoading();
    $.ajax({
        url: '../'+__s_c_name+'/doReBalance/',
        type: "POST",
        data: {"dt": s_date},
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state === true) {
                $.messager.alert('信息', o_response.msg, 'info');                
            } else {                
                $.messager.alert('错误', o_response.msg, 'error');
            }
            $("#dg").datagrid('reload');
            ajaxLoadEnd();
        }
    });
}

function init() {
    $('#btn_balance').bind('click', function () {
        doBalance();
    });
    
    $('#btn_re_balance').bind('click', function () {
        doReBalance();
    });
    
    $("#dg").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            loadOnSaleAB(row.ba_id);
            loadOnSaleYJ(row.ba_id);
            loadDelay(row.ba_id);
            loadErr(row.ba_id);
        }
    });
}

function loadOnSaleAB(s_bi){
    $("#dg-south1").datagrid("options").url = '../'+__s_c_name+'/getABList/';
    $('#dg-south1').datagrid('load', {
        bi: s_bi
    });
}

function loadOnSaleYJ(s_bi){
    $("#dg-south2").datagrid("options").url = '../'+__s_c_name+'/getYJList/';
    $('#dg-south2').datagrid('load', {
        bi: s_bi
    });
}

function loadDelay(s_bi){
    $("#dg-south3").datagrid("options").url = '../'+__s_c_name+'/getDelayList/';
    $('#dg-south3').datagrid('load', {
        bi: s_bi
    });
}

function loadErr(s_bi){
    $("#dg-east").datagrid("options").url = '../'+__s_c_name+'/getErrList/';
    $('#dg-east').datagrid('load', {
        bi: s_bi
    });
} 

function formatSName(val,row){
    return '易捷('+val+'站)';
}

function formatSDetail(val,row){
    return '<button onclick="getShopDetailList('+val+','+row.bad_bs_org_sn+')">详情</button>';
}

function getShopDetailList(bad_id, bad_bs_org_sn) {
    $('#layout_center').panel({
        href: '../AdBalanceStationC/?bi='+bad_id+'&oi='+bad_bs_org_sn,
        onLoad: function () {
            
        }
    });
}