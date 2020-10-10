

// 查询
function doSearch() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    var shop_id = $("#shop_id").val();
    var provider_id = $("#provider_id").val();
    var goods_name = $("#goods_name").val();
    $('#dg').datagrid('load', {
        start_date: start_date,
        end_date: end_date,
        shop_id: shop_id,
        provider_id: provider_id,
        goods_name: goods_name
    });
}

$(function () {
    $('#btn_search').bind('click', function () {
        doSearch();
    });
});