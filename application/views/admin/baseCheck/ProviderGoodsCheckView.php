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
    <div data-options="region:'center',title:'商品管理-商品盘点'">
        <table id="dg" toolbar="#d_toolbar" class="easyui-datagrid" data-options="fit:true,rownumbers:true,singleSelect:true,method:'get',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
            <tr>
                <th data-options="width:280,align:'center',field:'shop_name'">店铺名称</th>
                <th data-options="width:120,align:'center',field:'pgc_date'">盘点日期</th>
                <th data-options="width:120,align:'center',field:'operator_name'">操作员</th>
                <th data-options="width:200,align:'center',field:'pgc_create_time'">创建时间</th>
                <th data-options="width:200,align:'center',field:'pgc_update_time'">更新时间</th>
            </tr>
            </thead>
        </table>
    </div>
    <div id="d_toolbar">
        <div>
            <input id="q_date_begin" class="easyui-datebox" labelWidth="70" style="width:200px;" label="开始时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
            <input id="q_date_end" class="easyui-datebox" labelWidth="70" style="width:200px;" label="结束时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_search" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-search'">查询</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_remove" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
<!--            <a id="btn_reload" href="#" data-options="iconCls:'icon-reload'" class="easyui-linkbutton">校验库存</a>-->
<!--            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>-->
        </div>
    </div>
    <div id="detail_room" data-options="region:'east',title:'详情',hideCollapsedContent:false,collapsed:true,split:true" style="width:690px;height:300px;">
        <table id="dg2" toolbar="#d_toolbar_detail" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
            <thead>
            <tr>
                <th data-options="width:200,align:'center',field:'goods_name'">商品名称</th>
                <th data-options="width:150,align:'center',field:'num_unit'">数量(单位)</th>
                <th data-options="width:150,align:'center',field:'operator_name'">操作员</th>
                <th data-options="width:200,align:'center',field:'pgcd_create_time'">创建时间</th>
                <th data-options="width:200,align:'center',field:'pgcd_update_time'">更新时间</th>
            </tr>
            </thead>
        </table>
    </div>

    <div id="d_toolbar_detail">
        <div>
            <a id="btn_add_detail" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_edit_detail" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
            <a id="btn_remove_detail" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>
            <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        </div>
    </div>
</div>
<div id="d_edit_provider_goods_check_detail" class="easyui-window" title="编辑商品盘点信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:460px;height:210px;padding:5px;">
    <form id="f_edit_provider_goods_check_detail" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_provider_goods_check_gid" name="goods_id">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'90',label:'数量:',
                        width:'240px', precision:4">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'90',label:'单位:',width:'240px',panelHeight:'auto'">
                            <option value="1" selected="true">个</option>
                            <option value="2">斤</option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        <input name="pgcd_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditFormDetail()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWinDetail()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_provider_goods_check" class="easyui-window" title="新增商品盘点信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:460px;height:140px;padding:5px;">
    <form id="f_add_provider_goods_check" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id='check_date' name="date"
                               class="easyui-datebox"
                               data-options="
                               labelWidth:'70',
                               label:'盘点日期:',
                               width:'240'
                            ">
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
<div id="d_add_provider_goods_check_detail" class="easyui-window" title="新增商品盘点信息详情" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:460px;height:210px;padding:5px;">
    <form id="f_add_provider_goods_check_detail" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_provider_goods_check_gid" name="goods_id">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'90',label:'数量:',
                        width:'240px', precision:4">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'90',label:'单位:',width:'240px',panelHeight:'auto'">
                            <option value="1" selected="true">个</option>
                            <option value="2">斤</option>
                        </select>
                    </div>
                </td>
            </tr>
        </table>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveAddFormDetail()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeAddWinDetail()" style="width:80px">取消</a>
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
