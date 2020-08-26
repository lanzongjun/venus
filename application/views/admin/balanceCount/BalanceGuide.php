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
            <div title="订单" data-options="region:'center',split:true">
                <table id="dg_orders" class="easyui-datagrid" toolbar="#d_bg_order_toolbar" data-options="checkOnSelect:false,border:false,fit:true,singleSelect:false,method:'get'">
                    <thead>
                        <tr>
                            <th data-options="field:'ck', checkbox:true"></th>
                            <th data-options="width:65,align:'center',field:'order_from'">订单来源</th>
                            <th data-options="width:200,align:'center',field:'order_date'">订单日期</th>
                            <th data-options="width:220,align:'center',field:'shop_name'">商户名称</th>
                            <th data-options="width:80,align:'center',field:'receive_amount'">应收金额</th>
                            <th data-options="width:90,align:'center',field:'order_state'">订单状态</th>
                            <th data-options="width:65,align:'center',field:'eoi_ba_bat_id',formatter:orderCheckFormat">结算状态</th>
                            <th data-options="width:65,align:'center',field:'eoi_update_date',formatter:orderModifyFormat">修改状态</th>
                            <th data-options="width:65,align:'center',field:'eoi_code',formatter:editFormat">操作</th>
                        </tr>
                    </thead>
                </table>   
                <div id="d_bg_order_toolbar">
                    <div>
                        <input id="q_date_begin" class="easyui-datebox" labelWidth="20" style="width:130px;" label="从:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <input id="q_date_end" class="easyui-datebox" labelWidth="20" style="width:130px;" label="至:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <input id="q_shop" class="easyui-combobox" labelWidth="45" style="width:150px;" label='店铺:' labelPosition='left' data-options="url:'../AdTmpYJExpireC/getShopList', method:'get',valueField:'id', textField:'text'" />
                        <select id="q_from" class="easyui-combobox" labelWidth="45" style="width:125px;" label='来源:' labelPosition='left'>
                            <option value="ELE" selected="true">饿了么</option>
                            <option value="JD">京东到家</option>
                            <option value="MT">美团</option>
                        </select>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <select id="q_b_state" class="easyui-combobox" labelWidth="45" style="width:125px;" label='结算:' labelPosition='left'>
                            <option value="0" selected="true">未结算</option>
                            <option value="1">已结算</option>
                        </select>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_search" href="#" class="easyui-linkbutton">查询</a>
                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        <a id="btn_select" href="#" class="easyui-linkbutton">纳入结算</a>
                    </div>
                </div>
                <div id="w_edit_ele_toolbar">
                    <a href="javascript:void(0)" class="icon-save easyui-tooltip" title="保存订单信息" onclick="javascript:BGOE_doSaveOrder()"></a>
                    <a href="javascript:void(0)" class="icon-remove easyui-tooltip" title="删除订单" selected='true' size='large' onclick="javascript:BGOE_doDelOrder()"></a>
                </div>
                <div id="w_edit_ele" class="easyui-window" title="订单编辑-饿百" data-options="tools:'#w_edit_ele_toolbar',modal:true,closed:true,iconCls:'icon-edit'" style="width:850px;height:600px;padding:5px;">
                    <div class="easyui-layout" data-options="fit:true">
                        <div data-options="border:false,region:'north',split:true" style="height:160px">
                            <form id="f_edit_ele" method="post">
                                <table style="width:100%">
                                    <tr>
                                        <td>
                                            <input class="easyui-textbox" id="f_edit_eoi_code" name="code" data-options="label:'订单编号:',width:'240px',editable:false">                                                    
                                        </td>
                                        <td>
                                            <input class="easyui-textbox" name="order_date" data-options="label:'下单时间:',width:'240px',editable:false">                                                    
                                        </td>                                                
                                        <td>
                                            <select id="f_edit_ele_order_state" name="order_state" class="easyui-combobox" labelWidth="45" style="width:240px;" label='订单状态:' labelPosition='left'>
                                                <option value="finish">已完结</option>
                                                <option value="cancel" selected="true">已取消(终态)</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="easyui-textbox" name="order_amount" data-options="label:'订单金额:',width:'240px',editable:false">                                                 
                                        </td>
                                        <td>
                                            <input class="easyui-textbox" name="cus_pay" data-options="label:'实付金额:',width:'240px',editable:false">
                                        </td>                                                
                                        <td>
                                            <input class="easyui-textbox" name="receive_amount" data-options="label:'应收金额:',width:'240px',editable:false">                                                      
                                        </td>
                                    </tr>
                                </table>
                                <div>
                                    <input id="f_edit_shop_id" type="hidden" name="shop_id"/>                                            
                                    <input class="easyui-textbox" name="refund_state" data-options="label:'退款状态:',multiline:true,editable:false" style="width:48%;height:80px">
                                    <input id="f_edit_update_memo" class="easyui-textbox" name="update_memo" data-options="label:'更新说明:',multiline:true,required:true" style="width:48%;height:80px">
                                </div>
                            </form>
                        </div>
                        <div data-options="border:false,region:'center',split:true">
                            <table id="dg_order_detail" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get',toolbar:tb_detail">
                                <thead>
                                    <tr>
                                        <th data-options="width:300,align:'center',field:'eod_goods_name'">商品名称</th>
                                        <th data-options="width:150,align:'center',field:'sge_barcode'">商品条码</th>
                                        <th data-options="width:80,align:'center',field:'eod_onsale_before'">优惠前单价</th>
                                        <th data-options="width:50,align:'center',field:'eod_buy_count'">数量</th>
                                        <th data-options="width:120,align:'center',field:'eod_update_memo'">更新说明</th>
                                        <th data-options="width:80,align:'center',field:'eod_id',formatter:editDetailFormat">操作</th>
                                    </tr>
                                </thead>
                            </table>
                            <div id="w_add_detail_ele" class="easyui-window" title="订单详情新增-饿百" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:360px;height:280px;padding:15px;">
                                <form id="f_add_detail_ele" action="../AdEBOrderInfoC/addDetail/" method="post">
                                    <input type="hidden" name="eod_eoi_code"/>
                                    <input id="w_add_sgoods" class="easyui-combobox" name="eod_sku_code" style="width:300px;" label='商品:' labelPosition='left' data-options="url:'../AdEBShopGoodsC/getShopGoods',required:true, method:'get',valueField:'gid', textField:'gname'" />
                                    <input class="easyui-numberbox" name="eod_buy_count" data-options="label:'购买数量:',width:'300px',precision:0,required:true" style="">
                                    <input class="easyui-textbox" name="eod_update_memo" data-options="label:'更新说明:',multiline:true,width:'300px',required:true" style="height:90px">
                                    <input type="submit" value="保存" style="margin-top:10px;"/>
                                </form>
                            </div>
                            <div id="w_edit_detail_ele" class="easyui-window" title="订单详情编辑-饿百" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:260px;height:220px;padding:15px;">
                                <form id="f_edit_detail_ele" action="../AdEBOrderInfoC/editDetail/" method="post">
                                    <input type="hidden" name="eod_id"/>
                                    <input class="easyui-numberbox" name="eod_buy_count" data-options="label:'购买数量:',width:'200px',precision:0,required:true">
                                    <input class="easyui-textbox" name="eod_update_memo" data-options="label:'更新说明:',labelPosition:'top',multiline:true,required:true" style="width:100%;height:90px">
                                    <input type="submit" value="保存" style="margin-top:10px;"/>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div data-options="region:'east',split:true,title:'结算目标'" style="width:400px;">
                <table id="dg-balance" class="easyui-datagrid" data-options="border:false,fit:true,singleSelect:true,method:'get',toolbar:tb_balance">
                    <thead>
                        <tr>
                            <th data-options="width:50,align:'center',field:'order_from'">来源</th>
                            <th data-options="width:50,align:'center',field:'shop_sn'">商户</th>
                            <th data-options="width:50,align:'center',field:'receive_amount'">金额</th>
                            <th data-options="width:120,align:'center',field:'order_date'">日期</th>                            
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
        <script src="<?php echo base_url("/resource/admin/balanceCount/BalanceGuide.js?" . rand()) ?>" type="text/javascript"></script>
        <script src="<?php echo base_url("/resource/admin/balanceCount/BalanceGuideOrderEdit.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
