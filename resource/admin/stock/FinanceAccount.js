

// 查询
function doSearch() {
    var type = $('#type').val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    $('#dg').datagrid('load', {
        type: type,
        start_date: start_date,
        end_date: end_date
    });
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

function formatterDate(date) {
    var day = date.getDate() > 9 ? date.getDate() : "0" + date.getDate();
    var month = (date.getMonth() + 1) > 9 ? (date.getMonth() + 1) : "0"
        + (date.getMonth() + 1);
    return date.getFullYear() + '/' + month + '/' + day;
};