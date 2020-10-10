

// 查询
function doSearch() {
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    $('#dg').datagrid('load', {
        start_date: start_date,
        end_date: end_date
    });
}

$(function () {
    $('#btn_search').bind('click', function () {
        doSearch();
    });
});


function transfer1 (val, row, index) {
    return row.data[1].num;
}
function transfer2 (val, row, index) {
    return row.data[2].num;
}
function transfer3 (val, row, index) {
    return row.data[3].num;
}
function transfer4 (val, row, index) {
    return row.data[4].num;
}
function transfer6 (val, row, index) {
    return row.data[6].num;
}
function transfer7 (val, row, index) {
    return row.data[7].num;
}
function transfer8 (val, row, index) {
    return row.data[8].num;
}