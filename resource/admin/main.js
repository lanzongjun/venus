$(function () {
    $('#goods_stock').bind('click', function () {
        doGoodsStock();
    });
    $('#goods_change').bind('click', function () {
        doGoodsChange();
    });

    $('#goods_sale_online_ele').bind('click', function () {
        doGoodsSaleOnline();
    });

    $('#goods_sale_offline').bind('click', function () {
        doGoodsSaleOffline();
    });

    $('#goods_loss_shop').bind('click', function () {
        doGoodsLossShop();
    });

    $('#goods_loss_order').bind('click', function () {
        doGoodsLossOrder();
    });
    
    $('#add_provider').bind('click', function () {
        doAddProvider();
    });

    $('#add_provider_goods').bind('click', function () {
        doAddProviderGoods();
    });

    $('#provider_goods_check').bind('click', function () {
        doProviderGoodsCheck();
    });

    $('#provider_goods_sample').bind('click', function () {
       doProviderGoodsSample();
    });

    $('#provider_goods_sku').bind('click', function () {
        doProviderGoodsSku();
    });

    $('#sku_list').bind('click', function () {
        doShowSkuList();
    })
});

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

function doGoodsSaleOnline(){
    $('#layout_center').panel({
        href: '../sale/GoodsSaleOnlineController',
        onLoad: function () {

        }
    });
}

function doGoodsSaleOffline(){
    $('#layout_center').panel({
        href: '../sale/GoodsSaleOfflineController',
        onLoad: function () {

        }
    });
}

function doGoodsLossShop(){
    $('#layout_center').panel({
        href: '../sale/GoodsLossController',
        method: 'get',
        queryParams: {'type':1},
        onLoad: function () {

        }
    });
}

function doGoodsLossOrder(){
    $('#layout_center').panel({
        href: '../sale/GoodsLossController',
        method: 'get',
        queryParams: {'type':2},
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

