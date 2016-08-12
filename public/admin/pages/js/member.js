function memberController($scope) {
    $scope.$parent.page = true;
    $scope.$parent.diy = true;
    $scope.$parent.homepage = true;
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    // 配置json地址
//    json_url = 'json/member.json';

    // 分页  
    var num_per_page = 8;
    var page_num = 0;
    var pageselectCallback = function (page_indexs) {
//        console.log(jq)
$.ajax();
        $.getJSON(json_url, {per_page: num_per_page, current_page: page_indexs + 1}, function (json) {
            var data = json.data;
            var _div = '';
            if (json.err == 0) {
                $.each(data, function (i) {
                    var _divimg1 = '<img src="images/wx_icon.png" /><span>微信</span>';
                    var _divimg2 = '<img src="images/shop_icon.png" /><span>商场</span>';
                    _div += '<tr class="mem_list">\n\
	                <td style="text-align: left"><dl class="fl checkclass"><input type="checkbox" name="checks" style=" display:none;"><label class="label"></label></dl>' + data[i].name + '</td>\n\
	                <td>' + data[i].phone + '</td>\n\
	                <td>' + data[i].Integral + '</td>\n\
	                <td>' + (data[i].bind_wx ? _divimg1 : '') + (data[i].bind_shop ? _divimg2 : '') + '</td>\n\
	                <td>' + data[i].action + '</td>\n\
	                <td><span style="margin-right: 20px;">编辑</span><span class="delv" name="' + data[i].id + '">删除</span></td>\n\
	            </tr>';
                });
                $('.a-table').append(_div);
            } else {
                alert(json.msg);
                _show.filter(':eq(' + page_indexs - 1 + ')').show();
            }
            if (!page_num) {
                page_num = Math.ceil(json.data.total / num_per_page);
                $("#Pagination").pagination(page_num, {
                    items_per_page: 1,
                    prev_text: "上一页",
                    next_text: "下一页",
                    num_display_entries: 5,
                    num_edge_entries: 3,
                    link_to: location.href + "#show",
                    callback: pageselectCallback
                });
            }
            checkjs();
        });
        //阻止单击事件
        return false;
    };
    pageselectCallback(0);
    //删除
    $('.a-table').on('click', '.delv', function () {
        var id = $(this).attr('name');
        $.post('', {id: id}, function (json) {
            if (json.err == 0) {
                json_url = json;
            }
        });
    });
    //批量删除
    $('.info-top .delmember').click(function (event) {
        var id_all = new Array();
        j = 1;
        $('input[name=checks]').each(function (i) {
            if ($(this).prop("checked")) {
                id_all[j] = $(this).parent().siblings().find('.delv').attr('name');
                j++;
            }
        });
        $.post('', {id: id_all}, function (json) {
            if (json.err == 0) {
                json_url = json;
            }
        });
    });

    //全选
    $('.checkall').change(function () {
        $('.mem_list input[name=checks]').prop('checked', $(this).prop('checked'));
    });
}