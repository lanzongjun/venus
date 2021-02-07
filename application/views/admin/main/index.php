<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Venus</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/black/easyui.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/icon.css") ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/resource/admin/themes/demo.css") ?>">
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/jquery.easyui.min.js") ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("/resource/admin/easyui-lang-zh_CN.js") ?>"></script>
        <style>
            a:link{text-decoration: none;color: white}

            a:hover{text-decoration:none;color: white}

            a:visited{text-decoration: none;color: white}
        </style>
    </head>
    <body class="easyui-layout">
        <div data-options="region:'north',border:false"  class="system_title_panel">

            <SPAN><?php echo $title ?></SPAN>

            <!--            <a href="../admin/logout" style="color:#000000;float:right;">Logout</a>-->

            <a href="javascript:void(0)" id="mb" class="easyui-menubutton"
               data-options="menu:'#mm',iconCls:'icon-man'" style="color:#000000;float:right;"><?php echo $nickname ?></a>
            <div id="mm" style="width:150px;">
                <!--                <div data-options="iconCls:'icon-undo'">Undo</div>-->
                <!--                <div data-options="iconCls:'icon-redo'">Redo</div>-->
                <!--                <div class="menu-sep"></div>-->
                <div onclick="onOperationLogClick()">操作日志</div>
                <!--                <div>Copy</div>-->
                <!--                <div>Paste</div>-->
                <!--                <div class="menu-sep"></div>-->
                <!--                <div data-options="iconCls:'icon-remove'">Delete</div>-->
                <div><a href="../admin/logout">退出登录</a></div>
            </div>
        </div>


        <div data-options="region:'west',split:true" style="width:150px;">
            <div class="easyui-accordion" data-options="fit:true,border:false">

                <div title="导航">
                    <ul id="sys_root" class="easyui-tree">
                    </ul>
                </div>
            </div>
        </div>
        <div id="layout_center" data-options="region:'center'"></div>
    </body>
    <script src="<?php echo base_url("/resource/admin/main.js?" . rand()) ?>" type="text/javascript"></script>
</html>