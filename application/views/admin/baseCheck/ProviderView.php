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
        <th data-options="width:80,align:'center',field:'p_id'">供应商ID</th>
        <th data-options="width:150,align:'center',field:'p_name'">供应商名称</th>
        <th data-options="width:200,align:'center',field:'p_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'p_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <input id="provider_name" class="easyui-textbox" labelWidth="90" style="width:220px;" label="供应商名称:" labelPosition="left"/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_edit_provider" class="easyui-window" title="编辑供应商信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:440px;height:170px;padding:5px;">
    <form id="f_edit_provider" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="p_name" data-options="labelWidth:'100',label:'供应商名称:',width:'240px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-switchbutton" name="p_status" checked data-options="labelWidth:'100',label:'是否启用:',width:'80px'">
                    </div>
                </td>
            </tr>
        </table>
        <input name="p_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_provider" class="easyui-window" title="新增供应商信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:440px;height:150px;padding:5px;">
    <form id="f_add_provider" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="p_name" data-options="labelWidth:'100',label:'供应商名称:',width:'300px'">
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
<script src="<?php echo base_url("/resource/admin/baseCheck/Provider.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
