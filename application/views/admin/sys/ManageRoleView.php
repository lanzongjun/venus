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
<table id="dg" title="系统管理-角色列表" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,
rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:150,align:'center',field:'id'">ID</th>
        <th data-options="width:200,align:'center',field:'name'">名称</th>
        <th data-options="width:400,align:'center',field:'desc'">描述</th>
        <th data-options="width:100,align:'center',field:'status'">状态</th>
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
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_print" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="w_edit_manage_role" class="easyui-window" title="编辑SKU信息" data-options="modal:true,closed:true,
iconCls:'icon-edit'" style="width:1000px;height:800px;padding:5px;">
    <div class="easyui-layout" data-options="fit:true">
        <div id="w_pm_edit_role" title="角色信息" data-options="region:'west',split:true,border:false,width:400">
            <form id="f_pm_edit_role" method="post" data-options="fit:true,border:false,width:340" style="margin:50px 30px 0px 30px;">
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" name="name" data-options="labelWidth:'80px',label:'角色名称:',width:'320px',required:true">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <label style="width:80px;display:inline-block">角色状态:</label>
                    <input class="easyui-radiobutton" name="status" value="1" label="启用:" checked labelWidth="40px">
                    <input class="easyui-radiobutton" name="status" value="0" label="禁用:" labelWidth="40px">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" style="height:80px" name="desc" data-options="multiline:true,labelWidth:'80px',label:'角色描述:',width:'320px'">
                </div>
                <input id="f_pm_edit_role_perms_ids" type="hidden" name="perms_ids"/>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="ManagerRole.saveEditForm()"
                       style="width:80px">保存</a>
                    <!--                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="PowerManager.closeAddWin()" style="width:80px">取消</a>-->
                </div>
            </form>
        </div>
        <div title="角色权限" data-options="region:'center',border:false">
            <ul id="pm_power_tree" class="easyui-tree"
                url='<?php echo base_url("ManagePermsController/getList")?>'
                checkbox="true">
            </ul>
        </div>
        <input name="id" type="hidden"/>
    </div>

</div>
<div id="w_add_manage_role" class="easyui-window" title="新增SKU信息" data-options="modal:true,closed:true,
iconCls:'icon-add'" style="width:1000px;height:800px;padding:5px;">

    <div class="easyui-layout" data-options="fit:true">
        <div id="w_pm_add_role" title="角色信息" data-options="region:'west',split:true,border:false,width:400">
            <form id="f_pm_add_role" method="post" data-options="fit:true,border:false,width:340" style="margin:50px 30px 0px 30px;">
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" name="name" data-options="labelWidth:'80px',label:'角色名称:',width:'320px',required:true">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <label style="width:80px;display:inline-block">角色状态:</label>
                    <input class="easyui-radiobutton" name="status" value="1" label="启用:" checked labelWidth="40px">
                    <input class="easyui-radiobutton" name="status" value="0" label="禁用:" labelWidth="40px">
                </div>
                <div style="margin-left:5px;margin-bottom:5px">
                    <input class="easyui-textbox" style="height:80px" name="desc" data-options="multiline:true,labelWidth:'80px',label:'角色描述:',width:'320px'">
                </div>
                <input id="f_pm_add_role_perms_ids" type="hidden" name="perms_ids"/>
                <div style="text-align:center;padding:5px 0">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="ManagerRole.saveAddForm()" style="width:80px">保存</a>
<!--                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="PowerManager.closeAddWin()" style="width:80px">取消</a>-->
                </div>
            </form>
        </div>
        <div title="角色权限" data-options="region:'center',border:false">
            <ul id="pm_power_tree" class="easyui-tree"
                url='<?php echo base_url("ManagePermsController/getList")?>'
                checkbox="true">
            </ul>
        </div>
    </div>


</div>
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 40px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/sys/ManageRole.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
