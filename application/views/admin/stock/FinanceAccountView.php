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
<table id="dg" title="库存管理-库存列表" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:100,align:'center',field:'goods_id'">商品ID</th>
        <th data-options="width:200,align:'center',field:'goods_name'">商品名称</th>
        <th data-options="width:200,align:'center',field:'unit'">单位</th>
        <th data-options="width:200,align:'center',field:'stock', formatter:transfer1 ">入库数量</th>
        <th data-options="width:200,align:'center',field:'sale_offline', formatter:transfer2 ">线下销售</th>
        <th data-options="width:200,align:'center',field:'sale_online', formatter:transfer7 ">线上销售</th>
        <th data-options="width:200,align:'center',field:'exception', formatter:transfer8 ">异常情况</th>
        <th data-options="width:200,align:'center',field:'exception', formatter:transfer6 ">员工餐</th>
        <th data-options="width:200,align:'center',field:'exception', formatter:transfer3 ">损耗-店内破损</th>
        <th data-options="width:200,align:'center',field:'exception', formatter:transfer4 ">损耗-退单</th>
    </tr>
    </thead>
</table>


<div id="dom_toolbar1">
    <input id="start_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="开始时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
    <input id="end_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="结束时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
    <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
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
<script src="<?php echo base_url("/resource/admin/stock/FinanceAccount.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
