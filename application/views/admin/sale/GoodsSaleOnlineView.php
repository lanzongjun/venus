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
<!--<div id="tab_box" class="easyui-tabs" data-options="fit:true">-->
<table id="dg"  title="销售管理-线上销售-销售记录" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../sale/GoodsSaleOnlineController/getList/',toolbar:toolbar1,pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:350,align:'center',field:'shop_name'">店铺</th>
        <th data-options="width:100,align:'center',field:'cs_city'">门店所在城市</th>
        <th data-options="width:150,align:'center',field:'gso_date'">销售日期</th>
        <th data-options="width:150,align:'center',field:'gso_sku_code'">SKU</th>
        <th data-options="width:400,align:'center',field:'sku_name'">SKU名称</th>
        <th data-options="width:100,align:'center',field:'gso_num'">销量</th>
        <th data-options="width:200,align:'center',field:'gso_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'gso_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<!--    <div title="预览">-->
<!--        <table id="dg2" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',toolbar:toolbar2">-->
<!--            <thead>-->
<!--            <tr>-->
<!---->
<!--                <th data-options="width:150,align:'center',field:'gso_shop_id'">店铺</th>-->
<!--                <th data-options="width:150,align:'center',field:'gso_date'">销售日期</th>-->
<!--                <th data-options="width:150,align:'center',field:'gso_sku_code'">SKU</th>-->
<!--                <th data-options="width:150,align:'center',field:'gso_sku_code'">SKU名称</th>-->
<!--                <th data-options="width:150,align:'center',field:'tcpd_shop'">销量</th>-->
<!--                <th data-options="width:150,align:'center',field:'tcpd_bs_sale_sn'">创建时间</th>-->
<!--                <th data-options="width:150,align:'center',field:'tcpd_amount'">更新时间</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--        </table>-->
<!--        <input type="hidden" id="hid_tbn"/>-->
<!--    </div>-->
<!--</div>-->
<div id="sale_online_ele_win_input" class="easyui-window" title="导入数据" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:320px;height:200px;padding:10px;">
    <form id="goods_sale_online_form_input" method="post" enctype="multipart/form-data">
        <a id="btn_down_input" href="<?php echo base_url("/input_template/Example_线上销售模板的.xlsx") ?>" class="easyui-linkbutton" style="width:100%">下载模板文件</a>
        <br/><br/>
        <input name="upload_file" class="easyui-filebox" data-options="prompt:'选择一个EXCEL文件...'" style="width:100%">
        <br/><br/>
        <a id="btn_sale_online_ele_input" href="#" class="easyui-linkbutton" style="width:100%">导入</a>
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
<script src="<?php echo base_url("/resource/admin/sale/GoodsSaleOnline.js?".rand()) ?>" type="text/javascript"></script>
</body>
</html>
