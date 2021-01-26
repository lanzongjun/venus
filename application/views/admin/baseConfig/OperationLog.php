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
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 40px;
    }
</style>
<body>
<table id="dg" toolbar="#toolbar1" title="操作日志记录" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:80,align:'center',field:'id'">ID</th>
        <th data-options="width:100,align:'center',field:'nickname'">操作员</th>
        <th data-options="width:150,align:'center',field:'identify_code'">标识码</th>
        <th data-options="width:300,align:'center',field:'title'">标题</th>
        <th data-options="width:150,align:'center',field:'ip'">访问IP</th>
        <th data-options="width:400,align:'center',field:'params'">参数</th>
        <th data-options="width:400,align:'center',field:'message'">结果</th>
        <th data-options="width:200,align:'center',field:'create_time'">创建时间</th>
    </tr>
    </thead>
</table>
<div id="toolbar1">
    <div>
        <input id="title" class="easyui-textbox" labelWidth="40" style="width:150px;" label="标题:" labelPosition="left" placeholder=""/>
        <input id="content" class="easyui-textbox" labelWidth="40" style="width:150px;" label="内容:" labelPosition="left" placeholder=""/>
        <input id="nickname" class="easyui-textbox" labelWidth="40" style="width:150px;" label='用户:' labelPosition='left' placeholder=""/>
        <input id="start_date" class="easyui-datetimebox" labelWidth="70" style="width:240px;" label="开始时间:" labelPosition="left" placeholder=""/>
        <input id="end_date" class="easyui-datetimebox" labelWidth="70" style="width:240px;" label="结束时间:" labelPosition="left" placeholder=""/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>

<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/baseConfig/OperationLog.js") ?>" type="text/javascript"></script>
</body>
</html>
