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
            <div title="饿百汇总信息">
                <table id="dg" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'bce_date'">账单日期</th>
                            <th data-options="width:240,align:'center',field:'bce_shop_name'">商户名称</th>
                            <th data-options="width:100,align:'center',field:'bce_shop_id'">商户ID</th>
                            <th data-options="width:50,align:'center',field:'bce_orders'">单量</th>
                            <th data-options="width:60,align:'center',field:'bce_amount'">结算金额</th>
                            <th data-options="width:60,align:'center',field:'bce_user_fee'">用户支付</th>
                            <th data-options="width:60,align:'center',field:'bce_shop_rate'">商户补贴</th>
                            <th data-options="width:60,align:'center',field:'bce_agent_rate'">代理商补贴</th>
                            <th data-options="width:60,align:'center',field:'bce_send_fee'">配送费</th>
                            <th data-options="width:60,align:'center',field:'bce_commission'">实收佣金</th>
                            <th data-options="width:60,align:'center',field:'bce_products'">菜品</th>
                            <th data-options="width:60,align:'center',field:'bce_package_fee'">餐盒费</th>
                            <th data-options="width:60,align:'center',field:'bce_baidu_rate'">平台补贴</th>
                            <th data-options="width:60,align:'center',field:'bce_cold_box_fee'">冷链加价费</th>
                            <th data-options="width:60,align:'center',field:'bce_delivery_party_fee'">众包呼单费</th>
                            <th data-options="width:60,align:'center',field:'bce_tip'">呼单小费</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div title="预览">
                <table id="dg2" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'bce_date'">账单日期</th>
                            <th data-options="width:240,align:'center',field:'bce_shop_name'">商户名称</th>
                            <th data-options="width:100,align:'center',field:'bce_shop_id'">商户ID</th>
                            <th data-options="width:50,align:'center',field:'bce_orders'">单量</th>
                            <th data-options="width:60,align:'center',field:'bce_amount'">结算金额(元)</th>
                            <th data-options="width:60,align:'center',field:'bce_user_fee'">用户支付</th>
                            <th data-options="width:60,align:'center',field:'bce_shop_rate'">商户补贴</th>
                            <th data-options="width:60,align:'center',field:'bce_agent_rate'">代理商补贴</th>
                            <th data-options="width:60,align:'center',field:'bce_send_fee'">配送费</th>
                            <th data-options="width:60,align:'center',field:'bce_commission'">实收佣金</th>
                            <th data-options="width:60,align:'center',field:'bce_products'">菜品</th>
                            <th data-options="width:60,align:'center',field:'bce_package_fee'">餐盒费</th>
                            <th data-options="width:60,align:'center',field:'bce_baidu_rate'">平台补贴</th>
                            <th data-options="width:60,align:'center',field:'bce_cold_box_fee'">冷链加价费</th>
                            <th data-options="width:60,align:'center',field:'bce_delivery_party_fee'">众包呼单费</th>
                            <th data-options="width:60,align:'center',field:'bce_tip'">呼单小费</th>
                        </tr>
                    </thead>
                </table>
                <input type="hidden" id="hid_tbn"/>
            </div>
        </div>
        <div id="cbeb_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:300px;height:200px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_饿百汇总信息.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%">
                <br/><br/>
                <a id="cbeb_btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
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
        <script src="<?php echo base_url("/resource/admin/temp/CountBalEB.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
