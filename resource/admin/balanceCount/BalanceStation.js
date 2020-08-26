
function formatSName(val, row) {
    return '易捷(' + val + '站)';
}

function formatMail(val, row) {
    return "<button onclick='previewMail(\"" + val + "\")'>预览</button>";
}

function formatSendState(val, row) {
    if (val === ENUM_SEND_STATE_TODO) {
        return '<span style="color:#55CC55"><b>未发</b></span>';
    }
    if (val === ENUM_SEND_STATE_SUCCESS) {
        return '<span style="color:#FFFFFF"><b>成功</b></span>';
    }
    if (val === ENUM_SEND_STATE_FAIL) {
        return '<span style="color:#CC5555"><b>失败</b></span>';
    }
    return '未知';
}

function previewMail(val) {
    $('#d_mail_preview').panel({
        href: '../..' + val
    });
    var p = $("#layout_room").layout("panel", "east")[0].clientWidth;
    if (p <= 0) {
        $('#layout_room').layout('expand', 'east');
    }
}

$(function () {
    init();
});

function init() {
    $("#dg_bal_shop").datagrid({
        rowStyler: function (index, row) {
            if (row.bas_mail_to === '') {
                return 'background-color:#DD2222;color:#FFFFFF;';
            }
        },
        onClickRow: function (index, row) {
            var p = $("#layout_room").layout("panel", "south")[0].clientWidth;
            if (p <= 0) {
                $('#layout_room').layout('expand', 'south');
            }
            loadDetailData(row.bas_ba_id, row.bas_bs_org_sn);
        }
    });
    $('#btn_mail').bind('click', function () {
        sendMails();
    });
}

function loadDetailData(i_ba_id, i_org_sn) {
    $("#dg_bal_detail").datagrid("options").url = '../AdBalanceStationC/getDetail/';
    $('#dg_bal_detail').datagrid('load', {
        bi: i_ba_id,
        oi: i_org_sn
    });
}

function sendMails() {
    var a_rows = $("#dg_bal_shop").datagrid('getChecked');
    var a_bas_id = [];
    for (var i = 0; i < a_rows.length; i++) {
        if (a_rows[i].bas_mail_send === ENUM_SEND_STATE_SUCCESS) {
            $.messager.alert('错误', '选择的记录中存在已经发送邮件的，请重新选择', 'error');
            return;
        }
        var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); //正则表达式
        var s_mail_to = a_rows[i].bas_mail_to;
        if (!s_mail_to || !reg.test(s_mail_to)) {
            $.messager.alert('错误', '选择的记录中存在无效的邮箱地址，请重新选择或修改', 'error');
            return;
        }
        a_bas_id.push(a_rows[i].bas_id);
    }
    ajaxLoading();
    //异步请求
    $.ajax({
        url: '../AdBalanceStationC/sendMails/',
        type: "POST",
        data: {'ids': a_bas_id},
        success: function (data) {
            var o_response = $.parseJSON(data);
            var s_msg = '';
            for (var i=0; i<o_response.msg.length; i++){
                s_msg += '<div>'+o_response.msg[i]+'</div>';
            }
            if (o_response.state) {
                $.messager.alert('信息', s_msg, 'info');
            } else {
                var s_msg = '';
                $.messager.alert('错误', s_msg, 'error');
            }
            $("#dg_bal_shop").datagrid('reload');
            ajaxLoadEnd();
        }
    });
}
