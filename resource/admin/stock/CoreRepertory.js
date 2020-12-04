

// 查询
function doSearch() {
    var select_date = $("#select_date").val();
    var provider_id = $("#provider_id").val();
    var goods_name = $("#goods_name").val();
    $('#dg').datagrid('load', {
        select_date: select_date,
        provider_id: provider_id,
        goods_name: goods_name
    });
}

// 导出
function doPrint() {
    var select_date = $("#select_date").val();
    var shop_id = $("#shop_id").val();
    var provider_id = $("#provider_id").val();
    var goods_name = $("#goods_name").val();

    var a = document.createElement('a');
    a.href = '../' + __s_c_name + '/getList?is_download=1&page=1&rows=1000' +
        '&select_date=' + select_date +
        '&shop_id=' + shop_id +
        '&provider_id=' + provider_id +
        '&goods_name=' + goods_name;
    $("body").append(a);  // 修复firefox中无法触发click
    a.click();
    $(a).remove();
}

function changeColor(value,row,index) {
    if (value !== '--') {
        return 'background-color:#6293BB;color:#fff;';
    }
}

$(function () {

    var yesterday = new Date(new Date().setDate(new Date().getDate()-1));

    $('#select_date').datebox("setValue", formatterDate(yesterday));

    $('#btn_search').bind('click', function () {
        doSearch();
    });

    $('#btn_print').bind('click', function () {
        doPrint();
    });

    $('#dg').datagrid({
        url:'../' + __s_c_name + '/getList',
        method:'GET',
        queryParams:{
            select_date: $('#select_date').datebox('getValue')
        }
    });
});