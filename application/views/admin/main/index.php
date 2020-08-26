<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CVS管理中心</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/black/easyui.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/icon.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/demo.css") ?>">
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.easyui.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/easyui-lang-zh_CN.js") ?>"></script>
        <style>
            a:link{text-decoration: none;color: white}

            a:hover{text-decoration:none;color: white}

            a:visited{text-decoration: none;color: white}
        </style>
        <script>
            var __audio_path_order_new = "<?php echo base_url("/resource/audio/pikachu.m4a") ?>";
            var __audio_path_order_refund = "<?php echo base_url("/resource/audio/duanxin.m4a") ?>";

        </script>
    </head>
    <body class="easyui-layout">
        <div data-options="region:'north',border:false" style="height:60px;background:#333333;padding:10px;font-size:30px;">CVS Manage System</div>
        <div data-options="region:'west',split:true" style="width:150px;">            
            <div class="easyui-accordion" data-options="fit:true,border:false">                
                <div title="快捷-每日更新">
                    <a id='lnk_yj_shop_storage' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷站点库存</a>
                    <a id='lnk_goods_info_yj' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷总商品库</a>
                    <a id='lnk_temp_yj_price' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">易捷结算价格</a>
                    <a id='lnk_base_eb_ShopGoods' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">饿百店铺商品</a>
                    <a id='lnk_base_mt_ShopGoods' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">美团店铺商品</a>
                    <a id='lnk_base_mt_ShopGoods2' href="#" class="easyui-linkbutton" style="margin:10px 10px 10px 10px;" data-options="iconCls:'icon-large-my-update',size:'large',iconAlign:'top'">美团店铺商品2222</a>
                </div>
                <div title="导航">
                    <ul class="easyui-tree">
                        <li data-options="state:'closed'">
                            <span>大数据分析</span>
                            <ul>
                                <li>
                                    <span><a id='nav_market_original' href="#">对标元数据</a></span>
                                </li>
                            </ul>
                        </li>
                        <li data-options="state:'closed'">
                            <span>结算统计</span>
                            <ul>
                                <li>
                                    <span><a id='nav_balance_guide' href="#">结算向导</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_account_check_guide' href="#">对账向导</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_balance_station' href="#">站点结算记录</a></span>                            
                                </li>
                                <li>
                                    <span><a id='nav_balance_account' href="#">总结算记录</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>易捷数据</span>
                            <ul>
                                <li>
                                    <span><a id='nav_temp_cash_pool' href="#">资金池流水</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_yj_shop_storage' href="#">站点库存</a></span>
                                </li>
                                <li data-options="state:'closed'">
                                    <span>临期管理</span>
                                    <ul>                                        
                                        <li>
                                            <span><a id='nav_onsale_yj' href="#">临期促销</a></span>                            
                                        </li>
                                        <li>
                                            <span><a id='nav_temp_yj_expire' href="#">临期导入</a></span>                            
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='nav_shop_info_yj' href="#">店铺信息</a></span>
                                </li>
                                <li>
                                    <span><a id='nav_temp_yj_price' href="#">结算价格</a></span>
                                </li>
                                <li>
                                    <span><a id="nav_goods_info_yj" href="#">总商品库</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>饿百平台</span>
                            <ul>
                                <li data-options="state:'closed'">
                                    <span>订单管理</span>
                                    <ul>                                        
                                        <li>
                                            <span><a id='nav_base_OrdersInfoEB_TODO' href="#">接单</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoEB_Refund' href="#">退单</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoEB' href="#">历史订单</a></span>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='nav_base_eb_ShopGoods' href="#">门店商品</a></span>
                                </li>
                                <li>
                                    <span><a id="nav_count_bal_eb" href="#">汇总信息</a></span>
                                </li>
                                <li>
                                    <span><a id="nav_bill_info_eb" href="#">账单信息</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>美团平台</span>
                            <ul>
                                <li>
                                    <span>订单管理</span>
                                    <ul>                                        
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT_TODO' href="#">接单</a></span>
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT_Refund' href="#">退单</a></span> 
                                        </li>
                                        <li>
                                            <span><a id='nav_base_OrderInfoMT' href="#">历史订单</a></span>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='nav_base_mt_ShopGoods' href="#">门店商品</a></span>
                                </li>                                
                            </ul>
                        </li>
                        <li data-options="state:'closed'">
                            <span>京东平台</span>
                            <ul>                                
                                <li>
                                    <span>正在建设</span>                            
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="layout_center" data-options="region:'center'"></div>
    </body>
    <script src="<?php echo base_url("/resource/admin/main.js?" . rand()) ?>" type="text/javascript"></script>
</html>