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
<table id="dg" title="库存管理-库存列表" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:100,align:'center',field:'goods_id'">商品ID</th>
        <th data-options="width:400,align:'center',field:'shop_name'">店铺</th>
        <th data-options="width:200,align:'center',field:'provider_name'">供应商</th>
        <th data-options="width:200,align:'center',field:'crd_date'">日期</th>
        <th data-options="width:200,align:'center',field:'goods_name'">商品名称</th>
        <th data-options="width:200,align:'center',field:'num_unit'">剩余库存(单位)</th>
        <th data-options="width:200,align:'center',field:'check_num_unit'">盘点剩余库存(单位)</th>
        <th data-options="width:200,align:'center',field:'diff_num_unit',styler:changeColor">差值(单位)</th>
    </tr>
    </thead>
</table>

<div id="dom_toolbar1">
    <input id="select_date" class="easyui-datebox" labelWidth="70" style="width:200px;" label="查询日期:" labelPosition="left" data-options=""/>
<!--    <input id="end_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="结束时间:" labelPosition="left" data-options=""/>-->
    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
<!--    <input id="shop_id" class="easyui-combobox" data-options="-->
<!--                        url:'../CoreShopController/getList?rows_only=true',-->
<!--                        method:'get',-->
<!--                        valueField:'cs_id',-->
<!--                        textField:'cs_name',-->
<!--                        label: '店铺:',-->
<!--                        labelPosition: 'left',-->
<!--                        labelWidth:'40',-->
<!--                        width:'350',-->
<!--                        panelHeight:'auto'-->
<!--                        ">-->
    <input id="provider_id" class="easyui-combobox" data-options="
                        url:'../ProviderController/getList?rows_only=true',
                        method:'get',
                        valueField:'p_id',
                        textField:'p_name',
                        panelHeight:'auto',
                        label: '供应商名称:',
                        labelPosition: 'left',
                        labelWidth:'90',
                        width:'200',
                        panelHeight:'auto'
                        ">
    <input id="goods_name" class="easyui-textbox" labelWidth="70" style="width:220px;" label="商品名称:" labelPosition="left"/>
    <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    <a id="btn_print" href="#" data-options="iconCls:'icon-print'" class="easyui-linkbutton">导出</a>
    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
</div>

<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 30px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/stock/CoreRepertory.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
