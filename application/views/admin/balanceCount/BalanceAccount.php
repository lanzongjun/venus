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
            <div data-options="region:'center',title:'易捷总结算表'">
                <table id="dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/'">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'ba_balance_date_begin'">结算起始日期</th>
                            <th data-options="width:100,align:'center',field:'ba_balance_date_end'">结算结束日期</th>
                            <th data-options="width:70,align:'center',field:'ba_balance_eb'">饿百结算</th>
                            <th data-options="width:70,align:'center',field:'ba_balance_mt'">美团结算</th>
                            <th data-options="width:70,align:'center',field:'ba_balance_jd'">京东结算</th>
                            <th data-options="width:70,align:'center',field:'ba_balance_yj'">易捷结算</th>
                            <th data-options="width:100,align:'center',field:'ba_cpd_remaining_sum'">资金池余额</th>
                            <th data-options="width:160,align:'center',field:'ba_cpd_time'">资金池日期</th>
                            <th data-options="width:100,align:'center',field:'ba_cpd_bill_code'">资金池流水号</th>
                            <th data-options="width:160,align:'center',field:'ba_balance_time'">结算时间</th>
                        </tr>
                    </thead>
                </table>
                <div id="dom_toolbar1">
                    <div>
                        <input id="q_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <input id="q_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_search" href="#" class="easyui-linkbutton" iconCls='icon-search' data-options="disabled:true">查询</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>                        
                        <!--<a id="btn_re_balance" href="#" class="easyui-linkbutton" iconCls='icon-add'>重新结算</a>-->
                    </div>
                </div>
            </div>
            <div data-options="region:'east',title:'异常结算',collapsed:false,split:true" style="width:280px;">
                <table id="dg-east" class="easyui-datagrid" data-options="singleSelect:true,border:false,method:'get',split:true,fit:true" style="width:25%">
                    <thead>
                        <tr>
                            <th data-options="width:100,align:'center',field:'bs_shop_sn',formatter:formatSName">店铺名称</th>
                            <th data-options="width:150,align:'center',field:'bae_error_reason'">原因</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'south',collapsed:false,split:true" style="height:300px;">
                <div id="layout_room" class="easyui-layout" data-options="fit:true,border:false">
                    <div data-options="region:'center',collapsed:false,split:true" style="width:33%">
                        <table id="dg-south1" class="easyui-datagrid" data-options="title:'促销商品',border:false,singleSelect:true,method:'get',split:true,fit:true">
                            <thead>
                                <tr>
                                    <th data-options="width:150,align:'center',field:'oss_bgs_name'">商品名称</th>
                                    <th data-options="width:100,align:'center',field:'bs_shop_sn',formatter:formatSName">店铺名称</th>
                                    <th data-options="width:80,align:'center',field:'oss_trigger_count'">累计</th>
                                    <th data-options="width:80,align:'center',field:'oss_free_count'">免结</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div data-options="region:'east',collapsed:false,split:true,border:false" style="width:67%">
                        <div id="layout_room" class="easyui-layout" data-options="fit:true,border:false">
                            <div data-options="region:'center',collapsed:false,split:true" style="width:55%">
                                <table id="dg-south2" class="easyui-datagrid" data-options="title:'临期商品',border:false,singleSelect:true,method:'get',split:true,fit:true">
                                    <thead>
                                        <tr>
                                            <th data-options="width:100,align:'center',field:'oyc_goods_name'">商品名称</th>
                                            <th data-options="width:100,align:'center',field:'bs_shop_sn',formatter:formatSName">店铺名称</th>
                                            <th data-options="width:70,align:'center',field:'oys_count'">数量</th>
                                            <th data-options="width:70,align:'center',field:'oyc_count'">库存</th>
                                            <th data-options="width:100,align:'center',field:'oyc_end_date'">截止</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>                            
                            <div data-options="region:'east',collapsed:false,split:true" style="width:45%">
                                <table id="dg-south3" class="easyui-datagrid" data-options="title:'延迟结算',border:false,singleSelect:true,method:'get',split:true,fit:true">                            
                                    <thead>
                                        <tr>
                                            <th data-options="width:150,align:'center',field:'dbs_bgs_name'">商品名称</th>
                                            <th data-options="width:100,align:'center',field:'bs_shop_sn',formatter:formatSName">店铺名称</th>
                                            <th data-options="width:50,align:'center',field:'dbs_count'">数量</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
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
        <script src="<?php echo base_url("/resource/admin/balanceCount/BalanceAccount.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
