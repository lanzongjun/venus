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
            <div data-options="region:'center',border:false">
                <div id="tab_box" class="easyui-tabs" data-options="fit:true,border:false">
                    <div id="dom_tab_0" data-options="title:'饿百店铺商品信息'">
                        <table id="dg" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="fit:true,footer:'#dom_tb_ft',rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                            <thead>
                                <tr>
                                    <th data-options="width:100,align:'center',field:'sge_shop_name'">店铺名称</th>
                                    <th data-options="width:130,align:'center',field:'sge_barcode'">商品条形码</th>
                                    <th data-options="width:180,align:'center',field:'sge_gname'">商品名称</th>
                                    <th data-options="width:70,align:'center',field:'sge_price'">销售价格</th>
                                    <th data-options="width:65,align:'center',field:'sge_count'">库存数量</th>
                                    <th data-options="width:60,align:'center',field:'sge_count_new',formatter:newStorageFormat">库存(新)</th>
                                    <th data-options="width:55,align:'center',field:'sge_weight'">重量(g)</th>
                                    <!--<th data-options="width:70,align:'center',field:'sge_band'">所属品牌</th>-->
                                    <!--<th data-options="width:80,align:'center',field:'sge_fclass1'">前台一级</th>-->
                                    <th data-options="width:80,align:'center',field:'sge_fclass2'">前台二级</th>
<!--                                    <th data-options="width:80,align:'center',field:'sge_bclass1'">后台一级</th>
                                    <th data-options="width:80,align:'center',field:'sge_bclass2'">后台二级</th>
                                    <th data-options="width:80,align:'center',field:'sge_bclass3'">后台三级</th>-->
                                    <th data-options="width:50,align:'center',field:'sge_online',formatter:upFormat">上架</th>
