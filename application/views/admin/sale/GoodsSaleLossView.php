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

<table id="dg" title="销售管理-损耗" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:350,align:'center',field:'cs_name'">店铺名称</th>
        <th data-options="width:100,align:'center',field:'cs_city'">店铺所在城市</th>
        <th data-options="width:200,align:'center',field:'gl_date'">损耗日期</th>
        <th data-options="width:300,align:'center',field:'pg_name'">商品名称</th>
        <th data-options="width:150,align:'center',field:'type'">类型</th>
        <th data-options="width:150,align:'center',field:'num_unit'">数量(单位)</th>
        <th data-options="width:200,align:'center',field:'order'">订单</th>
        <th data-options="width:300,align:'center',field:'remark'">备注</th>
        <th data-options="width:100,align:'center',field:'u_name'">操作员</th>
        <th data-options="width:200,align:'center',field:'gl_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'gl_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <select id="type" class="easyui-combobox"
                data-options="
                    panelHeight:'auto',
                    label: '损耗类型:',
                    labelPosition: 'left',
                    labelWidth:'70',
                    width:'180',
                    panelHeight:'auto'
                ">
            <option value="0">全部</option>
            <option value="1">店铺损耗</option>
            <option value="2">退单损耗</option>
        </select>
        <input id="start_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="开始时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser" placeholder=""/>
        <input id="end_date" class="easyui-datebox" labelWidth="70" style="width:180px;" label="结束时间:" labelPosition="left" data-options="formatter:myformatter,parser:myparser" placeholder=""/>
        <input id="provider_goods_name" class="easyui-textbox" labelWidth="70" style="width:280px;" label="商品名称:"
               labelPosition="left" placeholder=""/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_remove" href="#" data-options="iconCls:'icon-remove'" class="easyui-linkbutton">删除</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_print" href="#" data-options="iconCls:'icon-print'" class="easyui-linkbutton">导出</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>


<div id="d_edit_goods_loss" class="easyui-window" title="编辑损耗信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:460px;height:400px;padding:5px;">
    <form id="f_edit_goods_loss" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_goods_loss_gid" name="goods_id" disabled placeholder="">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="edit_goods_loss_date" name="date" disabled class="easyui-datebox" placeholder=""
                            data-options="
                            labelWidth:'70',
                            label:'损耗日期:',
                            width:'200'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="type" data-options="labelWidth:'70',label:'类型:',width:'200',panelHeight:'auto'">
                            <option value="1" selected>店铺损耗</option>
                            <option value="2">退单损耗</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" placeholder=""
                               data-options="labelWidth:'70', label:'数量:', width:'200', min:0, precision:4,
                               required:true">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'70',label:'单位:',width:'200',panelHeight:'auto'">
                            <option value="1" selected>个</option>
                            <option value="2">斤</option>
                            <option value="3">克</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="order" placeholder=""
                               data-options="labelWidth:'70',label:'订单号:',width:'200'">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="remark" placeholder=""
                            data-options="
                            label:'备注:',
                            width:'400px',
                            height:'60px',
                            panelHeight:'auto',
                            multiline: 'true',
                            labelPosition: 'left',
                            labelWidth:'70'
                        ">
                    </div>
                </td>
            </tr>
        </table>
        <input name="gl_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_goods_loss" class="easyui-window" title="新增损耗信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:460px;height:400px;padding:5px;">
    <form id="f_add_goods_loss" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_goods_loss_gid" name="goods_id" placeholder="">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input id="add_goods_loss_date" class="easyui-datebox" name="date" placeholder=""
                               data-options="labelWidth:'70',label:'损耗日期:',width:'200',required:true">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="type" data-options="labelWidth:'70',label:'类型:',width:'200',panelHeight:'auto'">
                            <option value="1" selected>店铺损耗</option>
                            <option value="2">退单损耗</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" placeholder=""
                               data-options="labelWidth:'70', label:'数量:', width:'200', min:0, precision:4,
                               required:true">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'70',label:'单位:',width:'200',panelHeight:'auto'">
                            <option value="1" selected>个</option>
                            <option value="2">斤</option>
                            <option value="3">克</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="order" placeholder=""
                               data-options="labelWidth:'70', label:'订单号:', width:'200'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="remark" placeholder=""
                            data-options="
                            label:'备注:',
                            width:'400px',
                            height:'60px',
                            panelHeight:'auto',
                            multiline: 'true',
                            labelPosition: 'left',
                            labelWidth:'70'
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
        width: 40px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/sale/GoodsSaleLoss.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
