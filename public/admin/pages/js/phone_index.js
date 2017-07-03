function phone_indexController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    $_GET = $location.search();

    $scope.phoneIndexInit = function () {
        this.templePage = 'index';
        this.maininfourl = '../mhomepage-data';
        // this.maininfourl = 'json/phone_index.json';
        this.init();
    }
    $scope.phoneIndexInit.prototype = {
        init: function () {
            this.imageType = {};
            this.quickbarType = [];
            this.otherType = {};
            this._loading();
            this._getInfo();
        },
        _loading: function () {
            if ($scope.$parent.phonepreview) {
                $('#phone').data('phoneClosed_index', true);
                $('body').removeClass('closephone');
                setTimeout(function () {
                    width_resize();
                }, 4);
            }
        },
        _getInfo: function () {
            var Area_name = $_GET['Area'] || 'index', _this = this;
            if (Area_name == 'quickbar') {
                $('.phone_index_btn .delcolumn,.phone_index_btn .mobile_super,.phone_index-banner .navs').remove();
                $('.phone_index_btn .save').show();
                $('.phone_index_btn .rewrite').show();
//				$('#phone_index .phone_index_btn').width(122)
            }
            $('#phone_index-' + Area_name).show();
            // 获取手机切换标签
            $http.get(this.maininfourl).success(function (json) {
                checkJSON(json, function (json) {
                    var data = json.data.pagelist,
                            mpagetype, type,
                            templeType = 2;
                    if (json.data.coded == 0) {
                        $('.mobile_super').css('backgroundColor', '#888');
                    } else {
                        $('.mobile_super').css('backgroundColor', '#3878c0');
                    }
                    //手机高级定制
                    $('.mobile_super').unbind('click').click(function () {
                        if (json.data.coded) {
                            location.href = '#/diytpl?type=' + templeType + '';
                        } else {
                            var warningbox = new WarningBox('', {warning_context: '∑(っ°Д ° )っ此定制编辑需要有一定的代码基础，确定要对文件进行编辑吗？'});
                            warningbox.idx_fuc(templeType);
                        }
                    })
                    // 标签切换修改保存按钮类名
                    $('.phone_index-banner .navs li a').on('click', function () {
                        var page = $(this).data('sub').split("-");
                        window.location.hash = '#/phone_index?Area=' + page[1];
                    });
                    if (Area_name != _this.templePage) {
                        $.each(json.data.templatedata, function (idx, ele) {
                            switch (ele.type) {
                                case "image":
                                case "images":
                                    $('.page_navs a[data-sub="phone_index-images"]').removeClass('not-allowed');
                                    _this.imageType[idx] = ele;
                                    break;
                                case "quickbar":
                                    _this.quickbarType = ele;
                                    break;
                                case "text":
                                case "textarea":
                                case "navs":
                                    $('.page_navs a[data-sub="phone_index-other"]').removeClass('not-allowed');
                                    _this.otherType[idx] = ele;
                                    break;
                            }
                        });
                        if (Area_name == 'images') {
                            mpagetype = 'images';
                            var SlidepicsInit = new $scope.phoneIndexSlidepics(_this.imageType);
                        }
                        if (Area_name == 'quickbar') {
                            mpagetype = 'quickbar';
                            $http.get('../quickbar.jsoninit').success(function (json) {
                                checkJSON(json, function (json) {
                                    var QuickbarInit = new $scope.phoneIndexQuickbar(json.data);
                                });
                            });
                        }
                        if (Area_name == 'other') {
                            mpagetype = 'other';
                            var OtherInit = new $scope.phoneIndexOther(_this.otherType);
                        }
                    } else {
                        // 栏目排序
                        var init = new $scope.phoneIndexPageList(json.data.m_catlist);
                        mpagetype = 'index';
                    }
                    // 标签切换
                    _this.change_sub(Area_name, mpagetype);
                });
            });
        },
        Save_hint: function () {
            // 保存延时效果
            var Countdown = 2;
            function inside_hint() {
                $(".tpl_mask").show();
                $(".text_tishi").show();
                if (Countdown > 0) {
                    $('#phone_index .text_tishi').text('努力保存中...(' + Countdown + ')')
                    Countdown--;
                    setTimeout(inside_hint, 1000);
                } else {
                    $('#phone_index .text_tishi').text('保存成功！')
                    setTimeout(function () {
                        $(".tpl_mask").hide();
                        $(".text_tishi").hide();
                    }, 1000);
                    $('#phone_preview').attr('src', '../mobile/homepage-preview');
                }
            }
            inside_hint();
        },
        change_sub: function (phonename, mpagetype) {
            // 标签切换
            // 标签选中状态
            $('.phone_index-banner .page_navs li a').each(function () {
                var page = $(this).data('sub').split("-");
                if (phonename == page[1]) {
                    $(this).addClass('border_red').siblings().removeClass('border_red');
                }
            });
        }
    };
    var phoneindexinit = new $scope.phoneIndexInit();
    // 手机栏目排序
    $scope.phoneIndexPageList = function (ele) {
        this.jsonData = ele;
        this.init();
    };
    $scope.phoneIndexPageList.prototype = {
        init: function () {
            this.pagelist_info();
        },
        pagelist_info: function () {
            var _divT = '', _divF = '',
                    data = this.jsonData,
                    _this = this;
            $.each(data, function (k, v) {
                if (v.showtypetotal != 0) {// 可选
                    _this.column_icon(v.type);
                    _divT += '<tr class="firsttab" data-aid="' + v.id + '">\n\
		            	<td>\n\
	            			<i class="fa iconfont icon-phonehome btn ' + (v.index_show == 1 ? 'blue' : 'grey') + '"></i>\n\
	            			<div class="style_choose"><select>\n\
								<option ' + (v.showtype == 1 ? 'selected' : '') + ' ' + (v.showtypetotal >= 1 ? '' : 'class="none"') + ' value="1">' + (v.showtypetotal >= 1 ? '样式一' : '') + '</option>\n\
								<option ' + (v.showtype == 2 ? 'selected' : '') + ' ' + (v.showtypetotal >= 2 ? '' : 'class="none"') + ' value="2">' + (v.showtypetotal >= 2 ? '样式二' : '') + '</option>\n\
								<option ' + (v.showtype == 3 ? 'selected' : '') + ' ' + (v.showtypetotal >= 3 ? '' : 'class="none"') + ' value="3">' + (v.showtypetotal >= 3 ? '样式三' : '') + '</option>\n\
								<option ' + (v.showtype == 4 ? 'selected' : '') + ' ' + (v.showtypetotal >= 4 ? '' : 'class="none"') + ' value="4">' + (v.showtypetotal >= 4 ? '样式四' : '') + '</option>\n\
	    					</select></div>\n\
		            	</td>\n\
		            	<td><div class="tit_info">' + v.name + '</div>' + layout + '</td>\n\
		            	<td><i herf="javascript:void(0);" class="fa iconfont icon-xingxing star' + (v.star_only == 1 ? ' checked' : '') + '"></i>限<input type="text" value="' + v.show_num + '" class="show_num" />条</td>\n\
		            	<td><i class="fa iconfont icon-yidong"></i></td>\n\
		            </tr>';
                } else {// 不可选
                    _this.column_icon(v.type);
                    _divF += '<tr class="firsttab" data-aid="' + v.id + '">\n\
		            	<td>\n\
	            			<i class="fa iconfont icon-phonehome ' + (v.index_show == 1 ? 'blue' : 'grey') + ' not-allowed"></i>\n\
	            			<div class="style_choose">\n\
                                                <select disabled class="not-allowed">\n\
								<option >无样式</option>\n\
	    					</select>\n\
                                        </div>\n\
		            	</td>\n\
		            	<td><div class="tit_info">' + v.name + '</div>' + layout + '</td>\n\
		            	<td></td>\n\
		            	<td></td>\n\
		            </tr>';
                }
            });
            $('#phone_index-index .phone_table .sapces').after(_divT);
            $('#phone_index-index .a-table .disArea').after(_divF);
            this.IsShow();
            this.IsStar();
            this.ShowNum();
            this.ChangeStyle();
        },
        IsShow: function () {
            // 是否展示
            $('#phone_index-index .icon-phonehome').click(function () {
                var btn = $(this),
                        vid = $(this).parents('tr').attr('data-aid'),
                        isshow = ($(this).hasClass('blue') ? 0 : 1);
                if (!btn.hasClass('not-allowed')) {
                    $http.post('../mhomepage-batchmodify', {id: vid, show: isshow}).success(function (json) {
                        checkJSON(json, function (json) {
                            if (btn.hasClass('blue')) {
                                btn.removeClass('blue').addClass('grey');
                            } else {
                                btn.removeClass('grey').addClass('blue');
                            }
                            phoneindexinit.Save_hint();
                        });
                    });
                }
            });
        },
        IsStar: function () {
            // 星标文章
            $('#phone_index-index .firsttab .star').click(function () {
                var id = $(this).parents('.firsttab').data('aid');
                if (!$(this).hasClass("checked")) {
                    $(this).addClass("checked");
                    $http.post('../mhomepage-batchmodify', {id: id, star_only: 1}).success(function (json) {
                        checkJSON(json, function (json) {
                            var hint_box = new Hint_box();
                            hint_box;
                        });
                    });
                } else {
                    $(this).removeClass("checked");
                    $http.post('../mhomepage-batchmodify', {id: id, star_only: 0}).success(function (json) {
                        checkJSON(json, function (json) {
                            var hint_box = new Hint_box();
                            hint_box;
                        });
                    });
                }
            });
        },
        ShowNum: function () {
            // 显示数量
            $('#phone_index-index tr td .show_num').change(function () {
                var total = $(this).val(),
                        id = $(this).parent().parent().data('aid');
                $http.post('../mhomepage-batchmodify', {id: id, total: total}).success(function (json) {
                    checkJSON(json, function (json) {
                        phoneindexinit.Save_hint();
                    });
                });
            });
        },
        ChangeStyle: function () {
            // 更改样式
            $('.firsttab .style_choose select').change(function () {
                var style = $(this).val(),
                        id = $(this).parents('tr').data('aid');
                $http.post('../mhomepage-batchmodify', {id: id, showtype: style}).success(function (json) {
                    checkJSON(json, function (json) {
                        phoneindexinit.Save_hint();
                    });
                });
            });
            $('#phone_index-index tr .icon-yidong').TreeList({
                rootNode: 'firsttab',
                parentNode: 'phone_index-index',
                'oncallback': function (indexlist) {
                    $http.post('../mhomepage-sortmodify', {indexlist: indexlist}).success(function (json) {
                        checkJSON(json, function (json) {
                            phoneindexinit.Save_hint();
                        });
                    });
                }
            });
        },
        column_icon: function (type) {
            // 获取对应栏目图标
            switch (parseInt(type)) {
                case 1:
                    layout = '<i class="fa iconfont icon-liebiao"></i>';
                    break;
                case 3:
                    layout = '<i class="fa iconfont icon-list"></i>';
                    break;
                case 2:
                    layout = '<i class="fa iconfont icon-graph"></i>';
                    break;
                case 4:
                    layout = '<i class="fa iconfont icon-wenjian"></i>';
                    break;
                case 6:
                    layout = '<i class="fa iconfont icon-lianjie"></i>';
                    break;
                case 7:
                    layout = '<i class="fa iconfont icon-weixin"></i>';
                    break;
                case 8:
                    layout = '<i class="fa iconfont icon-dingwei"></i>';
                    break;
            }
        }
    };
    // 幻灯片
    $scope.phoneIndexSlidepics = function (ele) {
        this.jsonData = ele;
        this.count = 0;
        this.init();
    };
    $scope.phoneIndexSlidepics.prototype = {
        init: function () {
            this.ModelSlidepicsInfo = function (parameter) {
                var _div = '<' + (parameter.Tag || 'li') + ' class="phone_index-field' + (parameter.IsNew ? ' new' : '') + '">\n\
						<div class="materlist-first">\n\
			            	<dt class="title">' + (parameter.title || '') + '</dt>\n\
			                <dd class="msgimg">\n\
			                <img src="' + parameter.image + '" ' + (parameter.is_upload ? 'class="img_upload" data-name="' + parameter.subimage + '"' : '') + 'alt="" width="100%">\n\
			                </dd>\n\
			                <div class="concrol">\n\
			                	<dl><dd class="zz"></dd><dd class="concrol-edite"><i class="iconfont icon-bianji"></i></dd></dl>\n\
			                	<dl class="i1"><dd class="zz"></dd><dd class="concrol-del" name="' + (parameter.id || '') + '"><i class="iconfont icon-delete"></i></dd></dl> \n\
			                </div>\n\
			            </div>\n\
			            <div class="del-box">\n\
		                	<span class="del-sanjiao"></span>\n\
		                    <dd class="del-btn">删除</dd>\n\
			            </div>\n\
			            <div class="materlist-secondbox" style="dispaly:block;">\n\
			            <span class="sanjiao"></span>\n\
			            <div class="materlist-second">\n\
			            	<dt class="title">编辑名称</dt>\n\
			                <dd class="input"><input name="' + parameter.key + (parameter.num == undefined ? '[0]' : '[' + parameter.num + ']') + '[PC_name]" type="text" value="' + (parameter.title || '') + '" /></dd>\n\
			                <dt class="title">编辑链接</dt>\n\
			                <dd class="input"><input name="' + parameter.key + (parameter.num == undefined ? '[0]' : '[' + parameter.num + ']') + '[PC_link]" type="text" value="' + (parameter.link || '') + '" /></dd>\n\
			                <dt class="title">图片排序</dt>\n\
			                <dd class="input"><input name="' + parameter.key + (parameter.num == undefined ? '[0]' : '[' + parameter.num + ']') + '[PC_sort]" type="text" value="' + (parameter.sort || 100) + '" /></dd>\n\
			                <dl class="btnbox">\n\
                                            <dd class="surebtn">确定</dd>\n\
			                    <dd class="cancebtn">取消</dd>\n\
			                </dl>\n\
			            </div>\n\
			            </div>\n\
			            <div class="detailbox">\n\
			            	<span class="sanjiao"></span>\n\
			            	<div class="detailbox-main">\n\
			                	<dt class="title">确定删除此素材？</dt>\n\
			                    <dl class="btnbox">\n\
				                	<dd class="surebtn">确定</dd>\n\
				                    <dd class="cancebtn">取消</dd>\n\
			                	</dl>\n\
			                </div>\n\
			            </div><input type="hidden" name="' + parameter.key + (parameter.num == undefined ? '[0]' : '[' + parameter.num + ']') + '[phone_info_pic]" value="' + (parameter.subimage || '') + '" />\n\
					</' + (parameter.Tag || 'li') + '>';
                return _div;
            };
            this.slidepics_info();
            this.EditBtn();
        },
        slidepics_info: function () {
            // 幻灯片列表
            var htmlColumn = '', num = 1, _this = this;
            $.each(this.jsonData, function (index, ele) {
                var aspectRatio = ele.config.width / ele.config.height || '';
                switch (ele.type) {
                    case "images":
                        var data = ele.value,
                                oddColumnWidth = 315, // 单列宽
                                mainWidth = $('#phone_index').width(), // 区域总宽
                                ColumnNum = Math.floor(mainWidth / oddColumnWidth), // 列数
                                lastColumnNum = (data.length % ColumnNum);				// 记录最终添加完的是第几列
                        // 添加瀑布流列
                        $('#phone_index_image').before('<form id="phone_index_images_' + num + '" data-key="' + index + '"></form>');
                        $('#phone_index_images_' + num).append('<div class="pictitle">(多图文&nbsp;' + ele.config.width + '*' + ele.config.height + ')<span class="red">' + ele.description + '</span></div>').data({'lastColumnNum': lastColumnNum, 'ColumnNum': ColumnNum, 'aspectRatio': aspectRatio});
                        for (var i = 1; i <= ColumnNum; i++) {
                            $('#phone_index_images_' + num).append('<ul id="phone_index_col_' + num + '_' + i + '" class="phone_index_col" style="width:' + oddColumnWidth + 'px"></ul>');
                        }
                        $.each(data, function (k, v) {
                            _this.count = k;
                            // 除余取列
                            var C_num = (k + 1) % ColumnNum == 0 ? (k % ColumnNum) + 1 : (k + 1) % ColumnNum;
                            var _div = _this.ModelSlidepicsInfo({
                                key: index,
                                title: v.title,
                                image: v.image,
                                subimage: v.image,
                                id: v.id,
                                link: v.link,
                                sort: v.sort,
                                num: k
                            });
                            $('#phone_index_col_' + num + '_' + C_num + '').append(_div);
                        });
                        // 添加按钮
                        var addButton = '<div class="phone_index-add">\
                                            <div class="up_pic up_phone"></div>\
                                    </div>';
                        $('#phone_index_col_' + num + '_' + (lastColumnNum + 1) + '').append(addButton);
                        num++;
                        break;
                    case "image":
                        var data = (ele.value instanceof(Array) && ele.value.length != 0 ? ele.value[0] : ele.value), aspectRatio = ele.config.width / ele.config.height || '',
                                _div = data.length == 0 || data == null ? '' : _this.ModelSlidepicsInfo({
                                    key: index,
                                    title: data.title,
                                    image: data.image,
                                    subimage: data.image,
                                    id: data.id,
                                    link: data.link,
                                    Tag: 'div'
                                }),
                                addButton = '<div class="phone_index-add">\
                                                    <div class="up_pic up_phone"></div>\
                                            </div>';
                        $('#phone_index_image').attr('data-key', index);
                        $('#phone_index_image').append('<div class="pictitle">(单图)<span class="red">' + ele.description + '</span></div>');
                        $('#phone_index_image').append(_div + addButton).data('aspectRatio', aspectRatio);
                        break;
                    default:
                        console.log(ele.type);
                        break;
                }
            });
            _this.slidepics_upload();
            this.IsDelete();
        },
        layoutChange: function (itemIdNum) {
            // 改变列表布局
            // 创建数组记录总个数
            var arrTemp = [], arrHtml = [], maxLength = 0, changeNum = 0, newColumnNum;
            // 新栏个数		
            newColumnNum = Math.floor($('#phone_index').width() / 315);
            for (var start = 1; start <= newColumnNum; start++) {
                var arrColumn = $("#phone_index_col_" + itemIdNum + '_' + start).html().match(/<li(?:.|\n|\r|\s)*?li>/gi);
                if (arrColumn) {
                    maxLength = Math.max(maxLength, arrColumn.length);
                    arrTemp.push(arrColumn);
                    changeNum++;
                }
            }
            ;
            // 重新排序
            var rowStart, colStart;
            for (rowStart = 0; rowStart < maxLength; rowStart++) {
                for (colStart = 0; colStart < changeNum; colStart++) {
                    if (arrTemp[colStart][rowStart]) {
                        arrHtml.push(arrTemp[colStart][rowStart]);
                    }
                }
            }
            var lastColumnNum = arrHtml.length % newColumnNum;
            // 添加按钮
            arrHtml.push('<div class="phone_index-add"><div class="up_pic up_phone"></div></div>');
            if (arrHtml.length !== 0) {
                // 计算每列的行数
                var line = Math.ceil(arrHtml.length / newColumnNum);
                // 重组HTML
                var newStart = 0, htmlColumn = '<div class="pictitle">多图文' + itemIdNum + '</div>';
                for (newStart; newStart < newColumnNum; newStart++) {
                    htmlColumn = htmlColumn + '<ul id="phone_index_col_' + itemIdNum + '_' + (newStart + 1) + '" class="phone_index_col" style="width:315px">' +
                            function () {
                                var html = '';
                                for (var i = 0; i < line; i++) {
                                    html += arrHtml[newStart + newColumnNum * i] || '';
                                }
                                return html;
                            }() + '</ul> ';
                }
                $('#phone_index_images_' + itemIdNum).html(htmlColumn);
                this.slidepics_upload();
                this.IsDelete();
            }
        },
        slidepics_upload: function () {
            var _this = this, _newpic, i = 1;
            // 添加图片弹框
            $('.up_pic').on('click', function (event) {
                var warningbox = new WarningBox(), eventitemnum = $(this).closest('form').attr('id').split('_')[3],
                        lastColumnNum = $(this).closest('form').find('li').length,
                        aspectRatio = $(this).closest('form').data('aspectRatio'),
                        ColumnNum = $(this).closest('form').data('ColumnNum'),
                        $eventthis = $(this),
                        key = $eventthis.closest('form').data('key');
                warningbox._upImage({
                    aspectRatio: aspectRatio,
                    ajaxurl: '../fileupload?target=page_index',
                    oncallback: function (json) {
                        if ($eventthis.closest('#phone_index_image').length) {
                            $('#phone_index_image div.phone_index-field').remove();
                            _newpic = _this.ModelSlidepicsInfo({
                                key: key,
                                image: json.data.url,
                                subimage: json.data.name,
                                Tag: 'div',
                                IsNew: true,
                                is_upload: 'img_upload',
                            });
                            $eventthis.parent().before(_newpic);
                        } else {
                            _newpic = _this.ModelSlidepicsInfo({
                                key: key,
                                image: json.data.url,
                                subimage: json.data.name,
                                num: ++(_this.count),
                                IsNew: true,
                                is_upload: 'img_upload',
                            });
                            var addBtn = (lastColumnNum + 1) % ColumnNum,
                                    addpic = (lastColumnNum + 2) % ColumnNum;
                            $('#phone_index_col_' + eventitemnum + '_' + (addBtn == 0 ? ColumnNum : addBtn) + '').append(_newpic);
                            $('#phone_index_col_' + eventitemnum + '_' + (addpic == 0 ? ColumnNum : addpic) + '').append($eventthis.parent());
                            lastColumnNum++;
                        }
                        _this.IsDelete();
                    }
                });
            });
        },
        IsDelete: function () {
            var _this = this;
            // 确定、取消按钮效果
            $(".concrol-del").hover(function () {
                $(this).parents(".phone_index-field").find(".del-box").slideDown();
            }, function () {
                $(this).parents(".phone_index-field").find(".del-box").slideUp();
            }).click(function () {
                $(this).parents(".phone_index-field").find(".detailbox").slideDown();
                $(this).parents(".concrol").find(".zz").show();
            })
            $('.concrol-edite').click(function () {
                $(this).parents(".phone_index-field").find(".materlist-secondbox").slideDown();
                $(this).parents(".concrol").find(".zz").show();
            })
            $('.cancebtn').click(function () {
                $(this).parents(".phone_index-field").find(".materlist-secondbox,.detailbox").slideUp();
                $(this).parents(".phone_index-field").find(".zz").hide();
            })
            $('.detailbox .surebtn').click(function () {//===删除确认===
                var key = $(this).closest('form').data('key'),
                        str = '{"' + key + '":""}';
                eval('var json = ' + str);
                if ($(this).closest('#phone_index_image').length) {
                    $(this).parents('.phone_index-field').fadeOut('400', function () {
                        $(this).remove();
                        //幻灯片删除
                        if (!$(this).hasClass('new')) {
                            $http.post('../mhomepage-modify', json).success(function () {
                                phoneindexinit.Save_hint();
                            });
                        }
                    });
                } else {
                    var itemIdNum = $(this).closest('.phone_index_col').attr('id').split('_')[3];
                    $(this).parents('.phone_index-field').fadeOut('400', function () {
                        $(this).remove();
                        _this.layoutChange(itemIdNum);
                        //幻灯片删除
                        if (!$(this).hasClass('new')) {
                            var data = $('#phone_index_images_' + itemIdNum).serializeJson();
                            var data1 = ($('#phone_index_images_' + itemIdNum).serializeArray().length > 0 ? data : json);
                            $http.post('../mhomepage-modify', data1).success(function () {
                                phoneindexinit.Save_hint();
                            });
                        }
                    });
                }
            })
            $('.materlist-secondbox .surebtn').click(function () {//===保存确认===
                var _this = $(this).closest('.phone_index-field');
                $(this).parents(".phone_index-field").find(".materlist-secondbox,.detailbox").slideUp();
                $(this).parents(".phone_index-field").find(".zz").hide();
                var img_upload = [];
                $('.msgimg>.img_upload').each(function () {
                    img_upload.push($(this).data('name'));
                });
                if ($(this).parents('div').hasClass('materlist-secondbox')) {
                    //幻灯片保存
                    var data = $(this).closest('form').serializeJson();
                    $http.post('../mhomepage-modify', data).success(function () {
                        if (img_upload.length) {
                            $http.post('../imgupload?target=page_index', {files: img_upload});
                        }
                        phoneindexinit.Save_hint();
                        _this.removeClass('new');
                    });
                }
            });
        },
        EditBtn: function () {
            // 编辑功能
            $('.materlist-second .input:nth-of-type(1) input').on('keyup', function (event) {
                $(this).parents('.materlist-secondbox').siblings('.materlist-first').children('.title').text($(this).val());
            });
        }
    };
    // Other数据
    $scope.phoneIndexOther = function (ele) {
        this.jsonData = ele;
        this.init();
    };
    $scope.phoneIndexOther.prototype = {
        init: function () {
            this.GetOtherData();
            this.SaveOtherDataUrl = '';
        },
        GetOtherData: function () {
            $.each(this.jsonData, function (k, v) {
                var _div = '';
                switch (v.type) {
                    case 'text':
                        _div += '<li>\
									<dl class="leftblock">' + v.description + ':</dl>\
									<dl class="rightblock">\
										<input type="text" name="' + k + '" value="' + v.value + '"></input>\
									</dl>\
								</li>';
                        $('#phone_index_text ul').append(_div);
                        break;
                    case 'textarea':
                        _div += '<li>\
									<dl class="leftblock">' + v.description + ':</dl>\
									<dl class="rightblock">\
										<textarea name="' + k + '" cols="52" rows="4">' + v.value + '</textarea>\
									</dl>\
								</li>';
                        $('#phone_index_textarea ul').append(_div);
                        break;
                    case 'navs':
                        _div += '<li>\
									<dl class="leftblock">' + v.description + ':</dl>\
									<dl class="rightblock">\
										<div id="move_navs">\
										' + function () {
                            var html = '', _rel = '', list1 = '', pname = '', sign = [];
                            $.each(v.config.ids, function (idx, ele) {
                                $.each(v.config.list, function (i, vlist) {
                                    var _this = $(this);
                                    if (vlist.p_id == 0) {
                                        if (vlist.id == ele) {
                                            sign = '<input class="selectBox_val" type="hidden" value="' + vlist.id + '" name="data[' + k + '][ids][' + idx + ']" />';
                                            pname = vlist.name
                                        }
                                        list1 += '<li><a class="' + (vlist.childmenu == null ? 'lastchild ' : '') + 'parents' + ((v.config.filter.toLowerCase() == 'all' || v.config.filter == 'list,page') ? '' : v.config.filter == 'page' ? '' : vlist.type == 4 ? ' not-allowed' : '') + '" data-id="' + vlist.id + '">' + vlist.name + '</a></li>';
                                        var NextChild = vlist;
                                        var num = 2;
                                        var LoopChlid = function (NextChild, num) {
                                            if (NextChild.childmenu != null) {
                                                $.each(NextChild.childmenu, function (kk, vv) {
                                                    if (vv.id == ele) {
                                                        sign = '<input class="selectBox_val" type="hidden" value="' + vv.id + '" name="data[' + rootNodeName + '][ids][' + idx + ']" />';
                                                        pname = vv.name
                                                    }
                                                    list1 += '<li><a class="' + (vv.childmenu == null ? 'lastchild ' : '') + 'LevelChild' + num + ((vv.type == 4) && (v.config.filter == 'list') ? ' not-allowed' : '') + '" data-pid="' + vv.p_id + '" data-id="' + vv.id + '">├ ' + vv.name + '</a></li>';
                                                    NextChild = vv;
                                                    num++;
                                                    LoopChlid(NextChild, num);
                                                    num--;
                                                });
                                            }
                                        }
                                        v.config.filter == 'page' ? '' : LoopChlid(NextChild, num);
                                    }
                                });
                                _rel += '<div class="dropdown" style="margin-bottom:10px;">\
					                            <div class="selectBox" type="text">' + pname + '</div><span class="arrow"></span>' + sign + '\
					                            <ul>' + list1 + '</ul><span class="move_icon"><i class="iconfont icon-liebiao"></i><i class="iconfont icon-guanbi"></i></span></div>' + (idx == (v.config.ids.length - 1) ? '<div class="add_icon"><i class="iconfont icon-add" data-limit="' + v.config.limit + '"></i></div>' : '') + '';
                            });
                            return _rel;
                        }() + '</div>\
									</dl>\
								</li>';
                        $('#phone_index_navs ul').append(_div);
                        break;
                }
            });
            //下拉框更改
            DropdownEvent();
            // 提示移动框
            $('.not-allowed').MoveBox({context: '此为单页类型或者父级分类下带有子级，不支持选择！'});
            $scope.phoneIndexQuickbar.prototype.InputStyle();// 拖拽效果
            $('.dropdown .icon-liebiao').TreeList({
                parentNode: 'move_navs',
                rootNode: 'dropdown'
            });
            this.DeleteDropList();
            this.AddDropList();
            this.SaveOtherData();
        },
        DeleteDropList: function () {
            $('#move_navs .icon-guanbi').on('click', function () {
                $(this).closest('.dropdown').remove();
            });
        },
        AddDropList: function () {
            // 添加拖拽栏目
            $('.add_icon i').on('click', function (event) {
                if ($(this).parent().siblings('.dropdown').length >= $(this).data('limit')) {
                    alert('超出数量！')
                } else {
                    var lastNum = parseInt($('#move_navs .dropdown').last().find('.selectBox_val').attr('name').match(/\[(\d*)\]/)[1]) + 1,
                            clone_cell = $('#move_navs .dropdown').last().clone(true);
                    $('#move_navs .add_icon').before(clone_cell);
                    clone_cell.find('.selectBox').text('空').end().find('.selectBox_val').val('');
                    var word = clone_cell.find('.selectBox_val').attr('name').replace(/data\[(.*)\]\[(.*)\]\[(\d*)\]/, 'data[$1][$2][' + lastNum + ']');
                    clone_cell.find('.selectBox_val').attr('name', word);
                }
            });
        },
        SaveOtherData: function () {
            $('.save').click(function () {
                var data = $(this).closest('form').serializeJson();
                $http.post(this.SaveOtherDataUrl, data).success(function () {
                    checkJSON(json, function (json) {
                        phoneindexinit.Save_hint();
                    });
                });
            })
        }
    }
    // 底部导航
    $scope.phoneIndexQuickbar = function (ele) {
        this.jsonData = ele;
        this.QuickBarShowTypeUrl = 'json/bottomnavsType.json';
        this.init();
    };
    $scope.phoneIndexQuickbar.prototype = {
        init: function () {
            this.QuickBarInfo();
            this.QuickBarShowType();
        },
        QuickBarInfo: function () {
            var data = (this.jsonData == undefined ? null : this.jsonData.value), _this = this,
                    _div1 = '', num, info, colors = {};
            $.each(this.jsonData, function (k, v) {
                switch (v.type) {
                    case 'tel':
                    case 'sms':
                        info = '<div class="quicklist-r inline-block"><span class="contact ml5"><input type="text" value="' + v.data + '" class="message-box" /></span></div>';
                        break;
                    case 'im':
                        info = '<div class="quicklist-r inline-block">\
									<div class="ml5">\
									<ul class="fl consultation">'
                                + function () {
                                    var newArr = v.data.split('|'), _li = '';
                                    for (var x in newArr) {
                                        var fg = newArr[x].split('@'),
                                                kfname = fg[0].split(':')[0], kfnum = fg[0].split(':')[1];
                                        _li += '<li class="consultation-item">\
														<select><option value="qq"' + (fg[1] == 'qq' ? 'selected' : '') + '>QQ</option></select>\
														<span><input class="consultation-name message-box" value="' + (kfname || '') + '" />-<input class="consultation-num message-box" value="' + (kfnum || '') + '" /></span>\
														<div class="crl_icon fr"><i class="iconfont icon-guanbi"></i></div>\
													</li>';
                                    }
                                    return _li;
                                }() + '</ul><div class="crl_icon"><i class="iconfont icon-add"></i></div>\
								</div></div>';
                        break;
                    case 'share':
                        info = '<div class="quicklist-r inline-block">\
								<span class="shareicon ml5">\
									<i class="iconfonts ' + (v.data.indexOf('tsina') == -1 ? 'grey' : 'blue') + '" data-name="tsina">&#xe653;</i>\
									<i class="iconfonts ' + (v.data.indexOf('ibaidu') == -1 ? 'grey' : 'blue') + '"  data-name="ibaidu">&#xe651;</i>\
									<i class="iconfonts ' + (v.data.indexOf('qzone') == -1 ? 'grey' : 'blue') + '"  data-name="qzone">&#xe652;</i>\
									<i class="iconfonts ' + (v.data.indexOf('tqq') == -1 ? 'grey' : 'blue') + '"  data-name="tqq">&#xe650;</i>\
								</span></div>';
                        break;
                    case 'map':
                        var point = v.data.split('|')[1] || '';
                        _this.pointX = point.split(',')[0] || '';
                        _this.pointY = point.split(',')[1] || '';
                        info = '<div class="quicklist-r inline-block">\
									<div class="linktop"><input class="message-box" value="' + v.data.split('|')[0] + '" data-point="' + _this.pointX + ',' + _this.pointY + '" /><a class="search">搜索</a></div>\
									<div id="bdmap"></div>\
								</div>';
                        break;
                    case 'link':
                        info = '<div class="quicklist-r inline-block">\
									<ul class="fl consultation">\
									' + function () {
                            var newArr = v.data.split('|'), _li = '';
                            for (var x in newArr) {
                                _li += '<li class="consultation-item">\
                                        <span><input class="consultation-name message-box" value="' + newArr[x] + '" /></span>\
                                        <div class="crl_icon fr"><i class="iconfont icon-guanbi"></i></div>\
                                </li>';
                            }
                            return _li;
                        }() + '</ul><div class="crl_icon"></i></div>\
								</div>';
                        break;
                    case 'search':
                        info = '<div class="quicklist-r inline-block"></div>';
                        break;
                    case 'follow':
                        var barcode = '';
                        if (v.for == "vx_barcode" || v.for == "qq_barcode") {
                            if (v.serurl != '') {
                                barcode = '<div class="template-download fade fl in">\n\
                                                                            <div>\n\
                                                                                <span class="preview">\n\
                                                                                <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                                                                    <img src="' + v.serurl + '" style="width:80px;height:80px;padding:5px;" data-role="' + v.for + '" data-preimg="preimg">\n\
                                                                                </span>\n\
                                                                            </div><input type="hidden" name="' + v.for + '" value="' + v.data + '"></div>';
                            }

                            info = '<div class="quicklist-r inline-block">\
                                                                  <div class="feild-item">\
                                                                  <div class="set_pic3 set_pic inline-block" name="' + v.for + '" data-role="' + v.for + '">';
                            info += barcode;
                            info += '</div>\
                                                                  <button class="up_load" data-role="' + v.for + '"><span class="set_up_name" data-role="' + v.for + '">二维码上传</span></button>\
                                                                  </div>\
                                                              </div>';
                        } else if (v.for == "m_barcode") {
                            barcode = '<div class="template-download fade fl in">\n\
                                                                            <div>\n\
                                                                                <span class="preview">\n\
                                                                                    <img src="' + v.data + '" style="width:80px;height:80px;padding:5px;" data-role="' + v.for + '" data-preimg="preimg">\n\
                                                                                </span>\n\
                                                                            </div><input type="hidden" name="' + v.for + '" value="' + v.data + '"></div>';
                            info = '<div class="quicklist-r inline-block">\
                                                                  <div class="feild-item">\
                                                                  <div class="set_pic3 set_pic inline-block" name="' + v.for + '" data-role="' + v.for + '">';
                            if (v.data != '') {
                                info += barcode;
                            }
                            info += '</div>\
                                                                  <button class="flush" data-role="' + v.for + '"><span class="set_up_name" data-role="' + v.for + '">刷新</span></button>\
                                                                  </div>\
                                                              </div>';
                            info += '</div>\
                                                                  </div>\
                                                              </div>';
                        }
                        break;
                    case 'colors':
                        colors = v.data;
                        return true;
                }
                _div1 += '<li class="move_feild" data-role="' + v.for + '" >\n\
							<div class="quicklist-l inline-block">\
							<i class="fa iconfont icon-yidong"></i>\n\
							<span><i class="fa icon-pc iconfont btn btn-show btn-desktop ' + (v.enable_pc == 1 ? 'blue' : 'grey') + '"></i>';
                if (v.for != "m_barcode") {
                    _div1 += '<i class="fa iconfont icon-snimicshouji btn btn-show btn-mobile ' + (v.enable_mobile == 1 ? 'blue' : 'grey') + '"></i>';
                }
                _div1 += '</span>\n\
							<label class="message-name" data-type="' + v.type + '">' + v.name + '</label>\
                                            <label class="en-name none" >' + v.en_name + '</label>\
							<span class="icon_box pr">\
								<i class="iconfonts' + (v.icon ? '' : ' icon-dengpao') + '">' + (v.icon || '') + '</i>\
								<input type="hidden" name="' + v.type + '_icons" value="' + v.icon.replace('&', '&amp;') + '" class="icon_input" />\
	                            <span class="arrow"></span>\
	                            <div class="icon_ousidebox">\
	                                <div class="box_content">\
	                                    <ul></ul>\
	                                </div>\
	                            </div>\
                            </span></div>' + info + '\n\
						</li>';
            });
            $('.phone_quickbar_item .phone_func').append(_div1);
            var mobile_info = '';
            if (typeof (colors['mobile']) != "undefined") {
                mobile_info = '<li class="consultation-item">\
                                    <label class="message-name">手机主色：</label><div><input type="color" class="colors" value="' + this.ColorRet(colors['mobile'][0]) + '" name="mobile_mainColor" id="mobile_mainColor" data-id="mobile_mainColor"/><input type="text" class="mobile_mainColor" value="' + this.ColorRet(colors['mobile'][0]) + '"  data-id="mobile_mainColor" /></div>\
                                    <label class="message-name">手机副色：</label><div><input type="color" class="colors" value="' + this.ColorRet(colors['mobile'][1]) + '" name="mobile_secondColor" id="mobile_secondColor" data-id="mobile_secondColor"/><input type="text" class="mobile_secondColor" value="' + this.ColorRet(colors['mobile'][1]) + '" data-id="mobile_secondColor"/></div>\
                                    <label class="message-name">手机图标颜色：</label><div><input type="color" class="colors" value="' + this.ColorRet(colors['mobile'][2]) + '" name="mobile_textColor" id="mobile_textColor" data-id="mobile_textColor"/><input type="text" class="mobile_textColor" value="' + this.ColorRet(colors['mobile'][2]) + '" data-id="mobile_textColor"/></div>\
				</li>';
            }
            //===如果有PC站才生成html===
            var pc_info = '';
            if (typeof (colors['pc']) != "undefined"){
                pc_info = '<li class="consultation-item">\
                                    <label class="message-name">pc主色：</label><div><input type="color" class="colors" value="' + this.ColorRet(colors['pc'][0]) + '" name="pc_mainColor" id="pc_mainColor" data-id="pc_mainColor"/><input type="text" class="pc_mainColor" value="' + this.ColorRet(colors['pc'][0]) + '" data-id="pc_mainColor" /></div>\
                                    <label class="message-name">pc鼠标停留时颜色：</label><div><input type="color" class="colors" value="' + this.ColorRet(colors['pc'][1]) + '" name="pc_secondColor" id="pc_secondColor" data-id="pc_secondColor"/><input type="text" class="pc_secondColor" value="' + this.ColorRet(colors['pc'][1]) + '" data-id="pc_secondColor"/></div>\
                                    <label class="message-name">pc图标颜色：</label><div><input type="color" class="colors" value="' + this.ColorRet(colors['pc'][2]) + '" name="pc_textColor" id="pc_textColor" data-id="pc_textColor"/><input type="text" class="pc_textColor" value="' + this.ColorRet(colors['pc'][2]) + '" data-id="pc_textColor" /></div>\
                </li>';
            }
            //===end===
            info = '<div class="quicklist-r inline-block">\
                        <div class="ml5">\
                            <ul class="fl consultation colorsetting">\n\
                            ' + pc_info + mobile_info + '\n\
                            </ul>\
                            <button class="colorsclear btn btn-default" >还原</button>\
			</div>\n\
                    </div>';
            var _div2 = '<li class="move_feild">\n\
                            <div class="quicklist-l inline-block">\
                                <i class="fa iconfont icon-yidong"></i>\n\
                                <label class="message-name" data-type="colors">快捷导航颜色&nbsp&nbsp&nbsp</label>\
                            </div>' + info + '\n\
                        </li>';
            $('.phone_quickbar_item .phone_func').append(_div2);
            // 栏目图标
            var columnicon = new icon_choose(780);
            columnicon.clicks();
            this.InputStyle();
            this.ShowPos();
            this.DragBlock();
            this.SaveData();
            this.QuickBarListFuc();
            this.ColorBind();
        },
        //颜色3位转换成六位16进制
        ColorRet: function (color) {
            if (color.length < 5) {
                var colorstr = color.replace('#', '');
                color += colorstr;
            }
            if (color.length == 5) {
                color = color.substring(0, color.length - 1);
                var colorstr = color.replace('#', '');
                color += colorstr;
            }
            return color;
        },
        ColorBind: function () {
            $(".colorsetting input").change(function () {
                var value = $(this).val();
                var id = $(this).data("id");
                $(".colorsetting input[data-id=" + id + "]").val(value);
            });
        },
        QuickBarListFuc: function () {
            // 咨询  
            $('.icon-add').click(function () {
                var parenticon = $(this).closest('.crl_icon').siblings('.consultation'),
                        clone_cell = parenticon.find('.consultation-item').last().clone(true);
                parenticon.append(clone_cell);
            });
            $(".up_load").click(function () {
                var _this = $(this);
                var role = '';
                role = 32 / 32;
                var warningbox = new WarningBox();
                warningbox._upImage({
                    aspectRatio: role,
                    ajaxurl: '../fileupload?target=common',
                    oncallback: function (json) {
                        checkJSON(json, function (json) {
                            _this.closest('.feild-item').find('div[data-role=' + _this.data('role') + ']').children().remove();
                            var _newpic = '<div class="template-download fade fl in">\n\
                                                                        <div>\n\
                                                                            <span class="preview">\n\
                                                                            <div class="preview-close"><img src="images/preview-close.png" /></div>\n\
                                                                                <img src="' + json.data.url + '" class="img_upload" data-name="' + json.data.name + '" style="width:80px;height:80px;padding:5px;" data-role=' + _this.data('role') + ' data-preimg="preimg">\n\
                                                                            </span>\n\
                                                                        </div><input type="hidden" name="' + _this.data('role') + '" value="' + json.data.s_url + '" /></div>';
                            _this.closest('.feild-item').find('div[data-role=' + _this.data('role') + ']').append(_newpic);
                        });
                    }
                });
            });
            $('.feild-item').on('click', '.preview-close', function () {
                $(this).parents('.template-download').remove();
                return false;
            });
            $('.flush').on('click', function () {
                var _this = $(this);
                $http.post('../getqrcode', {barcode: 'mobile_domain'}).success(function (json) {
                    checkJSON(json, function (json) {
                        _this.closest('.feild-item').find('div[data-role=' + _this.data('role') + ']').children().remove();
                        var _newpic = '<div class="template-download fade fl in">\n\
                                                                        <div>\n\
                                                                            <span class="preview">\n\
                                                                                <img src="' + json.data + '" style="width:80px;height:80px;padding:5px;" data-role=' + _this.data('role') + ' data-preimg="preimg">\n\
                                                                            </span>\n\
                                                                        </div><input type="hidden" name="' + _this.data('role') + '" value="' + json.data + '" /></div>';
                        _this.closest('.feild-item').find('div[data-role=' + _this.data('role') + ']').append(_newpic);
                    });
                });
            });
            $('.icon-guanbi').on('click', function () {
                $('.consultation .consultation-item').length == 1 ? alert('请至少保留一个！') : $(this).closest('.consultation-item').remove();
            });
            // 百度地图
            this.BdMap();
        },
        BdMap: function () {
            // 百度地图API功能
            var pointX = this.pointX || null, pointY = this.pointY || null, marker
            map = new BMap.Map("bdmap");
            map.centerAndZoom(new BMap.Point(116.404, 39.915), 15);
            var bottom_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL});
            map.addControl(bottom_right_navigation);
            var local = new BMap.LocalSearch(map, {
                renderOptions: {map: map}
            });
            var dragMarker = function (pointX, pointY) {
                var new_point = new BMap.Point(pointX, pointY);
                map.panTo(new_point);
                // 拖拽坐标
                marker = new BMap.Marker(new_point);  // 创建标注
                map.addOverlay(marker);              // 将标注添加到地图中
                var label = new BMap.Label("拖拽坐标确定位置", {offset: new BMap.Size(20, -10)});
                marker.setLabel(label);
                map.addOverlay(marker);
                marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画 
                marker.enableDragging();    //可拖拽
                marker.addEventListener("dragend", function (e) {//将结果进行拼接并显示到对应的容器内
                    pointX = e.point.lng;
                    pointY = e.point.lat;
                    $('.quicklist-r .linktop .message-box').attr('data-point', pointX + ',' + pointY)
                });
            }
            if (pointX) {
                map.clearOverlays();
                dragMarker(pointX, pointY);
            }
            map.addEventListener("click", function (e) {
                pointX = e.point.lng;
                pointY = e.point.lat;
                $('.quicklist-r .linktop .message-box').attr('data-point', pointX + ',' + pointY);
                map.removeOverlay(marker);
                dragMarker(pointX, pointY);
            });
            var keyword;
            $('.linktop .search').click(function () {
                keyword = $(this).siblings('.message-box').val();
                local.search(keyword);
            });
        },
        InputStyle: function () {
            $('input').focus(function () {
                $(this).addClass('input_border').css('border', 'solid 1px #639cfb');
            }).blur(function () {
                $(this).removeClass('input_border').css('border', 'solid 1px #999');
            });
        },
        ShowPos: function () {
            $('.phone_func span').not('.icon_box').find('i').click(function () {
                $(this).hasClass('blue') ? $(this).removeClass('blue').addClass('grey') : $(this).removeClass('grey').addClass('blue');
            });
        },
        DragBlock: function () {
            $('#phone_index-quickbar li .icon-yidong').TreeList({
                parentNode: 'phone_func',
                rootNode: 'move_feild',
                oncallback: function (indexlist) {}
            });
        },
        QuickBarShowType: function () {
            var _this = this;
            $('.phone_quickbar_style .all_button').click(function (event) {
                var html = '';
                var ModelGetQuickBar = function (platform) {
                    $http.post(_this.QuickBarShowTypeUrl, {platform: platform}).success(function (json) {
                        checkJSON(json, function (json) {
                            var _div = '', name;
                            $.each(json.data, function (k, v) {
                                _div += '<li' + (v.selected ? ' class="cu"' : '') + '><div class="nsvshowtype-item-border"><img src="' + v.url + '" alt="" /><span' + (v.selected ? ' class="red"' : '') + '>' + v.name + '</span></div></li>';
                            });
                            html = '<div class="nsvshowtype">\
										<div class="nsvshowtype-title"><span>' + (platform ? 'PC导航风格' : '手机导航风格') + '</span></div>\
										<div class="nsvshowtype-content">\
											<ul class="nsvshowtype-info-box">' + _div + '</ul>\
										</div>\
				    				</div>';
                            var warningbox = new WarningBox('', {warning_context: html});
                            warningbox.ng_fuc();
                            $('.nsvshowtype-info-box li').click(function () {
                                $(this).addClass('cu').siblings().removeClass('cu');
                                name = $(this).find('span').text();
                            });
                            $('.button .save').click(function () {
                                $http.post('', {platform: platform, name: name}).success(function (json) {
                                    checkJSON(json);
                                });
                            });
                        });
                    });
                }
                if ($(this).closest('.pc_check_btn').length) {
                    ModelGetQuickBar(1);
                } else if ($(this).closest('.mob_check_btn').length) {
                    ModelGetQuickBar(0);
                }
            });
        },
        SaveData: function () {
            $('.phone_index-banner .rewrite').click(function () {
                $http.post('../quickbar.rewrite').success(function (json) {
                    checkJSON(json, function (json) {
                        location.reload();
                    });
                });
            });
            $('.colorsclear').click(function () {
                $http.post('../quickbar-colorsclear').success(function (json) {
                    checkJSON(json, function (json) {
                        location.reload();
                    });
                });
            });
            $('.phone_index-banner .save').click(function () {
                var navsArray = new Array();
                $('.phone_quickbar_item .phone_func .move_feild').each(function () {
                    var show = [], data = [], icons, for_bar,
                            type = $(this).find('.quicklist-l>.message-name').data('type');
                    icons = $(this).find('.icon_input').val();
                    switch (type) {
                        case 'tel':
                        case 'sms':
                            data = $(this).find('.quicklist-r .message-box').val();
                            break;
                        case 'im':
                            var info = '', name, num, fs,
                                    count = $(this).find('.consultation li').length;
                            $(this).find('.consultation li').each(function (i, j) {
                                name = $(this).find('.consultation-name').val();
                                num = $(this).find('.consultation-num').val();
                                fs = $(this).find('select').val();
                                info += (name + ':' + num + '@' + fs + (count == 0 ? '' : i == count - 1 ? '' : '|'));
                            });
                            data = info;
                            data = data ? data.toString() : '';
                            break;
                        case 'map':
                            data = $(this).find('.quicklist-r .linktop .message-box').val() + '|' + $(this).find('.quicklist-r .linktop .message-box').data('point')
                            break;
                        case 'share':
                            $(this).find('.quicklist-r .shareicon i').each(function (index, el) {
                                $(this).hasClass('blue') ? data.push($(this).data('name')) : null;
                            });
                            data = data ? data.toString() : '';
                            break;
                        case 'link':
                            var info = '', name,
                                    count = $(this).find('.consultation li').length;
                            $(this).find('.consultation li').each(function (i, j) {
                                name = $(this).find('.consultation-name').val();
                                info += (name + (count == 0 ? '' : i == count - 1 ? '' : '|'));
                            });
                            data = info;
                            data = data ? data.toString() : '';
                            break;
                        case 'follow':
                            for_bar = $(this).data('role');
                            data = $("input[name=" + for_bar + "]").val();
                            data = data ? data.toString() : '';
                            break;
                        case 'colors':
                            var colors = {};
                            if ($("#mobile_mainColor").length > 0) {
                                colors.mobile = [];
                                colors.mobile[0] = $("#mobile_mainColor").val();
                                colors.mobile[1] = $("#mobile_secondColor").val();
                                colors.mobile[2] = $("#mobile_textColor").val();
                            }
                            if ($("#pc_mainColor")) {
                                colors.pc = [];
                                colors.pc[0] = $("#pc_mainColor").val();
                                colors.pc[1] = $("#pc_secondColor").val();
                                colors.pc[2] = $("#pc_textColor").val();
                            }
                            data = colors;
                            break;
                    }
                    navsArray.push({
                        name: $(this).find('.quicklist-l>.message-name').text(),
                        en_name: $(this).find('.quicklist-l>.en-name').text(),
                        icon: icons,
                        data: data,
                        enable_pc: $(this).find('.quicklist-l span:eq(0) i').eq(0).hasClass('blue') ? 1 : 0,
                        enable_mobile: $(this).find('.quicklist-l span:eq(0) i').eq(1).hasClass('blue') ? 1 : 0,
                        for : for_bar,
                        type: type
                    });
                });
                var img_upload = [];
                $('.preview>.img_upload').each(function () {
                    img_upload.push($(this).data('name'));
                });
                $http.post('../quickbar.jsonmodify', {QuickBar: navsArray}).success(function (json) {
                    checkJSON(json, function (json) {
                        if (img_upload.length) {
                            $http.post('../imgupload?target=common',
                                    {
                                        files: img_upload,
                                        imgsize: 200
                                    });
                        }
                        phoneindexinit.Save_hint();
                    });
                });
            });
        }
    };
}