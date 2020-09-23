<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Venus</title>
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
    </head>
    <body class="easyui-layout">
        <div data-options="region:'north',border:false" style="height:60px;background:#333333;padding:10px;font-size:30px;">Venus(饺非饺进销存管理系统)</div>
        <div data-options="region:'west',split:true" style="width:180px;">
            <div class="easyui-accordion" data-options="fit:true,border:true">
                <div title="导航">
                    <ul class="easyui-tree">
                        <li data-options="state:'open'">
                            <span>供应商管理</span>
                            <ul>
                                <li>
                                    <span><a id='add_provider' href="#">添加供应商</a></span>
                                </li>
                            </ul>
                        </li>
                        <li data-options="state:'open'">
                            <span>商品管理</span>
                            <ul>
                                <li>
                                    <span><a id='add_provider_goods' href="#">添加商品</a></span>
                                </li>
                                <li>
                                    <span><a id='provider_goods_check' href="#">商品盘点</a></span>
                                </li>
                                <li>
                                    <span><a id='provider_goods_sample' href="#">商品取样</a></span>
                                </li>
                                <li>
                                    <span><a id='provider_goods_sku' href="#">商品关联</a></span>
                                </li>
                            </ul>
                        </li>
                        <li data-options="state:'open'">
                            <span>销售管理</span>
                            <ul>
                                <li>
                                    <span><a id='goods_sale_offline' href="#">线下销售</a></span>
                                </li>
                                <li>
                                    <span>损耗</span>
                                    <ul>
                                        <li>
                                            <span><a id='goods_loss_shop' href="#">店内破损</a></span>
                                        </li>
                                        <li>
                                            <span><a id='goods_loss_order' href="#">退单</a></span>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <span><a id='goods_stock' href="#">进货</a></span>
                                </li>
                                <li>
                                    <span><a id='goods_change' href="#">调度</a></span>
                                </li>
                                <li>
                                    <span><a id='goods_staff_meal' href="#">员工餐</a></span>
                                </li>
                                <li data-options="state:'open'">
                                    <span>线上销售</span>
                                    <ul>
                                        <li>
                                            <span><a id='goods_sale_online' href="#">销售记录</a></span>
                                        </li>
                                        <li>
                                            <span><a id='goods_sale_online_summary' href="#">销售合计</a></span>
                                        </li>
                                    </ul>
                                </li>

                                <li data-options="state:'open'">
                                    <span>异常订单</span>
                                    <ul>
                                        <li>
                                            <span><a id='goods_exception_claim' href="#">索赔单</a></span>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>销售预测</span>
                            <ul>
                                <li>
                                    <span><a id='base_stock' href="#">基于库存</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>SKU管理</span>
                            <ul>
                                <li>
                                    <span><a id="sku_list" href="#">sku列表</a></span>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <span>库存管理</span>
                            <ul>
                                <li>
                                    <span><a id="repertory_list" href="#">库存列表</a></span>
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