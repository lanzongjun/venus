

// 查询
function doSearch() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    $('#dg').datagrid('load', {
        start_date: start_date,
        end_date: end_date
    });
}

// 导出
function doPrint() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();

    var a = document.createElement('a');
    a.href = '../' + __s_c_name + '/getList?is_download=1&page=1&rows=1000' +
        '&start_date=' + start_date +
        '&end_date=' + end_date;
    $("body").append(a);  // 修复firefox中无法触发click
    a.click();
    $(a).remove();
}

$(function () {

    var type_obj = $('#type');
    var start_obj = $('#start_date');
    var end_obj = $('#end_date');

    start_obj.datebox('setValue', formatterDate(new Date()));
    end_obj.datebox('setValue', formatterDate(new Date()));

    $('#btn_search').bind('click', function () {
        doSearch();
    });

    $('#btn_print').bind('click', function () {
        doPrint();
    });

    $('#dg').datagrid({
        url:'../' + __s_c_name + '/getList',
        method:'GET',
        queryParams:{
            type: type_obj.val(),
            start_date: start_obj.val(),
            end_date: end_obj.val()
        }
    });
});


function transfer1000 (val, row, index) {
    return row.data[1000].num;
}
function transfer2000 (val, row, index) {
    return row.data[2000].num;
}
function transfer2001 (val, row, index) {
    return row.data[2001].num;
}
function transfer4000 (val, row, index) {
    return row.data[4000].num;
}
function transfer4001 (val, row, index) {
    return row.data[4001].num;
}
function transfer6000 (val, row, index) {
    return row.data[6000].num;
}
function transfer7000 (val, row, index) {
    return row.data[7000].num;
}
function transfer5000 (val, row, index) {
    return row.data[5000].num;
}
function transfer3000 (val, row, index) {
    return row.data[3000].num;
}
function transfer3001 (val, row, index) {
    return row.data[3001].num;
}

// function myformatter(date) {
//     var y = date.getFullYear();
//     var m = date.getMonth() + 1;
//     var d = date.getDate();
//     console.log(111);
//     return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
// }
//
// function myparser(s) {
//     console.log(2222);
//     if (!s)
//         return new Date();
//     var ss = (s.split('-'));
//     var y = parseInt(ss[0], 10);
//     var m = parseInt(ss[1], 10);
//     var d = parseInt(ss[2], 10);
//     if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
//         return new Date(y, m - 1, d);
//     } else {
//         return new Date();
//     }
// }

