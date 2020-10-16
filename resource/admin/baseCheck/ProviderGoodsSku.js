function showAddWin() {
    $('#d_add_provider_goods_sku').window('open');
    $('#add_provider_goods_sku_cscode').combobox({
        url:'../CoreSkuController/getList?rows_only=true',
        method:'get',
        valueField:'cs_code',
        textField:'show_name',
        label: 'SKU编码:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        fitColumns: true
    });
    $('#add_provider_goods_sku_pgid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品名称:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        fitColumns: true
    });
}

// 新增
function saveAddForm() {
    $('#f_add_provider_goods_sku').form('submit');
}

function closeAddWin() {
    $('#d_add_provider_goods_sku').window('close');
}

function saveEditForm() {
    $('#f_edit_provider_goods_sku').form('submit');
}

function closeEditWin() {
    $('#d_edit_provider_goods_sku').window('close');
}

// 编辑
function showEditWin() {
    var o_row = $("#dg").datagrid('getSelected');
    console.log(o_row);
    if (!o_row || !o_row.pgs_id) {
        $.messager.alert('错误', '请选择一条记录后，在进行此操作', 'error');
        return;
    }
    $('#d_edit_provider_goods_sku').window('open');
    $('#edit_provider_goods_sku_cscode').combobox({
        url:'../CoreSkuController/getList?rows_only=true',
        method:'get',
        valueField:'cs_code',
        textField:'show_name',
        label: 'SKU编码:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        fitColumns: true
    });
    $('#edit_provider_goods_sku_pgid').combobox({
        url:'../ProviderGoodsController/getList?rows_only=true',
        method:'get',
        valueField:'pg_id',
        textField:'provider_goods_format',
        label: '商品名称:',
        labelPosition: 'left',
        labelWidth:'70',
        width:'400',
        fitColumns: true
    });
    $('#f_edit_provider_goods_sku').form('load', '../' + __s_c_name + '/getProviderGoodsSkuInfo?id=' + o_row.pgs_id);
}

// 查询
function doSearch() {
    var sku_code = $("#sku_code").val();
    var provider_goods_name = $("#provider_goods_name").val();
    $('#dg').datagrid('load', {
        sku_code: sku_code,
        provider_goods_name: provider_goods_name
    });
}

/*
tableID:表格的ID
colList：需要合并的列，如果有多个，可以以,分开
*/
function mergeCellsByField(tableID, colList) {
    var ColArray = colList.split(",");
    var tTable = $("#" + tableID);
    var TableRowCnts = tTable.datagrid("getRows").length;
    var tmpA;
    var tmpB;
    var PerTxt = "";
    var CurTxt = "";
    var alertStr = "";
    for (j = ColArray.length - 1; j >= 0; j--) {
        PerTxt = "";
        tmpA = 1;
        tmpB = 0;

        for (i = 0; i <= TableRowCnts; i++) {
            if (i == TableRowCnts) {
                CurTxt = "";
            }
            else {
                CurTxt = tTable.datagrid("getRows")[i][ColArray[j]];
            }
            if (PerTxt == CurTxt) {
                tmpA += 1;
            }
            else {
                tmpB += tmpA;

                tTable.datagrid("mergeCells", {
                    index: i - tmpA,
                    field: ColArray[j],　　//合并字段
                    rowspan: tmpA,
                    colspan: null
                });
                tTable.datagrid("mergeCells", { //根据ColArray[j]进行合并
                    index: i - tmpA,
                    field: "Ideparture",
                    rowspan: tmpA,
                    colspan: null
                });

                tmpA = 1;
            }
            PerTxt = CurTxt;
        }
    }
}


$(function () {
    $('#dg').datagrid({
        url: '../' + __s_c_name + '/getList',
        onLoadSuccess: function (data) {
            if (data.rows.length > 0) {
                //调用mergeCellsByField()合并单元格
                mergeCellsByField("dg", "pgs_sku_code");
            }
        }
    });

    $('#btn_add').bind('click', function () {
        showAddWin();
    });
    $('#btn_edit').bind('click', function () {
        showEditWin();
    });
    $('#btn_search').bind('click', function () {
        doSearch();
    });

    $('#f_add_provider_goods_sku').form({
        url: '../' + __s_c_name + '/addProviderGoodsSku',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_add_provider_goods_sku').window('close');
            $('#dg').datagrid('reload');
        }
    });

    $('#f_edit_provider_goods_sku').form({
        url: '../' + __s_c_name + '/editProviderGoodsSku',
        type: "POST",
        success: function (data) {
            var o_response = $.parseJSON(data);
            if (o_response.state) {
                $.messager.alert('信息-更新成功', o_response.msg, 'info');
            } else {
                $.messager.alert('错误-更新失败', o_response.msg, 'error');
            }
            $('#d_edit_provider_goods_sku').window('close');
            $('#dg').datagrid('reload');
        }
    });
});