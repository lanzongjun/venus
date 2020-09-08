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
<table id="dg" title="销售-商品进货" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../sale/<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:80,align:'center',field:'gs_id'">商品进货ID</th>
        <th data-options="width:120,align:'center',field:'cs_name'">店铺名称</th>
        <th data-options="width:180,align:'center',field:'pg_name'">供应商商品</th>
        <th data-options="width:130,align:'center',field:'gs_date'">进货日期</th>
        <th data-options="width:100,align:'center',field:'gs_stock'">库存(KG)</th>
        <th data-options="width:100,align:'center',field:'u_name'">操作人</th>
        <th data-options="width:200,align:'center',field:'gs_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'gs_update_time'">更新时间</th>
    </tr>
    </thead>
</table>

<div id="dom_toolbar1">
    <div>
        <input id="start_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="开始时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="end_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="结束时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="provider_goods_name" class="easyui-textbox" labelWidth="90" style="width:220px;" label="供应商商品:" labelPosition="left"/>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_search" data-options="iconCls:'icon-search'" href="#" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <a id="btn_remove" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>

        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>

<div id="d_add_goods_stock" class="easyui-window" title="新增商品进货信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:440px;height:210px;padding:5px;">
    <form id="f_add_goods_stock" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '供应商商品信息:',
                        labelPosition: 'left',
                        labelWidth:'110',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-datebox" name="date" data-options="labelWidth:'110',label:'进货日期:',width:'300px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="stock" data-options="labelWidth:'110',label:'重量(KG):',width:'300', min:0, precision:2">
                    </div>

                </td>
            </tr>
        </table>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveAddForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeAddWin()" style="width:80px">取消</a>
        </div>
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
<script src="<?php echo base_url("/resource/admin/sale/GoodsStock.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