<!--                                    <th data-options="width:80,align:'center',field:'sge_limit'">每单限购</th>
                                    <th data-options="width:80,align:'center',field:'sge_type'">商品类型</th>-->
                                    <th data-options="width:100,align:'center',field:'sge_gid'">SKU_ID</th>
                                    <th data-options="width:110,align:'center',field:'sge_bs_e_id'">店铺饿百ID</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="dom_tb_ft" style="padding:2px 5px;">
                            <a id="btn_refresh_storage" href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true">刷新</a>
                            <a id="btn_update_storage" href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true">更新</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <a id="btn_out_pf_csv" href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true">导出CSV</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <a id="btn_sync_online" href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true">上传库存</a>
                            <a id="btn_sync_eb_sku_list" href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true">下载SKU列表</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            <a id="btn_freeze_storage" href="#" class="easyui-linkbutton" iconCls="icon-lock" plain="true">冻结库存</a>
                            <a id="btn_unfreeze_storage" href="#" class="easyui-linkbutton" iconCls="icon-lock" plain="true">解冻库存</a>
                            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                        </div>
                        <div id="dom_toolbar1">
                            <div>
                                <a id="btn_todo_input" href="#" class="easyui-linkbutton" iconCls='icon-add'>预导入</a>                    
                                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                                <input id="s_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text'" />
                                <input id="s_goods" class="easyui-textbox" labelWidth="60" style="width:150px;" label="商品名:" labelPosition="left"/>
                                <input id="s_barcode" class="easyui-textbox" labelWidth="60" style="width:200px;" label="条形码:" labelPosition="left"/>
                                <select id="s_filter_storage" class="easyui-combobox" labelWidth="45" style="width:135px;" label='库存:' labelPosition='left'>
                                    <option value="ALL" selected="true">所有</option>
                                    <option value="NON_ZERO">非零库存</option>
                                    <option value="DIFF">库存差异</option>
                                </select>
                                <select id="s_filter_up" class="easyui-combobox" labelWidth="45" style="width:110px;" label='上架:' labelPosition='left'>
                                    <option value="ALL" selected="true">所有</option>
                                    <option value="UP">上架</option>
                                    <option value="DOWN">下架</option>
                                </select>
                                <a id="btn_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                                <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                            </div>
                        </div>
                    </div>
                    <div title="预览">
                        <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">
                            <thead>
                                <tr>
                                    <th data-options="width:150,align:'center',field:'sge_gid'">商品ID</th>
                                    <th data-options="width:150,align:'center',field:'sge_cid'">商品自定义ID</th>
                                    <th data-options="width:150,align:'center',field:'sge_barcode'">商品条形码</th>
                                    <th data-options="width:150,align:'center',field:'sge_gname'">商品名称</th>
                                    <th data-options="width:150,align:'center',field:'sge_shelves'">货架号</th>
                                    <th data-options="width:150,align:'center',field:'sge_band'">所属品牌</th>
                                    <th data-options="width:150,align:'center',field:'sge_fclass1'">前台一级分类</th>
                                    <th data-options="width:150,align:'center',field:'sge_fclass2'">前台二级分类</th>
                                    <th data-options="width:150,align:'center',field:'sge_bclass1'">后台一级分类</th>
                                    <th data-options="width:150,align:'center',field:'sge_bclass2'">后台二级分类</th>
                                    <th data-options="width:150,align:'center',field:'sge_bclass3'">后台三级分类</th>
                                    <th data-options="width:150,align:'center',field:'sge_propety'">商品属性</th>
                                    <th data-options="width:150,align:'center',field:'sge_price'">销售价格</th>
                                    <th data-options="width:150,align:'center',field:'sge_count'">库存数量</th>
                                    <th data-options="width:150,align:'center',field:'sge_online'">是否上线</th>
                                    <th data-options="width:150,align:'center',field:'sge_limit'">每单限购</th>
                                    <th data-options="width:150,align:'center',field:'sge_type'">商品类型</th>
                                    <th data-options="width:150,align:'center',field:'sge_weight'">重量(g)</th>
                                    <th data-options="width:150,align:'center',field:'sge_bs_e_id'">店铺饿百ID</th>
                                    <th data-options="width:150,align:'center',field:'sge_shop_name'">店铺名称</th>
                                </tr>
                            </thead>
                        </table>                
                        <input type="hidden" id="hid_tbn"/>
                    </div>
                    <div title="库存同步日志">
                        <div class="easyui-layout" data-options="fit:true">                            
                            <div data-options="region:'center'">
                                <table id="dg_log" class="easyui-datagrid" toolbar="#dom_tb_log" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getLogUpdateList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
                                    <thead>
                                        <tr>
                                            <th data-options="width:120,align:'center',field:'bs_shop_name',formatter:shopNameFormat">店铺名称</th>
                                            <th data-options="width:80,align:'center',field:'lue_error_no',formatter:updateErrNoFormat">结果状态</th>
                                            <th data-options="width:180,align:'center',field:'lue_dt'">更新时间</th>
                                            <th data-options="width:80,align:'center',field:'lue_user'">操作人</th>
                                        </tr>
                                    </thead>
                                </table>
                                <div id="dom_tb_log">
                                    <div>
                                        <input id="s_log_shop" class="easyui-combobox" labelWidth="45" style="width:170px;" label='店铺:' labelPosition='left' data-options="method:'get',valueField:'id', textField:'text',url: '../<?php echo $c_name; ?>/getShopList'" />
                                        <a id="btn_log_search" href="#" iconCls="icon-search" class="easyui-linkbutton">查询</a>
                                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                                        <a id="btn_log_clear" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">清空日志</a>
                                        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
                                    </div>
                                </div>
                            </div>
                            <div data-options="region:'east',title:'结果详情',split:true" style="width:50%;">
                                <div id="dom_log_msg"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
            <div data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:true,title:'下载列表'" style='width:400px;'>
                <table id="dg_opfl" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getOPFList/'">
                    <thead>
                        <tr>
                            <th data-options="width:260,align:'center',field:'opf_filename'">文件名</th>
                            <th data-options="width:80,align:'center',field:'opf_url',formatter:opfURLFormat">文件地址</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="p_goods_new" data-options="region:'south',split:true,hideCollapsedContent:false,collapsed:true,title:'未上线产品',tools:'#tb_ng'" style='height:300px;'>
                <table id="dg_goods_new" class="easyui-datagrid" data-options="fit:true,border:false,rownumbers:true,singleSelect:true,method:'post',url:'../<?php echo $c_name; ?>/getNewGoods/'">
                    <thead>
                        <tr>
                            <th data-options="width:200,align:'center',field:'bssy_org_name'">站点</th>
                            <th data-options="width:350,align:'center',field:'bgs_name'">品名</th>
                            <th data-options="width:150,align:'center',field:'bgs_barcode'">条码</th>
                            <th data-options="width:80,align:'center',field:'bssy_count'">库存</th>
                            <th data-options="width:80,align:'center',field:'bbp_yj_sale_price'">零售价</th>
                            <th data-options="width:80,align:'center',field:'bbp_settlement_price'">结算价</th>
                        </tr>
                    </thead>
                </table>
            </div>            
        </div>
        <div id="tb_ng">
            <a href="javascript:void(0)" class="icon-reload" onclick="getNewGoods()"></a>
            <a id="tb_ng_dl" href="#" class="icon-print"></a>
        </div>
        <div id="ebsg_win_input" class="easyui-window" title="导入饿百店铺商品" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:350px;height:260px;padding:10px;">
            <form id="form_input" method="post" enctype="multipart/form-data">	
                <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_饿百店铺商品-1站.csv") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
                <br/><br/>
                <input name="e_shop_id" id="dom_shop_id" class="easyui-combobox" labelWidth="45" style="width:100%;" label='店铺' labelPosition='after' data-options="method:'get',valueField:'id', textField:'text'" />
                <input name="file_csv" class="easyui-filebox" data-options="prompt:'选择一个CSV文件...'" style="width:100%;">
                <br/><br/>
                <a id="btn_do_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
            </form>
        </div>
        <style type="text/css">
            .datagrid-header-rownumber, .datagrid-cell-rownumber {
                width: 30px;
            }
        </style>
        <script type="text/javascript">
            var __s_c_name = '<?php echo $c_name; ?>';
        </script>
        <script src="<?php echo base_url("/resource/admin/baseConfig/EBShopGoods.js?" . rand()) ?>" type="text/javascript"></script>
    </body>
</html>
