const ENUM_REPAIR_NORMAL = 'normal';
const ENUM_REPAIR_TODO = 'todo';
const ENUM_REPAIR_DONE = 'done';

const ENUM_ORDER_STATE_FINISH = 'finish';

const ENUM_SEND_STATE_TODO = 'todo';
const ENUM_SEND_STATE_SUCCESS = 'success';
const ENUM_SEND_STATE_FAIL = 'fail';

var __o_mtoitd_refresh_handler;
var __o_mtoir_refresh_handler;
var __o_eboitd_refresh_handler;

function beforeOpen(){
    clearTimeout(__o_mtoitd_refresh_handler);
    clearTimeout(__o_mtoir_refresh_handler);
    clearTimeout(__o_eboitd_refresh_handler);
}

var __i_OrderMonitor_refresh_rate = 30000;
var __o_OrderMonitor_refresh_handler;

function OrderMonitor(){
    $.ajax({
        url: '../AdMTOrderInfoC/isOrderToDo',
        type: "POST",
        success: function (data) {
            var i_res = data-0;
            console.log('美团订单监控 返回状态:'+i_res);
            if (i_res === 1){
            }
            if (i_res === 2){
            }
            if (i_res === 3){
            }
        }
    });

    __o_OrderMonitor_refresh_handler = setTimeout(OrderMonitor,__i_OrderMonitor_refresh_rate);
}

$(function () {
    // 屏蔽
    //OrderMonitor();
    $('#nav_base_eb_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowShopGoods();
    });

    $('#nav_temp_yj_price').bind('click', function () {
        beforeOpen();
        doShowTempYJSPrice();
    });
    $('#nav_yj_shop_storage').bind('click', function () {
        beforeOpen();
        doShowYJShopStorage();
    });
//    $('#nav_temp_eb_order').bind('click', function () {
//        beforeOpen();
//        doShowTempEBOrder();
//    });
    $('#nav_balance_station').bind('click', function () {
        beforeOpen();
        doShowBalanceStation();
    });
    $('#goods_stock').bind('click', function () {
        beforeOpen();
        doGoodsStock();
    });
    
    $('#nav_balance_account').bind('click', function () {
        beforeOpen();
        doShowBalanceAccount();
    });
   
    $('#nav_base_OrdersInfoEB_TODO').bind('click', function () {
        beforeOpen();
        doOrderInfoEBToDo();
    });
    
    $('#nav_base_OrderInfoEB').bind('click', function () {
        beforeOpen();
        doShowEBOrdersInfo();
    });
    
    $('#nav_temp_cash_pool').bind('click', function () {
        beforeOpen();
        doShowTmpCashPool();
    });
    
    $('#nav_temp_yj_expire').bind('click', function () {
        beforeOpen();
        doShowTmpYJExpire();
    });
    
    $('#nav_onsale_yj').bind('click', function () {
        beforeOpen();
        doShowOnSaleYJ();
    });
    
    $('#nav_balance_guide').bind('click', function () {
        beforeOpen();
        doBalanceGuide();
    });
    
    $('#nav_count_bal_eb').bind('click', function () {
        beforeOpen();
        doCountBalEB();
    });
    
    $('#nav_bill_info_eb').bind('click', function () {
        beforeOpen();
        doBillInfoEB();
    });
    
    $('#nav_shop_info_yj').bind('click', function () {
        beforeOpen();
        doShopInfoYJ();
    });
    
    $('#add_provider').bind('click', function () {
        beforeOpen();
        doAddProvider();
    });

    $('#add_provider_goods').bind('click', function () {
        beforeOpen();
        doAddProviderGoods();
    });

    $('#provider_goods_check').bind('click', function () {
        beforeOpen();
        doProviderGoodsCheck();
    });

    $('#provider_goods_sample').bind('click', function () {
       beforeOpen();
       doProviderGoodsSample();
    });

    $('#provider_goods_sku').bind('click', function () {
        beforeOpen();
        doProviderGoodsSku();
    });
    
    $('#nav_goods_info_yj').bind('click', function () {
        beforeOpen();
        doGoodsInfoYJ();
    });
    
    $('#nav_base_OrderInfoMT_TODO').bind('click', function () {
        beforeOpen();
        doOrderInfoMtToDo();
    });
    
    $('#nav_base_OrderInfoMT_Refund').bind('click', function () {
        beforeOpen();
        doOrderInfoMtRefund();
    });
    
    $('#nav_base_OrderInfoMT').bind('click', function () {
        beforeOpen();
        doOrderInfoMt();
    });
    
    $('#nav_base_mt_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowMTShopGoods();
    });
    
    $('#lnk_yj_shop_storage').bind('click', function () {
        beforeOpen();
        doShowYJShopStorage();
    });
    
    $('#lnk_goods_info_yj').bind('click', function () {
        beforeOpen();
        doGoodsInfoYJ();
    });
    
    $('#lnk_temp_yj_price').bind('click', function () {
        beforeOpen();
        doShowTempYJSPrice();
    });
    
    $('#lnk_base_eb_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowShopGoods();
    });
    
    $('#lnk_base_mt_ShopGoods').bind('click', function () {
        beforeOpen();
        doShowMTShopGoods();
    });
    $('#sku_list').bind('click', function () {
        beforeOpen();
        doShowSkuList();
    })
});

