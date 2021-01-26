
function doSearch() {
    var title = $("#title").val();
    var content = $("#content").val();
    var nickname = $("#nickname").val();
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    $('#dg').datagrid('load', {
        title: title,
        content: content,
        nickname: nickname,
        start_date: start_date,
        end_date: end_date
    });
}

$(function () {
    $('#btn_search').bind('click', function () {
        doSearch();
    });
});





