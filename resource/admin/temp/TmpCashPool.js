var toolbar1 = [{
        text: '预导入',
        iconCls: 'icon-add',
        handler: function () {
            $('#cp_win_input').window('open');
        }
    }];

var toolbar2 = [{
        text: '追加导入',
        iconCls: 'icon-add',
        handler: function () {
            appendData();
        }
    }];

$(function () {
    //预导入CSV文件
    $('#btn_do_input').bind('click', function () {
        $("#form_input").form("submit", {
            type: 'post',
            url: '../'+__s_c_name+'/uploadInfo',
            onSubmit: function () {
                $('#cp_win_input').window('close');
                ajaxLoading();
            },
            success: function (data) {
                var o_response = $.parseJSON(data);
                if (o_response.state === true) {
                    $('#hid_tbn').val(o_response.tbn);
                    $("#dg2").datagrid("options").url = '../'+__s_c_name+'/loadPreview/';
                    $('#dg2').datagrid('load', {
                        tbn: $('#hid_tbn').val()
                    });
                    ajaxLoadEnd();
                    $.messager.alert('信息', '预导入完成!', 'info');
                    $("#tab_box").tabs("select", 1);
                }
            }
        });
    });
})

function appendData() {
    var s_tbn = $('#hid_tbn').val();
    if (s_tbn === '') {
        $.messager.alert('错误', '预导入数据不存在!', 'error');
        return;
    }
    $.messager.confirm('确认', '预导入的数据将追加至正式数据，是否确认追加导入?', function (r) {
        if (r) {
            var s_tbn = $('#hid_tbn').val();
            //异步请求
            $.ajax({
                url: '../'+__s_c_name+'/appendData/',
                type: "POST",
                data: {"tbn": s_tbn},
                success: function (data) {
                    var o_response = $.parseJSON(data);
                    if (o_response.state === true) {
                       $.messager.alert('成功', '数据导入成功!', 'info');
                    } else {
                       $.messager.alert('失败', '数据导入失败!请重试', 'error');
                    }
                    //清空预览和tbn值
                    $('#hid_tbn').val('');
                    $("#dg2").datagrid("loadData", { total: 0, rows: [] });
                    //返回主界面
                    $("#tab_box").tabs("select", 0);
                    $("#dg").datagrid("reload");
                }
            })
        }
    });
}