function doOrderInfoMtToDo(){
    $('#layout_center').panel({
        href: '../AdMTOrderInfoToDoC',
        onLoad: function () {

        }
    });
}

function doOrderInfoMtRefund(){
    $('#layout_center').panel({
        href: '../AdMTOrderRefundC',
        onLoad: function () {

        }
    });
}

function doOrderInfoMt(){
    $('#layout_center').panel({
        href: '../AdMTOrderInfoC',
        onLoad: function () {

        }
    });
}

function doGoodsInfoYJ(){
    $('#layout_center').panel({
        href: '../AdYJGoodsInfoC',
        onLoad: function () {

        }
    });
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
        href: '../ProviderGoodsController',
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

function doBillInfoEB() {
    $('#layout_center').panel({
        href: '../AdBillInfoEBC',
        onLoad: function () {

        }
    });
}

function doShopInfoYJ() {
    $('#layout_center').panel({
        href: '../AdShopInfoYJC',
        onLoad: function () {

        }
    });
}

function doCountBalEB() {
    $('#layout_center').panel({
        href: '../AdCountBalEBC',
        onLoad: function () {

        }
    });
}

function doShowOnSaleYJ() {
    $('#layout_center').panel({
        href: '../AdYJOnSaleC',
        onLoad: function () {

        }
    });
}

function doBalanceGuide() {
    $('#layout_center').panel({
        href: '../AdBalanceGuideC',
        onLoad: function () {

        }
    });
}

function doShowTmpYJExpire() {
    $('#layout_center').panel({
        href: '../AdTmpYJExpireC',
        onLoad: function () {

        }
    });
}

function doShowTmpCashPool() {
    $('#layout_center').panel({
        href: '../AdTmpCashPoolC',
        onLoad: function () {

        }
    });
}

function doShowBalanceStation(){
    $('#layout_center').panel({
        href: '../AdBalanceStationC',
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

function doShowOrderInfoEB(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoC/',
        onLoad: function () {

        }
    });
}

function doOrderInfoEBToDo(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoToDoC',
        onLoad: function () {
            
        }
    });
}

function doShowEBOrdersInfo(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoC',
        onLoad: function () {
            
        }
    });
}

function doShowOrdersInfoEB(){
    $('#layout_center').panel({
        href: '../AdEBOrderInfoC',
        onLoad: function () {
            
        }
    });
}

function doShowBalanceAccount() {
    $('#layout_center').panel({
        href: '../AdBalanceAccountC',
        onLoad: function () {

        }
    });
}

function doShowShopGoods() {
    $('#layout_center').panel({
        href: '../AdEBShopGoodsC',
        onLoad: function () {

        }
    });
}

function doShowMTShopGoods() {
    $('#layout_center').panel({
        href: '../AdMTShopGoodsC',
        onLoad: function () {

        }
    });
}

function doShowTempYJSPrice() {
    $('#layout_center').panel({
        href: '../AdYJSPriceC',
        onLoad: function () {

        }
    });
}

function doShowYJShopStorage() {
    $('#layout_center').panel({
        href: '../AdYJShopStorageC',
        onLoad: function () {

        }
    });
}

function doShowTempEBOrder() {
    $('#layout_center').panel({
        href: '../AdTmpOrderEBC',
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

