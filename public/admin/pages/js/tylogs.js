function tylogsController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = false;
    $scope.$parent.menu = [];

    getSyslogs();
    
    //获取系统日志列表
    function getSyslogs(){
        $.get('../syslogs-get',  function (json) {
            var data = json.msg;
            var _div = '<tr>\n\
                    <th>版本<div class="fr">|</div></th>\n\
                    <th>简介<div class="fr">|</div></th>\n\
                    <th>更新日期<div class="fr">|</div></th>\n\
                    <th>查看</th>\n\
            </tr>\n\
            <tr class="sapces"></tr>';
            if (data != null) {
                $.each(data, function (k, v) {
                    _div += '<tr class="form-check" data-id="' + v.id + '">\n\
                                <td style="text-align: left">\n\
                                        <dl class="fl checkclass">\n\
                                                <input type="checkbox" name="checks" value="Bike1" style=" display:none;">\n\
                                                <label class="label"></label>\n\
                                        </dl>\n\
                                        <div class="tit_info"><span class="sap_tit">' + v.title + '</span></div>\n\
                                </td>\n\
                                <td>' + v.synopsis + '</td>\n\
                                <td>' + v.updatetime + '</td>\n\
                                <td>\n\
                                    <a style="margin:0 10px; cursor: pointer" class="log_check" title="查看"><i class="fa iconfont icon-dengpao1"></i></a>\n\
                                </td>\n\
                            </tr>';
                });
            } else {
                _div += "<tr><td colspan='4'>" + data + "</td></tr>";
            }
            $('.a-table').html(_div);
            //===查看日志详情===
            $('.log_check').unbind('click').click(function () {
                var id = $(this).parents('tr').attr('data-id');
                getSyslog(id);
            });
        });
    }

    //获取日志详情
    function getSyslog(id){
        $.get('../syslogs-get',{id:id},  function (json) {
            var data = json.msg;
            if(data != null){
                var _div1 = '';
                _div1 += '<li class="data-li clearfix">';
                _div1 += data['content'];
                _div1 += '</li>';
            } else {
                _div1 += '<li class="data-li clearfix">';
                _div1 += '内容获取失败';
                _div1 += '</li>';
            }
            $('.as-title').html(data['title']);
            $('.as-description').html(data['synopsis']);
            $('.element-show').html(_div1);
        });
    }

}

