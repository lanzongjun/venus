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
<table id="dg" title="商品管理-添加商品" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:100,align:'center',field:'pg_id'">商品ID</th>
        <th data-options="width:150,align:'center',field:'p_name'">供应商名称</th>
        <th data-options="width:150,align:'center',field:'pg_name'">商品名称</th>
        <th data-options="width:150,align:'center',field:'is_dumplings'">是否需要计量</th>
        <th data-options="width:200,align:'center',field:'pg_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'pg_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <input id="provider_name" class="easyui-textbox" labelWidth="85" style="width:220px;" label="供应商名称:" labelPosition="left" placeholder=""/>
        <input id="provider_goods_name" class="easyui-textbox" labelWidth="75" style="width:220px;" label="商品名称:" labelPosition="left" placeholder=""/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<div id="d_edit_provider_goods" class="easyui-window" title="编辑商品信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:460px;height:210px;padding:5px;">
    <form id="f_edit_provider_goods" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_provider_goods_pid" name="p_id" placeholder="">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="pg_name" data-options="labelWidth:'100',label:'商品名称:',
                        width:'240px'" placeholder="">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="is_dumplings" data-options="labelWidth:'100',
                        label:'是否需要计量:',width:'150',panelHeight:'auto'">
                            <option value="0">否</option>
                            <option value="1" selected>是</option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        <input name="pg_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_provider_goods" class="easyui-window" title="新增商品信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:460px;height:210px;padding:5px;">
    <form id="f_add_provider_goods" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_provider_goods_pid" name="p_id" placeholder="">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="pg_name" data-options="labelWidth:'100',label:'商品名称:',
                        width:'300px'" placeholder="">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="is_dumplings" data-options="labelWidth:'100',
                        label:'是否需要计量:',width:'160',panelHeight:'auto'">
                            <option value="0">否</option>
                            <option value="1" selected>是</option>
                        </select>
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
<script src="<?php echo base_url("/resource/admin/baseCheck/ProviderGoods.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
