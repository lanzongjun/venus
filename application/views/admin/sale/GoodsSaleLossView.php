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



<table id="dg" title="<?php echo $title; ?>" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList?type=<?php echo $type; ?>',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
    <thead>
    <tr>
        <th data-options="width:100,align:'center',field:'cs_name'">店铺名称</th>
        <th data-options="width:100,align:'center',field:'cs_city'">店铺所在城市</th>
        <th data-options="width:300,align:'center',field:'pg_name'">供应商商品名称</th>
        <th data-options="width:150,align:'center',field:'num_unit'">数量(单位)</th>
        <?php

        if ($type == 2) {
            $line = <<<EOF
                        <th data-options="width:150,align:'center',field:'gl_order'">订单</th>
EOF;
        echo $line;
        }

        ?>
        <th data-options="width:100,align:'center',field:'u_name'">操作员</th>
        <th data-options="width:200,align:'center',field:'gl_create_time'">创建时间</th>
        <th data-options="width:200,align:'center',field:'gl_update_time'">更新时间</th>
    </tr>
    </thead>
</table>
<div id="dom_toolbar1">
    <div>
        <input id="provider_goods_name" class="easyui-textbox" labelWidth="70" style="width:280px;" label="商品名称:" labelPosition="left"/>
        <a id="btn_search" href="#" data-options="iconCls:'icon-search'" class="easyui-linkbutton">查询</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
        <a id="btn_add" href="#" data-options="iconCls:'icon-add'" class="easyui-linkbutton">新增</a>
        <a id="btn_edit" href="#" data-options="iconCls:'icon-edit'" class="easyui-linkbutton">编辑</a>
        <span class="datagrid-btn-separator" style="vertical-align: middle;display:inline-block;float:none"></span>
    </div>
</div>
<?php
if ($type == 1) {
    $html = <<<EOF
    <div id="d_edit_goods_loss" class="easyui-window" title="编辑损耗信息" 
    data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:440px;height:210px;padding:5px;">
    <form id="f_edit_goods_loss" method="post">
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
                        labelWidth:'70',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-datebox" name="date" data-options="labelWidth:'70',label:'损耗日期:',width:'300px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'70',label:'数量/个:',width:'300', min:0">
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
    <div id="d_add_goods_loss" class="easyui-window" title="新增损耗信息" 
    data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:440px;height:245px;padding:5px;">
    <form id="f_add_goods_loss" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '商品信息:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'400',
                        required:true
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-datebox" name="date" data-options="labelWidth:'70',label:'损耗日期:',width:'300px',required:true">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'70',label:'数量:',width:'300', min:0, precision:2, required:true">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'70',label:'单位:',width:'130',panelHeight:'auto'">
                            <option value="1" selected="true">个</option>
                            <option value="2">斤</option>
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
EOF;
} else {
    $html = <<<EOF
    <div id="d_edit_goods_loss" class="easyui-window" title="编辑损耗信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:440px;height:245px;padding:5px;">
    <form id="f_edit_goods_loss" method="post">
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
                        labelWidth:'70',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-datebox" name="date" data-options="labelWidth:'70',label:'损耗日期:',width:'300px'">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'70',label:'数量/个:',width:'300', min:0">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="order" data-options="labelWidth:'70',label:'订单号:',width:'300'">
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
    <div id="d_add_goods_loss" class="easyui-window" title="新增损耗信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:440px;height:280px;padding:5px;">
    <form id="f_add_goods_loss" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '商品信息:',
                        labelPosition: 'left',
                        labelWidth:'70',
                        width:'400',
                        required:true
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-datebox" name="date" data-options="labelWidth:'70',label:'损耗日期:',width:'300px',required:true">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'70',label:'数量:',width:'300', min:0, precision:2, required:true">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <select class="easyui-combobox" name="unit" data-options="labelWidth:'70',label:'单位:',width:'130',panelHeight:'auto'">
                            <option value="1" selected="true">个</option>
                            <option value="2">斤</option>
                        </select>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-textbox" name="order" data-options="labelWidth:'70',label:'订单号:',width:'300', required:true">
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
EOF;
}

echo $html;
?>
<style type="text/css">
    .datagrid-header-rownumber, .datagrid-cell-rownumber {
        width: 40px;
    }
</style>
<script type="text/javascript">
    var __s_c_name = '<?php echo $c_name; ?>';
    var __s_type = '<?php echo $type; ?>';
</script>
<script src="<?php echo base_url("/resource/admin/sale/GoodsSaleLoss.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
