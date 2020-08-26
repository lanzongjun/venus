function confirmTypeFormat(value, row, index) {
    var v = value - 0;
    if (v === 1){
        return "门店";
    }
    if (v === 2){
        return "CVS手动";
    }
    if (v === 3){
        return "<span style='color:#FF0000;'>云端</span>";
    }
    return "未知";
}

function statusFormat(value, row, index) {
    if (value === '1') {
            return '待确认';
    }
    if (value === '5') {
        return '已确认';
    }
    if (value === '7') {
        return '骑士已接单';
    }
    if (value === '8') {
        return '骑士已取餐';
    }
    if (value === '9') {
        return '已完成';
    }
    if (value === '10') {
        return '已取消';
    }
    if (value === '15') {
        return '订单退款';
    }
    return '未知';
}

$(function () {
    init();
});

function init() {
    $('#btn_search').bind('click',function (){ doSearch(); });
    $("#dg").datagrid({
        onClickRow: function (index, row) { //easyui封装好的时间（被单机行的索引，被单击行的值）
            var p = $("#layout_room").layout("panel", "south")[0].clientWidth;            
            if (p <= 0) {
                $('#layout_room').layout('expand', 'south');
            }
            loadDetailData(row.order_id);            
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

function loadDetailData(s_code) {
    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadDetailData/';
    $('#dg2').datagrid('load', {
        ocode: s_code
    });
}