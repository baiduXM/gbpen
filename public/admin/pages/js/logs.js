/**
 * 文章控制器
 * @param {type} $scope
 * @param {type} $http
 * @param {type} $location
 * @returns {undefined}
 */
function logsController($scope, $http, $location) {
//    console.log($scope);
//    console.log('===1===');
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    // defalut parame
    $_GET = $location.search();
    $scope.page = $_GET['p'] == undefined ? 1 : parseInt($_GET['p']);
    $scope.num_per_page = 10;
    $scope.page_num = 0;
    var ser_name = '分类查找';
    // 下拉框模拟事件
    var PageId = [];
    DropdownEvent(PageId);
    /**
     * 文章列表Model
     * @param {type} option
     * @returns {undefined}
     */
    $scope.getLogsList = function (option) {
        
//               if(option.type != null){
//                   alert(option.type);
//               }
//               if(option.time1 != null){
//                   alert(option.time1);
//                   alert(option.time2);
//               }
        console.log(option);
//        console.log('===2===');
        var page = option.page || $scope.page, //===当前页码
                num_per_page = option.num_per_page || $scope.num_per_page, //===每页几条
                page_num = option.page_num || $scope.page_num, //===页数
                search_word = option.search_word || $scope.search_word;//===关键字
        option.back == undefined ? '' : option.back == 1 ? window.location.hash = '#/logs' : '';
        var urlparam = '';
        if (option.type != null) {
            urlparam += '&type=' + option.type;
        }
        if (option.time1 != null) {
            urlparam += '&time1=' + option.time1;
            urlparam += '&time2=' + option.time2;
        }
        if (option.username != null) {
            urlparam += '&username=' + option.username;
        }
        if (page != null) {
            urlparam += '&page=' + page;
        }
        if (num_per_page != null) {
            urlparam += '&per_page=' + num_per_page;
        }
        $http.get('../logs-list?1' + urlparam).success(function (json) {
            checkJSON(json, function (json) {
                if (option.first) {
//                    checkjs('logs');
                    // Catlist
//                    var _div2 = '';
//                    if (json.data != null) {
//                        var column_d = json.data.catlist;
//                        for (var i = 0; i < column_d.length; i++) {
//                            if (column_d[i].p_id == 0 && column_d[i].type < 5) {
//                                _div2 += '<li><a class="mov_val LevelChild1' + ((column_d[i].childmenu != null) || (column_d[i].type == 4) ? ' not-allowed' : '') + '" ' + (column_d[i].type == 4 ? 'data-type="page"' : '') + '><input value="' + column_d[i].id + '" style="display:none;" /><span>' + column_d[i].name + '</span></a></li>';
//                                if (column_d[i].childmenu != null) {
//                                    var NextChild = column_d[i];
//                                    var num = 2;
//                                    var LoopChlid = function (NextChild, num) {
//                                        if (NextChild.childmenu != null) {
//                                            $.each(NextChild.childmenu, function (k, v) {
//                                                if (v.type < 5) {
//                                                    _div2 += '<li><a class="mov_val LevelChild' + num + (v.childmenu != null ? ' not-allowed' : '') + '" ' + (v.type == 4 ? 'data-type="page"' : '') + '><input value="' + v.id + '" style="display:none;" /><span>├' + v.name + '</span></a></li>';
//                                                    NextChild = v;
//                                                    num++;
//                                                    LoopChlid(NextChild, num);
//                                                    num--;
//                                                }
//                                            });
//                                        }
//                                    }
//                                    LoopChlid(NextChild, num);
//                                }
//                            }
//                        }
//                        $('.list_mov').html(_div2);
//                        $('.list_new').html(_div2);
//                        $('.list_new').find('.mov_val').removeClass('not-allowed');
//                        $('.list_new').find('.mov_val[data-type="page"]').addClass('not-allowed');
//                        $('.not-allowed').MoveBox({context: '此为单页类型或者父级分类下带有子级，不支持选择！'});
//                    }
                }

                //===搜索条件===
//                var urlparam = '';
//                if (cat_id != null) {
//                    urlparam += '&id=' + cat_id;
//                }
//                if (is_star != null) {
//                    urlparam += '&is_star=' + is_star;
//                }
//                if (ser_name != null) {
//                    urlparam += '&ser_name=' + ser_name;
//                }
//                if (search_word != null) {
//                    urlparam += '&search_word=' + search_word;
//                }
//                ser_active ? window.location.hash = '#/article?p=1' + urlparam : '';
//                if (cat_id != null) {
//                    $('.article-tb .newarticle').addClass('navcolor');
//                    $('.article-tb .newarticle span').text(ser_name)//。。。
//                    $('.article-tb .starback').fadeIn();
//                }
                
                if (json.data != null && json.data.data != null) {
                    var article_d = json.data.data == undefined ? json.data : json.data.data;
                    var total = json.data == undefined ? json.data.data.total : json.data.total;
                    var _div = '<tr>\n\
                        <th>操作描述<div class="fr">|</div></th>\n\
                        <th>操作类型<div class="fr">|</div></th>\n\
                        <th>操作用户<div class="fr">|</div></th>\n\
                        <th>操作ip<div class="fr">|</div></th>\n\
                        <th>操作时间<div class="fr">|</div></th>\n\
                        <th>操作对象id或cus_id<div class="fr"></div></th>\n\
                    </tr>\n\
                    <tr class="sapces"></tr>';
                    var now_page = getUrlParam('p') ? getUrlParam('p') : 1;
                    $.each(article_d, function (k, v) {
                        _div += '<tr class="article-check">\n\
                                        <td style="text-align: left">\n\
                                                </dl><div class="tit_info"><span class="sap_tit" style="display:block !important;overflow:visible !important;text-overflow:none !important;">' + v.operation_describe + '</span></div>\n\
                                        </td>\n\
                                        <td>' + v.operation_type + '</td>\n\
                                        <td>' + v.username + '</td>\n\
                                        <td>' + v.ip + '\n\
                                        </td>\n\
                                        <td>' + v.operation_time + '</td>\n\\n\
                                        <td>' + v.fk_id + '</td>\n\
                                         </tr>';
                    });
                    $('.a-table').html(_div);
//                    $scope.ArticleNav.batchEdit();
                    // 分页样式显示
                    page_num = Math.ceil(total / num_per_page);
                    $("#Pagination").pagination(page_num, {
                        current_page: (page - 1),
                        items_per_page: 1,
                        prev_text: "上一页",
                        next_text: "下一页",
                        num_display_entries: 5,
                        num_edge_entries: 3,
                        callback: function (page_index) {
//                            var urlparam = '';
//                            if (cat_id != null) {
//                                urlparam += '&id=' + cat_id;
//                            }
//                            if (is_star != null) {
//                                urlparam += '&is_star=' + is_star;
//                            }
//                            if (ser_name != null) {
//                                urlparam += '&ser_name=' + ser_name;
//                            }
//                            if (page_index != null) {
//                                urlparam += '&p=' + (page_index + 1);
//                            }
                            if(option.time1 != '' && option.time1 != undefined){
                                if(option.time2 == '' || option.time2 == undefined){
                                    alert("结束日期必须设置");
                                }else if(option.type != '0' && option.type != undefined){
            //                        有两个日期和类型
                                    $scope.getLogsList({
                                        first: false,
                                        page: page_index + 1,
                                        time1: option.time1,
                                        time2: option.time2,
                                        type: option.type
                                    });
                                }else{
            //                        有两个日期
                                    $scope.getLogsList({
                                        first: false,
                                        page: page_index + 1,
                                        time1: option.time1,
                                        time2: option.time2
                                    });
                                }
                            }else if(option.type != '' && option.type != undefined && option.type != '0'){
            //                    只有类型
                                $scope.getLogsList({
                                    first: false,
                                    page: page_index + 1,
                                    type: option.type
                                });
                            }else{
            //                    都没有
                                $scope.getLogsList({
                                    first: false,
                                    page: page_index + 1
                                });
                            }
//                            $scope.getLogsList({
//                                first: false,
//                                page: page_index + 1,
//                            });

//                            window.location.hash = '#/article?1' + urlparam;
                        }
                    });
                } else {
                    $('.a-table').html('<tr>\n\
                        <th>操作用户<div class="fr">|</div></th>\n\
                        <th>操作类型<div class="fr">|</div></th>\n\
                        <th>操作描述<div class="fr">|</div></th>\n\
                        <th>操作ip<div class="fr">|</div></th>\n\
                        <th>操作时间<div class="fr">|</div></th>\n\
                        <th>操作对象id<div class="fr"></div></th>\n\
                    </tr>\n\
                    <tr class="sapces"></tr>');
                    // 分页样式显示
                    page_num2 = 0;
                    $("#Pagination").pagination(page_num2);
                } // if判断结束
                $('body').append('<img id="imgpre" style="display:none;width:100px;" src="images/logo.png" />');

            }); // checkJSON结束
        });
    };
    // 首次获取
    $scope.getLogsList({
        first: true,
        ser_name: ser_name,
    });
    

    
    

    $scope.ArticleNav = {
        init: function () {
            this._searchWords();//===模糊查找===
            // this._batchEdit();
        },
        //===搜索关键词===
        _searchWords: function () {
            $('.searchlogs').unbind('click').click(function () {
                var time1 = $('#time1').val();
                var time2 = $('#time2').val();
                var username = $('#username').val();
                var type = $('.selectBox_val').val();
                var cha = (Date.parse(time2) - Date.parse(time1));
                if(cha<1){
                    alert("结束时间不能大于或等于开始时间");
                }else{
                    if(time1 != '' && time1 != undefined){
                        if(time2 == '' || time2 == undefined){
                            alert("结束日期必须设置");
                        }else if(type != '0' && type != undefined && username != '' && username != undefined){
//                            全有
                            $scope.getLogsList({
                                first: true,
                                time1: time1,
                                time2: time2,
                                type: type,
                                username: username
                            });
                        }else if(type != '0' && type != undefined){
//                            有两个日期和类型
                            $scope.getLogsList({
                                first: true,
                                time1: time1,
                                time2: time2,
                                type: type
                            });
                        }else if(username != '' && username != undefined){
//                            有两个日期和用户
                            $scope.getLogsList({
                                first: true,
                                time1: time1,
                                time2: time2,
                                username: username
                            });
                        }else{
//                            有两个日期
                            $scope.getLogsList({
                                first: true,
                                time1: time1,
                                time2: time2
                            });
                        }
                    }else if(type != '' && type != undefined && type != '0'){
                        if(username != '' && username != undefined){
//                            有类型和用户
                            $scope.getLogsList({
                                first: true,
                                type: type,
                                username: username
                            });
                        }else{
//                            只有类型
                            $scope.getLogsList({
                                first: true,
                                type: type
                            });
                        }
                    }else if(username != '' && username != undefined){
//                            只有用户
                            $scope.getLogsList({
                                first: true,
                                username: username
                            });
                    }else{
    //                    都没有
                        $scope.getLogsList({
                            first: true
                        });
                    }
                 
                }

            });
        }
    }
    //===加载初始化===
    $scope.ArticleNav.init();
}
