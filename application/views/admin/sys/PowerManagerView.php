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
<div class="easyui-layout" data-options="fit:true">
    <div id="w_pm_add_role" title="角色信息" data-options="region:'west',split:true,border:false,width:400">
        <form id="f_pm_add_role" method="post" data-options="fit:true,border:false,width:340" style="margin:50px 30px 0px 30px;">
            <div style="margin-left:5px;margin-bottom:5px">
                <input class="easyui-textbox" name="name" data-options="labelWidth:'80px',label:'角色名称:',width:'320px',required:true">
            </div>
            <div style="margin-left:5px;margin-bottom:5px">
                <input class="easyui-numberbox" name="status" data-options="labelWidth:'80px',label:'角色状态:',width:'320px',precision:0,required:true">
            </div>
            <div style="margin-left:5px;margin-bottom:5px">
                <input class="easyui-textbox" style="height:80px" name="desc" data-options="multiline:true,labelWidth:'80px',label:'角色描述:',width:'320px'">
            </div>
            <input id="f_pm_add_role_perms_ids" type="hidden" name="perms_ids"/>
            <div style="text-align:center;padding:5px 0">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="PowerManager.saveAddForm()" style="width:80px">保存</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="PowerManager.closeAddWin()" style="width:80px">取消</a>
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
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/sys/PowerManager.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
