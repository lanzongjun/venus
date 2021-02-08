function initNavCache(){
    $('#sys_root').tree({
        url:'../Admin/loadNav',
        onLoadSuccess:function(node,data){
            console.log(data);
            _init_tree_node_bind();
        }
    });
}

function __tree_node_bind(s_node_id){
    var o_node = $('#sys_root').tree('find', s_node_id);
    if (o_node){
        // if (o_node.bnn_lnk-0 === 1){
        //     __lnk_node_bind('lnk_'+s_node_id,o_node.bnn_lnk_name,o_node.bnn_url);
        // }
        $(o_node.target).bind('click', function () {
            //beforeOpen();
            $('#layout_center').panel({
                href: '../'+o_node.url,
                onLoad: function () {}
            });
        });
    }
}

$(function () {

    initNavCache();

});

function _init_tree_node_bind(){

    __tree_node_bind('add_provider');

    __tree_node_bind('add_provider_goods');

    __tree_node_bind('provider_goods_check');

    __tree_node_bind('provider_goods_sample');

    __tree_node_bind('provider_goods_sku');

    __tree_node_bind('goods_sale_offline');

    __tree_node_bind('goods_loss');

    __tree_node_bind('goods_stock');

    __tree_node_bind('goods_change');

    __tree_node_bind('goods_staff_meal');

    __tree_node_bind('goods_exception');

    __tree_node_bind('goods_sale_online');

    __tree_node_bind('goods_sale_online_summary');

    __tree_node_bind('base_stock');

    __tree_node_bind('sku_list');

    __tree_node_bind('repertory_list');

    __tree_node_bind('finance_account_list');

}



function doAddProvider() {
    $('#layout_center').panel({
        href: '../ProviderController',
        onLoad: function () {

        }
    });
}

function doAddProviderGoods() {
    $('#layout_center').panel({
        href: '../ProviderGoodsController',
        onLoad: function () {

        }
    });
}

function doProviderGoodsCheck() {
    $('#layout_center').panel({
        href: '../ProviderGoodsCheckController',
        onLoad: function () {

        }
    })
}

function doProviderGoodsSample() {
    $('#layout_center').panel({
        href: '../ProviderGoodsSampleController',
        onLoad: function () {

        }
    })
}

function doProviderGoodsSku() {
    $('#layout_center').panel({
        href: '../ProviderGoodsSkuController',
        onLoad: function () {

        }
    })
}

function doGoodsSaleOffline(){
    $('#layout_center').panel({
        href: '../sale/GoodsSaleOfflineController',
        onLoad: function () {

        }
    });
}

function doGoodsLoss(){
    $('#layout_center').panel({
        href: '../sale/GoodsLossController',
        // method: 'get',
        // queryParams: {'type':2},
        onLoad: function () {

        }
    });
}

function doGoodsStock() {
    $('#layout_center').panel({
        href: '../sale/GoodsStockController',
        onLoad: function () {

        }
    });
}

function doGoodsChange() {
    $('#layout_center').panel({
        href: '../sale/GoodsChangeController',
        onLoad: function () {

        }
    });
}

function doGoodsStaffMeal() {
    $('#layout_center').panel({
        href: '../sale/GoodsStaffMealController',
        onLoad: function () {

        }
    });
}

function doGoodsSaleOnline(){
    $('#layout_center').panel({
        href: '../sale/GoodsSaleOnlineController',
        onLoad: function () {

        }
    });
}

function doGoodsSaleOnlineSummary(){
    $('#layout_center').panel({
        href: '../sale/GoodsSaleOnlineSummaryController',
        onLoad: function () {

        }
    });
}

function doGoodsException(){
    $('#layout_center').panel({
        href: '../sale/GoodsExceptionHandleController',
        onLoad: function () {

        }
    });
}

function doShowBaseStock(){
    $('#layout_center').panel({
        href: '../SaleForecastBaseStockController',
        onLoad: function () {

        }
    });
}

function doShowSkuList() {
    $('#layout_center').panel({
        href: '../CoreSkuController',
        onLoad: function () {

        }
    });
}

function doShowRepertoryList() {
    $('#layout_center').panel({
        href: '../CoreRepertoryController',
        onLoad: function () {

        }
    });
}

function doFinanceAccountList() {
    $('#layout_center').panel({
        href: '../FinanceAccountController',
        onLoad: function () {

        }
    });
}

function onOperationLogClick(){
    $('#layout_center').panel({
        href: '../OperationLogController',
        onLoad: function () {}
    });
}

function ajaxLoading() {
    $("<div class=\"datagrid-mask\"></div>").css({display: "block", width: "100%", height: $(window).height()}).appendTo("body");
    $("<div class=\"datagrid-mask-msg\"></div>").html("正在处理，请稍候。。。").appendTo("body").css({height:'40px',display: "block", left: ($(document.body).outerWidth(true) - 190) / 2, top: ($(window).height() - 45) / 2});
}
function ajaxLoadEnd() {
    $(".datagrid-mask").remove();
    $(".datagrid-mask-msg").remove();
}

function myformatter(date) {
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    var d = date.getDate();
    return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
}

function myparser(s) {
    if (!s)
        return new Date();
    var ss = (s.split('-'));
    var y = parseInt(ss[0], 10);
    var m = parseInt(ss[1], 10);
    var d = parseInt(ss[2], 10);
    if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
        return new Date(y, m - 1, d);
    } else {
        return new Date();
    }
}

function formatterDate(date) {
    var day = date.getDate() > 9 ? date.getDate() : "0" + date.getDate();
    var month = (date.getMonth() + 1) > 9 ? (date.getMonth() + 1) : "0"
        + (date.getMonth() + 1);
    return date.getFullYear() + '-' + month + '-' + day;
}

