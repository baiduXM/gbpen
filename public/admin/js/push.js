var grad_push = 0;
var grad_key = 0;
var count;
var c_ids;
var name=getUrlParam("name");
var remember_token=getUrlParam("remember_token");
function refresh(parameter) {
    if (grad_push == 1) {
        var per = 0;
        per = Math.floor((100 * grad_key + parameter) / (count + 1));
        
    console.log(per);
        if (parameter == 100) {
            grad_key++;
//            if (grad_key < count) {
//                $('#push_iframe').attr('src', 'http://192.168.1.44/pushlogin?name='+name+"&remember_token="+remember_token+'end=0&push_c_id=' + c_ids[grad_key]);
//            } else if (grad_key == count) {
//                $('#push_iframe').attr('src', 'http://192.168.1.44/pushlogin?name='+name+"&remember_token="+remember_token+'end=1&push_c_id=0');
//            }
            $('.progress-bar').css('width', per + '%');
        $('.progress-bar').attr('aria-valuenow', per);
            per == 100 ? $('.progress-bar').text('已完成！') : $('.progress-bar').text( per + '%');
            if ($('.progress_title').text() == '已完成！') {
                $('.refresh-content .btn-top').append('<a class=" refresh_a" target="_blank" href="http://' + $scope.$parent.domain_pc + '">查看网站首页</a>');
                // 刷新按钮
                $('.refresh-content .ing').hide();
                $('.refresh-content .ing').find('img').removeClass('rotate');
                $('.refresh-content .ed').css({'display': 'block'}).stop();
                $('.feild-content .feild-item .status').hide();
                $('.feild-content .feild-item img').show();
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
            // 刷新按钮
            $('.refresh-content .ing').hide();
            $('.refresh-content .ing').find('img').removeClass('rotate');
            $('.refresh-content .ed').css({'display': 'block'}).stop();
            $('.feild-content .feild-item .status').hide();
            $('.feild-content .feild-item img').show();
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
//            console.log(json);
//            $("body").append('<iframe id="push_iframe" src="http://192.168.1.44/pushlogin?name='+name+"&remember_token="+remember_token+'gradpush=1" frameborder="0" style="display;none"></iframe>');
//            refresh(100);
        }
    });
}else{
   grad_push = 0; 
//   $("body").append('<iframe id="push_iframe" src="../push" frameborder="0" style="display;none"></iframe>');
}