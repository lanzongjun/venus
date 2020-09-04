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
<table id="dg" title="商品供应商信息" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:120,align:'center',field:'cs_code'">SKU</th>
        <th data-options="width:400,align:'center',field:'cs_name'">名称</th>
        <th data-options="width:350,align:'center',field:'cs_description'">描述</th>
        <th data-options="width:350,align:'center',field:'cs_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'cs_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <input id="cs_code" class="easyui-textbox" labelWidth="40" style="width:220px;" label="sku:" labelPosition="left"/>
        <input id="cs_name" class="easyui-textbox" labelWidth="40" style="width:220px;" label="名称:" labelPosition="left"/>
        <input id="cs_description" class="easyui-textbox" labelWidth="40" style="width:220px;" label="描述:" labelPosition="left"/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="w_edit_shop" class="easyui-window" title="编辑供应商信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:500px;height:210px;padding:5px;">
    <form id="f_edit_shop" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" readonly disabled name="cs_code" data-options="labelWidth:'100',label:'SKU:',width:'300px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="cs_name" data-options="labelWidth:'100',label:'名称:',width:'400px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="cs_description" data-options="labelWidth:'100',label:'描述:',width:'400px'">
                    </div>
                </td>
            </tr>
        </table>
        <input name="id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="w_add_shop" class="easyui-window" title="新增SKU信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:500px;height:210px;padding:5px;">
    <form id="f_add_shop" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="cs_code" data-options="labelWidth:'100',label:'SKU:',width:'300px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="cs_name" data-options="labelWidth:'100',label:'名称:',width:'400px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="cs_description" data-options="labelWidth:'100',label:'描述:',width:'400px'">
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
<script src="<?php echo base_url("/resource/admin/sku/Sku.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
