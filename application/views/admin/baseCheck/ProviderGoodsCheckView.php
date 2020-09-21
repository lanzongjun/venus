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
<div id="layout_room" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',title:'商品盘点信息'">
        <table id="dg" toolbar="#d_mtoi_toolbar" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
            <tr>
                <th data-options="width:120,align:'center',field:'shop_name'">店铺名称</th>
                <th data-options="width:120,align:'center',field:'pgc_date'">盘点日期</th>
                <th data-options="width:120,align:'center',field:'operator_name'">操作员</th>
                <th data-options="width:200,align:'center',field:'pgc_create_time'">创建时间</th>
                <th data-options="width:200,align:'center',field:'pgc_update_time'">更新时间</th>
            </tr>
            </thead>
        </table>
    </div>
    <div id="d_mtoi_toolbar">
        <div>
            <input id="q_date_begin" class="easyui-datebox" labelWidth="70" style="width:200px;" label="开始时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
            <input id="q_date_end" class="easyui-datebox" labelWidth="70" style="width:200px;" label="结束时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
            <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        </div>
    </div>
    <div id="detail_room" data-options="region:'east',title:'详情',hideCollapsedContent:false,collapsed:true,split:true" style="width:690px;height:300px;">
        <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
            <thead>
            <tr>
                <th data-options="width:200,align:'center',field:'goods_name'">商品名称</th>
                <th data-options="width:150,align:'center',field:'pgcd_num'">数量</th>
                <th data-options="width:150,align:'center',field:'operator_name'">操作员</th>
                <th data-options="width:200,align:'center',field:'pgcd_create_time'">创建时间</th>
                <th data-options="width:200,align:'center',field:'pgcd_update_time'">更新时间</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<div id="d_edit_provider_goods_check" class="easyui-window" title="编辑商品盘点信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:440px;height:170px;padding:5px;">
    <form id="f_edit_provider_goods_check" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        panelHeight:'auto',
                        label: '商品名称:',
                        labelPosition: 'left',
                        labelWidth:'90',
                        width:'300'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="pgcd_num" data-options="labelWidth:'90',label:'商品数量:',width:'240px'">
                    </div>
                </td>
            </tr>
        </table>
        <input name="pgcd_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_provider_goods_check" class="easyui-window" title="新增商品盘点信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:440px;height:180px;padding:5px;">
    <form id="f_add_provider_goods_check" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        panelHeight:'auto',
                        label: '商品名称:',
                        labelPosition: 'left',
                        labelWidth:'90',
                        width:'300'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="pgcd_num" data-options="labelWidth:'90',label:'商品数量:',width:'300px'">
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
<script src="<?php echo base_url("/resource/admin/baseCheck/ProviderGoodsCheck.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>