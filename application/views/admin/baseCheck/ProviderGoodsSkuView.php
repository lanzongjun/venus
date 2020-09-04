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
<table id="dg" title="商品关联信息" class="easyui-datagrid" toolbar="#dom_toolbar1"
       data-options="
                border:false,
                fit:true,
                rownumbers: true,
                singleSelect: true,
                url:'../ProviderGoodsSkuController/getList/',
                method: 'get',
                pagination:true,
                pageSize:50,
                pageList: [50, 100, 200, 300],
            ">
    <thead frozen="true">
    <tr>
        <th data-options="width:150,align:'center',field:'pgs_sku_code'">SKU</th>
        <th data-options="width:150,align:'center',field:'pg_name'">商品名称</th>
        <th data-options="width:100,align:'center',field:'pgs_num'">数量(个)</th>
        <th data-options="width:200,align:'center',field:'pgs_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'pgs_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <input id="sku_code" class="easyui-textbox" labelWidth="75" style="width:220px;" label="SKU编码:" labelPosition="left"/>
        <input id="provider_goods_name" class="easyui-textbox" labelWidth="75" style="width:220px;" label="商品名称:" labelPosition="left"/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_edit_provider_goods_sku" class="easyui-window" title="编辑商品关联信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:600px;height:220px;padding:5px;">
    <form id="f_edit_provider_goods_sku" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="cs_code" data-options="
                        url:'../CoreSkuController/getList?rows_only=true',
                        method:'get',
                        valueField:'cs_code',
                        textField:'show_name',
                        label: 'SKU编码:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'500',
                        fitColumns: true,
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '商品名称:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'300',
                        fitColumns: true,
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="pgs_num" data-options="labelWidth:'70',label:'数量:',width:'200'">
                    </div>
                </td>
            </tr>
        </table>
        <input name="pgs_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_provider_goods_sku" class="easyui-window" title="新增商品关联信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:600px;height:220px;padding:5px;">
    <form id="f_add_provider_goods_sku" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="cs_code" data-options="
                        url:'../CoreSkuController/getList?rows_only=true',
                        method:'get',
                        valueField:'cs_code',
                        textField:'show_name',
                        label: 'SKU编码:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'500',
                        fitColumns: true,
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '商品名称:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'300',
                        fitColumns: true,
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="pgs_num" data-options="labelWidth:'70',label:'数量:',width:'200'">
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
        width: 40px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/baseCheck/ProviderGoodsSku.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
