function articleController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    // defalut parame
    $_GET = $location.search();
    $scope.page = $_GET['p'] == undefined ? 1 : parseInt($_GET['p']);
    $scope.num_per_page = 10;
    $scope.page_num = 0;
    $scope.cat_id = $_GET['id'] == undefined ? null : parseInt($_GET['id']);
    $scope.is_star = $_GET['is_star'] == undefined ? null : parseInt($_GET['is_star']);
    $scope.ser_name = $_GET['ser_name'] == undefined ? null : $_GET['ser_name'];
    $scope.search_word = $_GET['search_word'] == undefined ? null : $_GET['search_word'];
//    console.log($scope);
//    console.log('===$scope===');
    // Model
    $scope.getArticleList = function (option) {
        var page = option.page || $scope.page,
                cat_id = option.cat_id || $scope.cat_id,
                num_per_page = option.num_per_page || $scope.num_per_page,
                page_num = option.page_num || $scope.page_num,
                is_star = option.is_star || $scope.is_star,
                ser_active = option.ser_active,
                search_word = option.search_word || $scope.search_word,
                ser_name = option.ser_name || $scope.ser_name;
        option.back == undefined ? '' : option.back == 1 ? window.location.hash = '#/article' : '';
        var urlparam = '';
        if (cat_id != null) {
            urlparam += '&c_id=' + cat_id;
        }
        if (is_star != null) {
            urlparam += '&is_star=' + is_star;
        }
        if (search_word != null) {
            urlparam += '&search_word=' + search_word;
        }
        if (page != null) {
            urlparam += '&page=' + page;
        }
        $http.get('../article-manage?per_page=' + num_per_page + urlparam).success(function (json) {
//            console.log(json.data);
//            console.log('article-manage');
            checkJSON(json, function (json) {
                if (option.first) {
                    checkjs('article');
                    // Catlist
                    var _div2 = '';
                    if (json.data != null) {
                        var column_d = json.data.catlist;
                        for (var i = 0; i < column_d.length; i++) {
                            if (column_d[i].p_id == 0 && column_d[i].type < 5) {
                                _div2 += '<li><a class="mov_val LevelChild1' + ((column_d[i].childmenu != null) || (column_d[i].type == 4) ? ' not-allowed' : '') + '" ' + (column_d[i].type == 4 ? 'data-type="page"' : '') + '><input value="' + column_d[i].id + '" style="display:none;" /><span>' + column_d[i].name + '</span></a></li>';
                                if (column_d[i].childmenu != null) {
                                    var NextChild = column_d[i];
                                    var num = 2;
                                    var LoopChlid = function (NextChild, num) {
                                        if (NextChild.childmenu != null) {
                                            $.each(NextChild.childmenu, function (k, v) {
                                                if (v.type < 5) {
                                                    _div2 += '<li><a class="mov_val LevelChild' + num + (v.childmenu != null ? ' not-allowed' : '') + '" ' + (v.type == 4 ? 'data-type="page"' : '') + '><input value="' + v.id + '" style="display:none;" /><span>├' + v.name + '</span></a></li>';
                                                    NextChild = v;
                                                    num++;
                                                    LoopChlid(NextChild, num);
                                                    num--;
                                                }
                                            });
                                        }
                                    }
                                    LoopChlid(NextChild, num);
                                }
                            }
                        }
                        $('.list_mov').html(_div2);
                        $('.list_new').html(_div2);
                        $('.list_new').find('.mov_val').removeClass('not-allowed');
                        $('.list_new').find('.mov_val[data-type="page"]').addClass('not-allowed');
                        $('.not-allowed').MoveBox({context: '此为单页类型或者父级分类下带有子级，不支持选择！'});
                    }
                }

                //===搜索条件===
                var urlparam = '';
                if (cat_id != null) {
                    urlparam += '&id=' + cat_id;
                }
                if (is_star != null) {
                    urlparam += '&is_star=' + is_star;
                }
                if (ser_name != null) {
                    urlparam += '&ser_name=' + ser_name;
                }
                if (search_word != null) {
                    urlparam += '&search_word=' + search_word;
                }
                ser_active ? window.location.hash = '#/article?p=1' + urlparam : '';
                if (cat_id != null) {
                    $('.article-tb .newarticle').addClass('navcolor');
                    $('.article-tb .newarticle span').text($scope.ser_name)
                    $('.article-tb .starback').fadeIn();
                }
                if (json.data != null && json.data.aticlelist != null) {
                    var article_d = json.data.aticlelist == undefined ? json.data : json.data.aticlelist.data;
                    var total = json.data.aticlelist == undefined ? json.total : json.data.aticlelist.total;
                    var _div = '<tr>\n\
                        <th>文章标题<div class="fr">|</div></th>\n\
                        <th>所属分类<div class="fr">|</div></th>\n\
                        <th>访问<div class="fr">|</div></th>\n\
                        <th>展示<div class="fr">|</div></th>\n\
                        <th>发布时间<div class="fr">|</div></th>\n\
                        <th>排序<div class="fr">|</div></th>\n\
                        <th>操作</th>\n\
                    </tr>\n\
                    <tr class="sapces"></tr>';
                    var now_page = getUrlParam('p') ? getUrlParam('p') : 1;
                    $.each(article_d, function (k, v) {
                        _div += '<tr class="article-check">\n\
                                        <td style="text-align: left">\n\
                                            <dl class="fl checkclass">\n\
                                                <input type="checkbox" name="checks" value="Bike1" style=" display:none;">\n\
                                                <label class="label"></label>\n\
                                                </dl><div class="tit_info"><input type="hidden" class="imgpre" value="' + v.img[0] + '"><input type="text" data-id="' + v.id + '" class="title_modify" style="display:none;" value=' + v.title + ' /><span class="sap_tit">' + v.title + (v.is_star ? '</span><img class="tit_pic" />' : '</span>') + (v.is_top ? '<i class="fa iconfont icon-zhiding mr5 pos_bule"></i>' : '') + '</div>\n\
                                        </td>\n\
                                        <td>' + v.c_name + '</td>\n\
                                        <td>' + v.viewcount + '</td>\n\
                                        <td>\n\
                                            <span><i class="fa iconfont icon-pc btn btn-show btn-desktop ' + (v.pc_show ? 'blue' : 'grey') + '"></i></span>\n\
                                            <span><i class="fa iconfont icon-snimicshouji btn btn-show btn-mobile ' + (v.mobile_show ? 'blue' : 'grey') + '"></i></span>\n\
                                        </td>\n\
                                        <td>' + v.created_at + '</td>\n\\n\
                                        <td><input class="sort" type="text" data-id="' + v.id + '"  value="' + ((v.sort == 1000000) ? '' : v.sort) + '" /></td>\n\
                                        <td><a style="margin:0 10px;" class="column-edit pr" href="#/addarticle?id=' + v.id + '&c_id=' + v.c_id + '&p=' + now_page + '"><i class="fa iconfont icon-bianji"></i><div class="warning"><i class="iconfont' + (v.img_err ? ' icon-gantanhao' : '') + '"></i></div></a><a class="delv" name="' + v.id + '"><i class="fa iconfont icon-delete mr5"></i></a></td>\n\
                                    </tr>';
                    });
                    $('.a-table').html(_div);
                    $scope.ArticleNav.batchEdit();
                    star_top();
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
                            var urlparam = '';
                            if (cat_id != null) {
                                urlparam += '&id=' + cat_id;
                            }
                            if (is_star != null) {
                                urlparam += '&is_star=' + is_star;
                            }
                            if (ser_name != null) {
                                urlparam += '&ser_name=' + ser_name;
                            }
                            window.location.hash = '#/article?p=' + (page_index + 1) + urlparam;
                        }
                    });
                } else {
                    $('.a-table').html('<tr>\n\
                            <th>文章标题<div class="fr">|</div></th>\n\
                            <th>所属分类<div class="fr">|</div></th>\n\
                            <th>访问<div class="fr">|</div></th>\n\
                            <th>展示<div class="fr">|</div></th>\n\
                            <th>发布时间<div class="fr">|</div></th>\n\
                            <th>排序<div class="fr">|</div></th>\n\
                            <th>操作</th>\n\
                        </tr>\n\
                        <tr class="sapces"></tr>');
                    // 分页样式显示
                    page_num2 = 0;
                    $("#Pagination").pagination(page_num2);
                } // if判断结束
                $('body').append('<img id="imgpre" style="display:none;width:100px;" src="images/logo.png" />');
                $('tr .sap_tit').mouseover(function (e) {
                    var x = e.pageX;
                    var y = e.pageY - $('body').scrollTop();

                    var imgpre = $(this).parent('div').find(".imgpre").val();
                    if (imgpre.length) {
                        $('#imgpre').show();
                        $('#imgpre').attr('src', json.data.source_dir + imgpre);
                        $('#imgpre').css({
                            "position": "fixed",
                            "left": x + "px",
                            "top": y + 'px',
                        });
                    }
                });
                $('tr .sap_tit').mouseout(function () {
                    $('#imgpre').hide();
                });
                //文章标题修改
                $(".tit_info .sap_tit").click(function () {
                    $(this).hide();
                    $(this).parent('div').find(".title_modify").show();
                    $(this).parent('div').find(".title_modify").focus().val($(this).parent('div').find(".title_modify").val());
                });
                $(".title_modify").blur(function () {
                    if ($(this).val() !== $(this).parent("div").find(".sap_tit").text()) {
                        $(this).parent("div").find(".sap_tit").text($(this).val());
                        var title = $(this).val();
                        var id = $(this).data("id");
                        $http.post('../article-title-modify', {id: id, title: title}).success(function (json) {
                            checkJSON(json, function (json) {
                                var hint_box = new Hint_box();
                                hint_box;
                            });
                        });
                    }
                    $(this).parent("div").find(".sap_tit").show();
                    $(this).hide();
                });
            }); // checkJSON结束
        });
    };

    //菜单
    (function ArticleMenu() {
        var timer;
        $('.movarticle').click(function () {
            $(this).find('i').hasClass('icon-iconfont57') ? $(this).find('i').removeClass('icon-iconfont57').addClass('icon-xiangxia') : $(this).find('i').removeClass('icon-xiangxia').addClass('icon-iconfont57');
            $(this).siblings('.list_mov').slideToggle(function () {
                var area = parseInt($('#main').height()) - parseInt($('.whole').css('paddingTop')) - parseInt($('.whole').css('paddingBottom'));
                parseInt($('.list_mov').height()) > area ? $('.list_mov').css({'overflow-y': 'scroll', 'overflow-x': 'hidden', 'height': area - 1}) : '';
            });
        });
        $('.setarticle').click(function () {
            $(this).find('i').hasClass('icon-iconfont57') ? $(this).find('i').removeClass('icon-iconfont57').addClass('icon-xiangxia') : $(this).find('i').removeClass('icon-xiangxia').addClass('icon-iconfont57');
            $(this).siblings('.list_set').slideToggle();
        });
        $('.newarticle').click(function () {
            $(this).find('i').hasClass('icon-iconfont57') ? $(this).find('i').removeClass('icon-iconfont57').addClass('icon-xiangxia') : $(this).find('i').removeClass('icon-xiangxia').addClass('icon-iconfont57');
            $(this).parent().siblings('.stararticle').css({'background-color': '#7589a0'});
            $(this).siblings('.list_new').slideToggle(function () {
                var area = parseInt($('#main').height()) - parseInt($('.whole').css('paddingTop')) - parseInt($('.whole').css('paddingBottom'));
                parseInt($('.list_new').height()) > area ? $('.list_new').css({'overflow-y': 'scroll', 'overflow-x': 'hidden', 'height': area - 1}) : '';
            });
        });
        $('.mov,.set,.new').mouseenter(function (event) {
            clearTimeout(timer);
        }).mouseleave(function () {
            var _this = $(this);
            timer = setTimeout(function () {
                _this.children('ul').fadeOut();
                _this.find('i').hasClass('icon-iconfont57') && _this.find('.list_new').is(':visible') ? _this.find('i').removeClass('icon-iconfont57').addClass('icon-xiangxia') : _this.find('i').removeClass('icon-xiangxia').addClass('icon-iconfont57');
            }, 2000)
        }).click(function () {
            $('.info-top ul').not($(this).find('ul')).fadeOut();
        });
    })();

    // 取消置顶、推荐
    function star_top() {
        $('.article-tb .a-table .article-check .tit_info .pos_bule').on('click', function () {
            console.log('===star_top===');
            var id = $(this).parents('td').siblings().find('.delv').attr('name');
            if ($(this).hasClass('pos_bule')) {
                $(this).removeClass('pos_bule');
                $(this).addClass('pos_grey');
                cancel_crl(id, "set_top", 0);
            } else {
                $(this).removeClass('pos_grey');
                $(this).addClass('pos_bule');
                cancel_crl(id, "set_top", 1);
            }
        });
        $('.article-tb .a-table .article-check .tit_info').on('click', '.tit_pic', function () {
            var id = $(this).parents('td').siblings().find('.delv').attr('name');
            if ($(this).hasClass('cancle_star')) {
                $(this).removeClass('cancle_star');
                $(this).css('background-position', '0px 0px');
                cancel_crl(id, "set_star", 1);
            } else {
                $(this).addClass('cancle_star');
                $(this).css('background-position', '0px -34px');
                cancel_crl(id, "set_star", 0);
            }
        });

        function cancel_crl(id, action, values) {
            $http.post('../article-batch-modify', {id: id, action: action, values: values}).success(function () {
                var hint_box = new Hint_box();
                hint_box;
            });
        }
    }
    //获取勾选id
    var all_id = function () {
        id_all = new Array();
        var j = 0;
        $('.article-check input[name=checks]').each(function (i) {
            if ($(this).attr('checked')) {
                id_all[j] = $(this).parents('td').siblings().find('.delv').attr('name');
                j++;
            }
        });
        return id_all;
    };

    $scope.ArticleNav = {
        init: function () {
            // 首次获取
            $scope.getArticleList({
                first: true
            });
            this._checkstar();
            this._searchInfo();
            this._moveclassify();
            this._setInfoClassify();
            this._sort();
            this._delete();
            this._batchdel();
            this._showPlatform();
            this._batchAdd();
            this._searchWords();//===模糊查找===
            // this._batchEdit();
        },
        // 查看推荐以及返回
        _checkstar: function () {
            $('.article-tb .stararticle').unbind('click').click(function () {
                console.log('_checkstar');
                if ($(this).hasClass('starback')) {
                    // 返回
                    $scope.cat_id = null;
                    $scope.is_star = null;
                    $scope.page = 1;
                    $scope.getArticleList({
                        first: true,
                        back: 1
                    });
                    $('.article-tb .newarticle span').text('分类查找');
                    $(this).prev().css({'background-color': '#7589a0'});
                    $(this).siblings('.new').children('.newarticle').removeClass('navcolor');
                    $(this).fadeOut();
                } else {
                    // 查看
                    $scope.page = 1;
                    $scope.getArticleList({
                        first: false,
                        is_star: 1
                    });
                    $(this).css({'background-color': '#B0B0B0'});
                    $(this).next().fadeIn();
                }
            });
        },
        //新闻分类搜索
        _searchInfo: function () {
            $('.info-top .list_new').off('click').on('click', ' .mov_val:not(.not-allowed)', function () {
                console.log('_searchInfo');
                $scope.page = 1;
                $(this).parents('ul').prev().addClass('navcolor');
                $('.article-tb .stararticle').next().fadeIn();
                $(this).parents('ul').fadeOut();
                $scope.cat_id = $(this).find('input').val();
                $scope.getArticleList({
                    first: false,
                    ser_active: true,
                    ser_name: $(this).find('span').text()
                });
            });
        },
        //移动分类
        _moveclassify: function () {
            $('.info-top .list_mov').off('click').on('click', '.mov_val:not(.not-allowed)', function () {
                console.log('_moveclassify');

                mov_id = $(this).find('input').val();
                var id_all = new all_id;
                var mov_text = $(this).text();
                $(this).parents('.list_mov').fadeOut();
                $http.post('../article-move-classify', {id: id_all, target_catid: mov_id}).success(function (json) {
                    checkJSON(json, function (json) {
                        // 设为实时显示
                        $('.article-tb .a-table .article-check label[class*="nchecked"]').parents('td').siblings('td:nth-of-type(2)').text(mov_text);
                        var hint_box = new Hint_box();
                        hint_box;
                    });
                });
            });
        },
        _setInfoClassify: function () {
            var r_this = this;
            $('.list_set a').unbind('click').click(function () {
                console.log('_setInfoClassify');
                var set_name = $(this).attr('name'),
                        set_val = $(this).attr('value');
                if (id_all = null) {
                    alert('请选择更改的文章！')
                }
                $(this).parents('.list_set').fadeOut();
                $http.post('../article-batch-modify', {id: all_id(), action: set_name, values: set_val}).success(function (json) {
                    checkJSON(json, function (json) {
                        // 设为实时显示
                        var _this = $('.article-tb .a-table .article-check label[class*="nchecked"]');
                        switch (set_name) {
                            case "set_star":
                                if (set_val == 1) {
                                    _this.parent().siblings('.tit_info').find('.tit_pic').remove();
                                    _this.parent().siblings('.tit_info').append('<img class="tit_pic" />');
                                } else {
                                    _this.parent().siblings('.tit_info').children('.tit_pic').addClass('cancle_star').css('background-position', '0px -34px');
                                }
                                break;
                            case "set_top":
                                if (set_val == 1) {
                                    _this.parent().siblings('.tit_info').find('.pos_bule').remove();
                                    _this.parent().siblings('.tit_info').append('<i class="fa iconfont icon-zhiding pos_bule"></i>');
                                } else {
                                    _this.parent().siblings('.tit_info').find('.pos_bule').css('color', 'rgb(158,158,158)');
                                }
                                break;
                            case "set_pcshow":
                                r_this.ModelSetIsShow(_this, set_val, '.btn-desktop');
                                break;
                            case "set_mobileshow":
                                r_this.ModelSetIsShow(_this, set_val, '.btn-mobile');
                                break;
                            case "set_wechatshow":
                                r_this.ModelSetIsShow(_this, set_val, '.btn-wechat');
                                break;
                        }//switch结束
                        var hint_box = new Hint_box();
                        hint_box;
                    });
                });// POST请求结束
            });
        },
        ModelSetIsShow: function (_this, set_val, selector) {
            console.log('ModelSetIsShow');
            _this.parents('td').siblings().find('span ' + selector).each(function () {
                _this.parents('td').siblings().find('span ' + selector).removeClass((set_val == 1 && !$(this).hasClass('blue') ? 'grey' : $(this).hasClass('blue') ? 'blue' : null))
                        .addClass((set_val == 1 && !$(this).hasClass('blue') ? 'blue' : $(this).hasClass('blue') ? 'grey' : null));
            });
        },
        //排序设置
        _sort: function () {
            $('.a-table').on('change', '.sort', function () {
                console.log('_sort');

                var id, sort;
                id = $(this).data('id');
                sort = $(this).val();
                $http.post('../article-sort-modify', {id: id, sort: sort}).success(function (json) {
                    checkJSON(json, function (json) {
//                                    $('.a-table').children().remove();
//                                    $scope.getArticleList({
//                                        first : false
//                                    });
                        var hint_box = new Hint_box();
                        hint_box;
                    });
                });
            });
        },
        //删除
        _delete: function () {
            $('.a-table').off('click').on('click', '.delv', function () {
                console.log('_delete');

                var id = $(this).attr('name');
                var _this = $(this);
                (function article_delete(del_num) {
                    if (del_num == undefined) {
                        var warningbox = new WarningBox(article_delete);
                        warningbox.ng_fuc();
                    } else {
                        if (del_num) {
                            $http.post('../article-delete', {id: id}).success(function (json) {
                                checkJSON(json, function (json) {
                                    _this.parent().parent().remove();
                                    var hint_box = new Hint_box();
                                    hint_box;
                                });
                            });
                        }
                    }
                })();
            });
        },
        //批量删除
        _batchdel: function () {
            $('.info-top .delarticle').unbind('click').click(function (event) {
                console.log('_batchdel');

                (function article_delete(del_num) {
                    if (del_num == undefined) {
                        var warningbox = new WarningBox(article_delete);
                        warningbox.ng_fuc();
                    } else {
                        if (del_num) {
                            var id_all = new all_id;
                            $('.article-check .nchecked').parents('.article-check').remove();
                            $http.post('../article-delete', {id: id_all}).success(function (json) {
                                checkJSON(json, function (json) {
                                    var hint_box = new Hint_box();
                                    hint_box;
                                });
                            });
                        }
                    }
                })();
            });
        },
        //展示
        _showPlatform: function () {
            $('.a-table').off('click').on('click', '.btn-show', function () {
                console.log('_showPlatform');

                var btn = $(this);
                var voperate = '';
                if (btn.hasClass('icon-pc')) {
                    voperate = 'set_pcshow';
                } else if (btn.hasClass('icon-snimicshouji')) {
                    voperate = 'set_mobileshow';
                } else {
                    voperate = 'set_wechatshow';
                }
                var vid = btn.parents('td').siblings().find('.delv').attr('name');
                if (btn.hasClass('blue')) {
                    var vvaule = 0;
                } else {
                    var vvaule = 1;
                }
                $http.post('../article-batch-modify', {id: vid, action: voperate, values: vvaule}).success(function (json) {
                    checkJSON(json, function (json) {
                        if (btn.hasClass('blue')) {
                            btn.removeClass('blue').addClass('grey');
                        } else {
                            btn.removeClass('grey').addClass('blue');
                        }
                        var hint_box = new Hint_box();
                        hint_box;
                    });
                });
            });
        },
        // 批量添加文章
        _batchAdd: function () {
            var _this = this;
            $('.info-top .batchadd').unbind('click').click(function () {
                console.log('_batchAdd');

                var warningbox = new WarningBox();
                warningbox._upImage({
                    IsBaseShow: true,
                    ajaxurl: '',
                    IsMultiple: true,
                    oncallback: function (json) {
                        var html = '', ids = [];
                        $('.save_column,.batchbtn .save').css('cursor', 'pointer');
                        $('#inputImage-queue').append('<span class="finish">全部完成！</span>');
                        $(".uploadify-queue").animate({scrollTop: $('.uploadify-queue')[0].scrollHeight}, 1000);
                        $('.batchbtn .save').unbind().click(function () {
                            if (confirm('是否确定，并且批量生成文章？')) {
                                var articleArray = new Array();
                                var img_upload = [];
                                var pc_show = $('.classify input[value=pc_show][checked]').val() ? '1' : '0';
                                var mobile_show = $('.classify input[value=mobile_show][checked]').val() ? '1' : '0';
                                var c_id = $('.classify input[name=column_name]').val();
                                var title_empty = 0;
                                $('.batch_title .article').each(function () {
                                    var title = $(this).children(".title").val();
                                    if (title == '') {
                                        title_empty = 1;
                                        return false;
                                    }
                                    img_upload.push($(this).children(".img").val());
                                    articleArray.push({
                                        img: $(this).children(".img").val(),
                                        title: title,
                                    });
                                });
                                if (title_empty) {
                                    alert("标题不能为空！");
                                    return false;
                                }
                                if (!$(".my_mask").attr('class')) {
                                    var fade = '<div class="tpl_mask my_mask" style="display: block;"></div><div class="text_tishi my_tishi">努力保存中<i class="icon-spin4 iconfont icon-shuaxin"></i></div>';
                                    $('body').append(fade);
                                }
                                $('.my_mask').show();
                                $('.my_tishi').show();
                                $http.post('../article-batch-add', {ArticleBatch: articleArray, pc_show: pc_show, mobile_show: mobile_show, c_id: c_id}).success(function (json) {
                                    checkJSON(json, function (json) {
                                        if (img_upload.length) {
                                            $http.post('../imgupload?target=articles', {files: img_upload}).success(function () {
                                                $('.my_mask').hide();
                                                $('.my_tishi').hide();
                                            });
                                        }
                                        $('.warning_box ').hide().prev().hide();
                                    });
                                });
                            } else {
                                return false;
                            }
                        });
                    }
                });
            });
        },
        // 批量编辑文章
        batchEdit: function () {
            $('.info-top .batchedit').unbind('click').click(function () {
                console.log('batchEdit');

                var id_all = new all_id;
                if (id_all == '') {
                    alert('请选择要编辑的文章！')
                } else {
                    window.location.hash = '#/batcharticle?ids=' + id_all + '';
                }
            });
        },
        //===搜索关键词===
        _searchWords: function () {
            $('.searcharticle').unbind('click').click(function () {
                console.log('_searchWords');

                var search_word = $("[name='search_word']").val();
                $scope.getArticleList({
                    first: false,
                    ser_active: true,
                    search_word: search_word
                });
//                $scope.getArticleList({
//                    first: false,
//                    ser_active: true,
//                    ser_name: $(this).find('span').text()
//                });
            });
        }
    };
    //===加载初始化===
    $scope.ArticleNav.init();
}