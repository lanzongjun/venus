<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/default/easyui.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/icon.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/demo.css") ?>">
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.easyui.min.js") ?>"></script>
    </head>
    <body>
        <div id="tab_box" class="easyui-tabs" data-options="fit:true">
            <div title="饿百原始订单">
                <table id="dg" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../AdTmpOrderEBC/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="width:50,align:'center',field:'id'">ID</th>
                            <th data-options="width:50,align:'center',field:'sn'">序号</th>
                            <th data-options="width:180,align:'center',field:'tcode'">订单编号</th>
                            <th data-options="width:80,align:'center',field:'tfrom'">订单来源</th>
                            <th data-options="width:180,align:'center',field:'e_code'">饿了么订单编号</th>
                            <th data-options="width:80,align:'center',field:'city'">城市</th>
                            <th data-options="width:150,align:'center',field:'shop_name'">商户名称</th>
                            <th data-options="width:120,align:'center',field:'shop_id'">商户ID</th>
                            <th data-options="width:110,align:'center',field:'deliver'">配送方式</th>
                            <th data-options="width:100,align:'center',field:'order_state'">订单状态</th>
                            <th data-options="width:80,align:'center',field:'refund_state'">退款状态</th>
                            <th data-options="width:100,align:'center',field:'order_invalid'">订单无效理由</th>
                            <th data-options="width:120,align:'center',field:'order_create'">下单时间</th>
                            <th data-options="width:120,align:'center',field:'arrive_time'">预计送达时间</th>
                            <th data-options="width:110,align:'center',field:'shop_receive'">商户接单时间</th>
                            <th data-options="width:110,align:'center',field:'order_finish'">订单完成时间</th>
                            <th data-options="width:100,align:'center',field:'order_amount'">订单总金额</th>
                            <th data-options="width:110,align:'center',field:'cus_pay'">用户实付金额</th>
                            <th data-options="width:110,align:'center',field:'shop_receive_amount'">商户应收金额</th>
                            <th data-options="width:80,align:'center',field:'commission'">平台佣金</th>
                            <th data-options="width:80,align:'center',field:'claim_settlement'">索赔状态</th>
                            <th data-options="width:80,align:'center',field:'goods_price'">商品费用</th>
                            <th data-options="width:150,align:'center',field:'goods_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'sku_code'">SKU编码</th>
                            <th data-options="width:110,align:'center',field:'goods_cusid'">商品自定义ID</th>
                            <th data-options="width:110,align:'center',field:'onsale_before'">优惠前单价</th>
                            <th data-options="width:110,align:'center',field:'onsale_after'">优惠后单价</th>
                            <th data-options="width:80,align:'center',field:'buy_count'">购买数量</th>
                            <th data-options="width:50,align:'center',field:'memo'">备注</th>
                            <th data-options="width:80,align:'center',field:'deliver_fee'">配送费</th>
                            <th data-options="width:80,align:'center',field:'deliver_free'">免配送费</th>
                            <th data-options="width:80,align:'center',field:'package_fee'">包装费</th>
                            <th data-options="width:110,align:'center',field:'subsidy_shop_promotion'">商家活动补贴</th>
                            <th data-options="width:110,align:'center',field:'is_present'">是否参加满赠活动</th>
                            <th data-options="width:110,align:'center',field:'subsidy_shop_ticket'">商家券补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_shop_packet'">商家红包补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_shop_deliver'">商家配送费补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_shop_gift'">商家礼金补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_platform_promotion'">平台活动补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_platform_ticket'">平台商家券补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_platform_packet'">平台红包补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_platform_deliver'">平台配送费补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_platform_gift'">平台礼金补贴</th>
                            <th data-options="width:110,align:'center',field:'subsidy_cus_ticket'">顾客买券补贴</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div title="预览">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                    <thead>
                        <tr>
                            <th data-options="width:50,align:'center',field:'id'">ID</th>
                            <th data-options="width:150,align:'center',field:'sn'">订单序号</th>
                            <th data-options="width:150,align:'center',field:'tcode'">订单编号</th>
                            <th data-options="width:150,align:'center',field:'tfrom'">订单来源</th>
                            <th data-options="width:150,align:'center',field:'e_code'">饿了么订单编号</th>
                            <th data-options="width:150,align:'center',field:'city'">城市</th>
                            <th data-options="width:150,align:'center',field:'shop_name'">商户名称</th>
                            <th data-options="width:150,align:'center',field:'shop_id'">商户ID</th>
                            <th data-options="width:150,align:'center',field:'deliver'">配送方式</th>
                            <th data-options="width:150,align:'center',field:'order_state'">订单状态</th>
                            <th data-options="width:150,align:'center',field:'refund_state'">退款状态</th>
                            <th data-options="width:150,align:'center',field:'order_invalid'">订单无效理由</th>
                            <th data-options="width:150,align:'center',field:'order_create'">下单时间</th>
                            <th data-options="width:150,align:'center',field:'arrive_time'">预计送达时间</th>
                            <th data-options="width:150,align:'center',field:'shop_receive'">商户接单时间</th>
                            <th data-options="width:150,align:'center',field:'order_finish'">订单完成时间</th>
                            <th data-options="width:150,align:'center',field:'order_amount'">订单总金额</th>
                            <th data-options="width:150,align:'center',field:'cus_pay'">用户实付金额</th>
                            <th data-options="width:150,align:'center',field:'shop_receive_amount'">商户应收金额</th>
                            <th data-options="width:150,align:'center',field:'commission'">平台佣金</th>
                            <th data-options="width:150,align:'center',field:'claim_settlement'">索赔状态</th>
                            <th data-options="width:150,align:'center',field:'goods_price'">商品费用</th>
                            <th data-options="width:150,align:'center',field:'goods_name'">商品名称</th>
                            <th data-options="width:150,align:'center',field:'sku_code'">SKU编码</th>
                            <th data-options="width:150,align:'center',field:'goods_cusid'">商品自定义ID</th>
                            <th data-options="width:150,align:'center',field:'onsale_before'">优惠前单价</th>
                            <th data-options="width:150,align:'center',field:'onsale_after'">优惠后单价</th>
                            <th data-options="width:150,align:'center',field:'buy_count'">购买数量</th>
                            <th data-options="width:150,align:'center',field:'memo'">备注</th>
                            <th data-options="width:150,align:'center',field:'deliver_fee'">配送费</th>
                            <th data-options="width:150,align:'center',field:'deliver_free'">免配送费</th>
                            <th data-options="width:150,align:'center',field:'package_fee'">包装费</th>
                            <th data-options="width:150,align:'center',field:'subsidy_shop_promotion'">商家活动补贴</th>
                            <th data-options="width:150,align:'center',field:'is_present'">是否参加满赠活动</th>
                            <th data-options="width:150,align:'center',field:'subsidy_shop_ticket'">商家券补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_shop_packet'">商家红包补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_shop_deliver'">商家配送费补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_shop_gift'">商家礼金补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_platform_promotion'">平台活动补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_platform_ticket'">平台商家券补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_platform_packet'">平台红包补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_platform_deliver'">平台配送费补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_platform_gift'">平台礼金补贴</th>
                            <th data-options="width:150,align:'center',field:'subsidy_cus_ticket'">顾客买券补贴</th>
                            <th data-options="width:150,align:'center',field:'express_cmp'">快递公司</th>
                            <th data-options="width:150,align:'center',field:'express_code'">快递单号</th>
                        </tr>
                    </thead>
                </table>
                <input type="hidden" id="hid_tbn"/>
            </div>
        </div>
        <div id="toeb_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_饿百订单信息.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%">
                <br/><br/>
                <a id="btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
            </form>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 40px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/temp/TmpOrderEB.js?" . rand())?>" type="text/javascript"></script>
    </body>
</html>
