

// 查询
function doSearch() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var provider_id = $("#provider_id").val();
    var goods_name = $("#goods_name").val();
    $('#dg').datagrid('load', {
        start_date: start_date,
        end_date: end_date,
        provider_id: provider_id,
        goods_name: goods_name
    });
}

// 导出
function doPrint() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var shop_id = $("#shop_id").val();
    var provider_id = $("#provider_id").val();
    var goods_name = $("#goods_name").val();

    var a = document.createElement('a');
    a.href = '../' + __s_c_name + '/getList?is_download=1&page=1&rows=1000' +
        '&start_date=' + start_date +
        '&end_date=' + end_date +
        '&shop_id=' + shop_id +
        '&provider_id=' + provider_id +
        '&goods_name=' + goods_name;
    $("body").append(a);  // 修复firefox中无法触发click
    a.click();
    $(a).remove();
}

$(function () {

    var yesterday = new Date(new Date().setDate(new Date().getDate()-1));

    $('#start_date').datebox("setValue", formatterDate(yesterday));
    $('#end_date').datebox("setValue", formatterDate(yesterday));


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
            start_date: $('#start_date').datebox('getValue'),
            end_date: $('#end_date').datebox('getValue')
        }
    });
});