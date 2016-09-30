var grad_push = 0;
var grad_key = 0;
var count;
var c_ids;
var name=getUrlParam("name");
var remember_token=getUrlParam("remember_token");
function refresh(parameter) {
    if (grad_push == 1) {
        var per = 0;
        per = Math.floor((100 * grad_key + parameter) / count);
        if (parameter == 100) {
            grad_key++;
            $('.progress-bar').css('width', per + '%');
            $('.progress-bar').attr('aria-valuenow', per);
            per == 100 ? $('.progress-bar').text('已完成！') : $('.progress-bar').text( per + '%');
            if ($('.progress-bar').text() == '已完成！') {
                $('.pc_domain').show();
                $(".refresh_loading").hide();
            }
        } else {
            $('.progress-bar').css('width', per + '%');
            $('.progress-bar').attr('aria-valuenow', per);
            $('.progress-bar').text( per + '%');
        }
    } else {
        parameter == 100 ? $('.progress-bar').text('已完成！') : $('.progress-bar').text(parameter + '%');
        $('.progress-bar').css('width', parameter + '%');
        $('.progress-bar').attr('aria-valuenow', parameter);
        if ($('.progress-bar').text() == '已完成！') {
            $('.pc_domain').show();
            $(".refresh_loading").hide();
        }
    }
}
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null)
        return unescape(r[2]);
    return null; //返回参数值
}
if(getUrlParam("pushgrad")=="1"){
    grad_push = 1;
    $.ajax({
        url: "http://182.61.23.43/push-classify-ids?name="+name+"&remember_token="+remember_token,
        async: false,
        dataType: "json",
        type: "GET",
        success: function(json) {
            c_ids = json;
            count = json.length;
        }
    });
}else{
   grad_push = 0; 
}