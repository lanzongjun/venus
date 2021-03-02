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
<table id="dg" title="系统管理-操作员列表" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,
rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:100,align:'center',field:'id'">ID</th>
        <th data-options="width:400,align:'center',field:'username'">用户名</th>
        <th data-options="width:400,align:'center',field:'role_name'">角色名称</th>
        <th data-options="width:200,align:'center',field:'manage_status_text'">管理员状态</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <input id="name" class="easyui-textbox" labelWidth="60" style="width:220px;" label="用户名:" labelPosition="left"/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_remove" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_edit_manage" class="easyui-window" title="编辑管理员信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:500px;height:210px;padding:5px;">
    <form id="f_edit_manage" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_manage" name="manage_id" placeholder="">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_role" name="role_id" placeholder="">
                    </div>
                </td>
            </tr>


            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <label style="width:80px;display:inline-block">绑定状态:</label>
                        <input class="easyui-radiobutton" name="status" value="1" label="启用:" checked labelWidth="40px">
                        <input class="easyui-radiobutton" name="status" value="0" label="禁用:" labelWidth="40px">
                    </div>
                </td>
            </tr>
        </table>
        <input name="id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="Manage.saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="Manage.closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_manage" class="easyui-window" title="新增管理员信息" data-options="modal:true,closed:true,iconCls:'icon-add'"
     style="width:500px;height:210px;padding:5px;">
    <form id="f_add_manage" method="post">
        <table>

            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_manage" name="manage_id" placeholder="">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_role" name="role_id" placeholder="">
                    </div>
                </td>
            </tr>


            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <label style="width:80px;display:inline-block">绑定状态:</label>
                        <input class="easyui-radiobutton" name="status" value="1" label="启用:" checked labelWidth="40px">
                        <input class="easyui-radiobutton" name="status" value="0" label="禁用:" labelWidth="40px">
                    </div>
                </td>
            </tr>


        </table>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="Manage.saveAddForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="Manage.closeAddWin()" style="width:80px">取消</a>
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
<script src="<?php echo base_url("/resource/admin/sys/Manage.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
