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
    <div data-options="region:'center',title:'商品取样信息'">
        <table id="dg" title="" class="easyui-datagrid" toolbar="#dom_toolbar1" data-options="border:false,fit:true,rownumbers:true,singleSelect:true,method:'get',url:'../<?php echo $c_name; ?>/getList/',pagination:true,pageSize:50,pageList: [50, 100, 200, 300]">
            <thead>
            <tr>
                <th data-options="width:100,align:'center',field:'pgs_id'">商品采样ID</th>
                <th data-options="width:150,align:'center',field:'pgs_provider_goods_id'">供应商商品ID</th>
                <th data-options="width:300,align:'center',field:'pg_name'">商品名称</th>
                <th data-options="width:150,align:'center',field:'pgs_weight'">重量/个(克)</th>
                <th data-options="width:200,align:'center',field:'pgs_create_time'">创建时间</th>
                <th data-options="width:200,align:'center',field:'pgs_update_time'">更新时间</th>
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
    </div>

    <div id="detail_room" data-options="region:'east',title:'详情',hideCollapsedContent:false,collapsed:true,split:true" style="width:690px;height:300px;">
        <table id="dg2" class="easyui-datagrid" data-options="fit:true, rownumbers:true,singleSelect:true,method:'get'">
            <thead>
            <tr>
                <th data-options="width:200,align:'center',field:'pg_name'">商品名称</th>
                <th data-options="width:150,align:'center',field:'pgsr_weight'">重量(KG)</th>
                <th data-options="width:150,align:'center',field:'pgsr_num'">数量</th>
                <th data-options="width:200,align:'center',field:'pgsr_create_time'">创建时间</th>
                <th data-options="width:200,align:'center',field:'pgsr_update_time'">更新时间</th>
            </tr>
            </thead>
        </table>
    </div>
</div>



<div id="d_edit_provider_goods_sample" class="easyui-window" title="编辑商品取样信息" data-options="modal:true,closed:true,iconCls:'icon-edit'" style="width:440px;height:170px;padding:5px;">
    <form id="f_edit_provider_goods_sample" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '供应商商品信息:',
                        labelPosition: 'left',
                        labelWidth:'110',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="pgs_weight" data-options="labelWidth:'110',label:'重量/个（克）:',width:'300', min:0, precision:3">
                    </div>

                </td>
            </tr>
        </table>
        <input name="pgs_id" type="hidden"/>
        <div style="text-align:center;padding:5px 0">
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="saveEditForm()" style="width:80px">保存</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" onclick="closeEditWin()" style="width:80px">取消</a>
        </div>
    </form>
</div>
<div id="d_add_provider_goods_sample" class="easyui-window" title="新增商品取样信息" data-options="modal:true,closed:true,iconCls:'icon-add'" style="width:440px;height:220px;padding:5px;">
    <form id="f_add_provider_goods_sample" method="post">
        <table>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-combobox" name="pg_id" data-options="
                        url:'../ProviderGoodsController/getList?rows_only=true',
                        method:'get',
                        valueField:'pg_id',
                        textField:'provider_goods_format',
                        label: '供应商商品信息:',
                        labelPosition: 'left',
                        labelWidth:'110',
                        width:'400'
                        ">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="weight" data-options="labelWidth:'110',label:'重量(KG):',width:'300', min:0, precision:3">
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div style="margin-left:5px;margin-bottom:5px">
                        <input class="easyui-numberbox" name="num" data-options="labelWidth:'110',label:'数量（个）:',width:'300', min:0">
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
<script src="<?php echo base_url("/resource/admin/baseCheck/ProviderGoodsSample.js?" . rand()) ?>" type="text/javascript"></script>
</body>
</html>
