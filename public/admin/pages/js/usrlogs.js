/**
 * 日志控制器
 * @param {type} $scope
 * @param {type} $http
 * @param {type} $location
 * @returns {undefined}
 */
function usrlogsController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    // 下拉框模拟事件
    var PageId = [];
    DropdownEvent(PageId);

    $scope.getLogsList = function (option) {
        var urlparam = '';
        if (option.type) {
            urlparam += '&type=' + option.type;
        }
        if(option.date) {
            urlparam += '&date=' + option.date;
        }
        $http.get('../usr-list?1' + urlparam).success(function (json) {
            checkJSON(json, function (json) {
                if(json.err == 1000) {
                    var data = json.data;
                    var _div = '<tr>\n\
                        <th>操作描述<div class="fr">|</div></th>\n\
                        <th>操作类型<div class="fr">|</div></th>\n\
                        <th>操作ip<div class="fr">|</div></th>\n\
                        <th>操作时间<div class="fr"></div></th>\n\
                    </tr>\n\
                    <tr class="sapces"></tr>';
                    $.each(data, function (k, v) {
                        _div += '<tr class="article-check">\n\
                                <td style="text-align: left">\n\
                                        </dl><div class="tit_info"><span class="sap_tit" style="display:block !important;overflow:visible !important;text-overflow:none !important;">' + v.operation_describe + '</span></div>\n\
                                </td>\n\
                                <td>' + v.operation_type + '</td>\n\
                                <td>' + v.ip + '</td>\n\
                                <td>' + v.operation_time + '</td>\n\
                                </tr>';
                    });
                    $('.a-table').html(_div);
                }
            }, function(json) { $('.a-table').html(json.msg); });
        });
    }

    // 首次获取
    $scope.getLogsList({});

    $scope.ArticleNav = {
        init: function () {
            this._searchWords();//===模糊查找===
        },
        //===搜索关键词===
        _searchWords: function () {
            $('.searchlogs').unbind('click').click(function () {
                var type = $('.selectBox_val').val();
                var date = $('.creat_time').val();
                $('.a-table').html('正在加载。。。');
                $scope.getLogsList({
                    type: type,
                    date: date
                });

            });
        }
    }
    //===加载初始化===
    $scope.ArticleNav.init();
}
