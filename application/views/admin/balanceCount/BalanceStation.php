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
        <div id="layout_room" class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',title:'站点结算记录'">
                <table id="dg_bal_shop" class="easyui-datagrid" toolbar="#d_bs_search_toolbar" data-options="fit:true,rownumbers:true,singleSelect:false,method:'get',url:'../AdBalanceStationC/getList/'">
                    <thead>
                        <tr>
                            <th data-options="field:'ck',checkbox:true"></th>
                            <th data-options="width:55,align:'center',field:'bas_mail_file',formatter:formatMail">预览</th>
                            <th data-options="width:100,align:'center',field:'date_begin'">开始日期</th>
                            <th data-options="width:100,align:'center',field:'date_end'">结束日期</th>
                            <th data-options="width:80,align:'center',field:'bas_bs_org_sn'">组织编码</th>
                            <th data-options="width:100,align:'center',field:'bas_bs_shop_name',formatter:formatSName">商户名称</th>
                            <th data-options="width:60,align:'center',field:'bas_order_count'">订单数</th>
                            <th data-options="width:60,align:'center',field:'bas_order_amount'">金额</th>
                            <th data-options="width:150,align:'center',field:'bas_mail_to'">邮件地址</th>
                            <th data-options="width:80,align:'center',field:'bas_mail_send',formatter:formatSendState">发送状态</th>                            
                            <th data-options="width:180,align:'center',field:'ba_balance_time'">结算时间</th>                            
                        </tr>
                    </thead>
                </table>
                <div id="d_bs_search_toolbar">
                    <div>
                        <input id="q_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <input id="q_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <input id="q_shop" class="easyui-combobox" labelWidth="45" style="width:150px;" label='店铺:' labelPosition='left' data-options="url:'../AdTmpYJExpireC/getShopList', method:'get',valueField:'id', textField:'text'" />
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <select id="q_b_state" class="easyui-combobox" labelWidth="75" style="width:150px;" label='邮件状态:' labelPosition='left'>
                            <option value="all" selected="true">所有</option><option value="todo">未发</option>
                            <option value="success">成功</option><option value="fail">失败</option>
                        </select>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_search" iconCls='icon-search' data-options="disabled:true" href="#" class="easyui-linkbutton">查询</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_mail" iconCls='icon-man' href="#" class="easyui-linkbutton">发送邮件</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                    </div>
                </div>
            </div>
            <div id="d_mail_preview" data-options="region:'east',hideCollapsedContent:false,collapsed:true,title:'邮件预览',split:true" style="width:850px"></div>
            <div data-options="region:'south',hideCollapsedContent:false,collapsed:true,title:'站点结算详情'" style="height:300px">
                <table id="dg_bal_detail" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="width:80,align:'center',field:'bad_bs_org_sn'">组织编码</th>
                            <th data-options="width:220,align:'center',field:'bad_bs_shop_name'">商户名称</th>
                            <th data-options="width:150,align:'center',field:'bad_pay_account'">付款方账号</th>
                            <th data-options="width:130,align:'center',field:'bad_bgs_barcode'">商品条形码</th>
                            <th data-options="width:50,align:'center',field:'bad_bbp_settlement_price'">单价</th>
                            <th data-options="width:50,align:'center',field:'bad_count'">数量</th>
                            <th data-options="width:50,align:'center',field:'account_count'">金额</th>
                            <th data-options="width:400,align:'center',field:'bad_bgs_name'">商品名称</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 30px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/balanceCount/BalanceStation.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
