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
<table id="dg" title="异常订单-索赔单" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:80, align:'center',field:'geh_id'">异常订单ID</th>
        <th data-options="width:200,align:'center',field:'shop_name'">店铺名称</th>
        <th data-options="width:100,align:'center',field:'type'">类型</th>
        <th data-options="width:100,align:'center',field:'geh_order'">订单号</th>
        <th data-options="width:200,align:'center',field:'goods_name'">商品名称</th>
        <th data-options="width:180,align:'center',field:'geh_date'">上报日期</th>
        <th data-options="width:100,align:'center',field:'num_unit'">数量（单位）</th>
        <th data-options="width:100,align:'center',field:'geh_type_text'">异常类型</th>
        <th data-options="width:100,align:'center',field:'geh_is_reduce_stock_text'">是否减少库存</th>
        <th data-options="width:300,align:'center',field:'remark'">备注</th>
        <th data-options="width:100,align:'center',field:'operator'">操作员</th>
        <th data-options="width:200,align:'center',field:'geh_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'geh_update_time'">更新时间</th>
    </tr>
    </thead>
</table>

<div id="dom_toolbar1">
    <div>
        <select id="type" class="easyui-combobox" data-options="
                        panelHeight:'auto',
                        label: '损耗类型:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'180',
                        panelHeight:'auto'
                        ">
            <option value="0">全部</option>
            <option value="1">索赔单</option>
        </select>
        <input id="start_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="开始时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <input id="end_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="结束时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser"/>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <input id="provider_goods_name" class="easyui-textbox" labelWidth="90" style="width:220px;" label="供应商商品:" labelPosition="left"/>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_search" data-options="iconCls:'icon-search'" href="#" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_remove" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>

<div id="d_edit_exception_handle" class="easyui-window" title="编辑异常订单信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:450px;height:430px;padding:5px;">
    <form id="f_edit_exception_handle" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="goods_id" readonly disabled data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '商品信息:',
                        labelPosition: 'left',
                        labelWidth:'90',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_exception_handle_date" class="easyui-datebox" name="date" data-options="labelWidth:'90',label:'上报日期:',width:'200'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="type" readonly disabled data-options="labelWidth:'90',label:'类型:',width:'200',panelHeight:'auto'">
                            <option value="1" selected="true">索赔单</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'90',label:'数量:',width:'200', min:0, precision:2">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'90',label:'单位:',width:'200',panelHeight:'auto'">
                            <option value="1" selected="true">个</option>
                            <option value="2">斤</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="is_reduce_stock" readonly data-options="labelWidth:'90',label:'是否减库存:',width:'200',panelHeight:'auto'">
                            <option value="0" selected="true">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="order" data-options="labelWidth:'90',label:'订单号:',width:'200'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="remark" data-options="
                        label:'备注:',
                        width:'400',
                        height:'60px',
                        panelHeight:'auto',
                        multiline: 'true',
                        labelPosition: 'left',
                        labelWidth:'90'
                    ">
                    </div>
                </td>
            </tr>
        </table>
        <input name="geh_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>

<div id="d_add_exception_handle" class="easyui-window" title="新增异常订单信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:450px;height:430px;padding:5px;">
    <form id="f_add_exception_handle" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="goods_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '商品信息:',
                        labelPosition: 'left',
                        labelWidth:'90',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_exception_handle_date" class="easyui-datebox" name="date" data-options="labelWidth:'90',label:'上报日期:',width:'200'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="type" data-options="labelWidth:'90',label:'类型:',width:'200',panelHeight:'auto'">
                            <option value="1" selected="true">索赔单</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'90',label:'数量:',width:'200', min:0, precision:2">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'90',label:'单位:',width:'200',panelHeight:'auto'">
                            <option value="1" selected="true">个</option>
                            <option value="2">斤</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="is_reduce_stock" data-options="labelWidth:'90',label:'是否减库存:',width:'200',panelHeight:'auto'">
                            <option value="0" selected="true">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="order" data-options="labelWidth:'90',label:'订单号:',width:'200'">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="remark" data-options="
                        label:'备注:',
                        width:'400px',
                        height:'60px',
                        panelHeight:'auto',
                        multiline: 'true',
                        labelPosition: 'left',
                        labelWidth:'90'
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

<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 30px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/sale/GoodsExceptionHandle.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
