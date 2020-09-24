

// 查询
function doSearch() {
    var shop_id = $("#shop_id").val();
    var provider_id = $("#provider_id").val();
    var goods_name = $("#goods_name").val();
    $('#dg').datagrid('load', {
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