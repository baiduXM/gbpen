
$(function () {
    $.get('../statis-get', function (json) {
        var data = json.data;
        $(".cxb_list_n2>.cxb_list_m").html(data.count_today);//今日访问数量
        $(".cxb_list_n3>.cxb_list_m").html(data.count_all);//总访客数量
        $(".cxb_list_n4>.cxb_list_m").html(data.mobile_count);//移动端访问量
    });
});