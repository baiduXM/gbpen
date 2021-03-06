
function heightauto() {
    //自动获取高度
    var col_list = $(".box_info").height() / 2;
    $(".box_info").css({
        "marginTop": "-" + col_list + "px"
    })
}
function tanchuang(ColumnInit) {
    // 弹窗处理
    $('.close').click(function () {
        $('#bomb-box').removeClass('in').fadeOut(function () {
            $(".box_info").css({
                "marginTop": "-" + 2000 + "px"
            });
            $('.box-up').text('添加栏目');
            clear_info();
        });
    });
    $('.mask,.box_info .boxs .cancel,.save_column,.box_info .boxs .save').click(function () {
        $('#bomb-box').removeClass('in').fadeOut(function () {
            $(".box_info").css({
                "marginTop": "-" + 2000 + "px"
            });
            $('.box-up').text('+添加栏目+');
            clear_info();
            $('.single').show();
            $('.batch').hide();
        });
    });
    //===添加栏目显示编辑框===
    $('.addcolumn,.addlist,.addauto').click(function () {
        $(this).hasClass('addcolumn') ? ColumnInit.Column_Upload('') : null;
        $('#bomb-box').fadeIn(function () {
            heightauto()
        });
        $('#bomb-box').addClass('in');
        $('#box_flag').val('single');
        $('.box-up').text('添加栏目');
        $('.single').show();
        $('.batch').hide();
        clear_info();
        ColumnInit._Save_id(1);
    });
    $('.batchaddcolumn').click(function () {
        $(this).hasClass('addcolumn') ? ColumnInit.Column_Upload('') : null;
        $('#bomb-box').fadeIn(function () {
            heightauto()
        });
        $('#bomb-box').addClass('in');
        $('#box_flag').val('batch');
        $('.box-up').text('批量添加栏目');
        $('.batch').show();
        $('.single').hide();
        clear_info();
        ColumnInit._Save_id(1);
    });
    //===添加栏目显示编辑框===
    $('.batchaddcolumn').click(function () {
        $('.batch').fadeIn(function () {
            heightauto()
        });
        $('.batch').addClass('in');
        clear_info();
//        ColumnInit._Save_id(1);
    });
    //数据清除
    function clear_info() {
        $('[name="batch_column_name"]').val('');
        $('.selectBox').text('');
        $('.column_name').val('');
        $('.en_name').val('');
        $('.dropdown ul').hide();
        $('.dropdown li').show();
        $('#lottery').val('');
        $('.keyword').val('');
        $('.template-download').remove();
        $('.txts').val('');
        $('#out_url input').val('');
        $('.sites input[value=pc]').attr('checked', '');
        $('.sites label.lb_pc').removeClass('chirdchecked');
        $('.sites input[value=mobile]').attr('checked', '');
        $('.sites label.lb_mobile').removeClass('chirdchecked');
        $('.sites input[value=footer]').attr('checked', '');
        $('.sites label.lb_footer').removeClass('chirdchecked');
        $('.sites input[value=wechat]').attr('checked', '');
        $('.sites label.lb_wechat').removeClass('chirdchecked');
        $('.sites label.lb_wechat').removeClass('chirdchecked');
        $('#lottery1,#lottery2,#lottery_mg').hide();
        $('#out_url').hide();
        $('#page_editor').hide();
        $('#inside_model').addClass('none');
        $('#models').hide();
        $('.icon_ousidebox,.icon_box .arrow').fadeOut();
        $('.icon_ousidebox').siblings('i').removeClass('in');
        $('.box-down .mod_border').each(function () {
            $(this).hasClass('cu') ? $(this).removeClass('cu') : '';
        });
    }
}
// 二维码
var ercodeDrop = {
    star_y: 0,
    end_y: 0,
    selector: '',
    height: 0,
    _init: function (selector, mobileurl) {
        this.mobileurl = mobileurl;
        this.selector = $(selector);
        this.height = this.selector.height();
        this._mouseenter();
        this._click();
    },
    _mouseenter: function (difheight) {
        var _this = this;
        this.selector.find('img').attr('src', '/qrimg.php?url=http://' + this.mobileurl);
        this.selector.hover(function (event) {
            $(this).children().not('img').fadeIn();
            $(this).height(40);
            _this._click();
        }, function () {
            $(this).children('.arrow').hide().end()
                    .find('.arrow2').attr('class', 'arrow').hide().end().find('span').removeClass('posb').text('拖动下拉扫描二维码').end()
                    .find('img').hide().end()
                    .height(20);
        });
    },
    _mousedown: function () {
        var _this = this;
        this.selector.mousedown(function (event) {
            this.star_y = event.clientY - $(this).offset().top;
            _this._mousemove();
        });
    },
    _mousemove: function () {
        var _this = this;
        this.selector.mousemove(function (event) {
            var y = event.clientY - $(this).offset().top,
                    dif = y - _this.star_y + _this.height;
            _this.selector.height(dif);
        });
        this._mouseup();
    },
    _mouseup: function () {
        this.selector.mouseup(function (event) {
            this.end_y = event.clientY - $(this).offset().top;
        });
    },
    _click: function () {
        var _this = this;
        this.selector.find('.arrow').click(function (event) {
            _this.selector.height(_this.selector.parent().height());
            _this.selector.find('img').show();
            _this.selector.find('.arrow').attr('class', 'arrow2').end().find('span').addClass('posb').text('点击收起');
        });
    },
    _clickup: function () {
        var _this = this;
        this.selector.find('.posb,.arrow2').unbind('click').click(function () {
            _this.selector.height(_this.height);
            $(this).parent().find('.arrow2').attr('class', 'arrow').end().find('span').removeClass('posb').text('拖动下拉扫描二维码');
            _this.selector.find('img').hide();
        });
    }
}

// 弹框警告
var WarningBox = function (del, warning_context) {
    this.context = $.extend(true, {
        warning_context: '∑(っ°Д ° )っ你确定删除吗？',
        IsBaseShow: false
    }, warning_context);
    this.init = function () {
        this.div = '<div class="tpl_mask" style="display: block;"></div>\n\
			<div class="warning_box box_info pos">\n\
			<div class="boxs" style="overflow-y: hidden">\n\
				<div class="box-up tc">' + this.context.warning_context + '</div>\n\
				<div class="button pr">\n\
					<input type="button" class="cancel" value="取消" />\n\
					<input type="button" class="save save_column" value="确定" />\n\
				</div>\n\
			</div>\n\
			</div>';

        $('.tpl_mask,.warning_box').remove();
        $('body').append(this.div);
        $('.tpl_mask').click(function () {
            $(this).hide();
            $(this).next().hide();
        });
        $('.warning_box .cancel').click(function () {
            $('.warning_box ').hide().prev().hide();
        });
    };
    this.ng_fuc = function () {
        this.init();
        $('.warning_box .cancel').click(function () {
            this.is = 0;
            typeof del === 'function' ? del(this.is) : null;
            $(this).parents('.box_info').hide().prev().hide();
        });
        $('.warning_box .save').click(function () {
            this.is = 1;
            typeof del === 'function' ? del(this.is) : null;
            $(this).parents('.box_info').hide().prev().hide();
        });
    };
    this.idx_fuc = function (templeType) {
        this.init();
        $('.warning_box .cancel').click(function () {
            $(this).parents('.box_info').hide().prev().hide();
        });
        $('.warning_box .save').click(function () {
            var _this = $(this);
            $.get('../template-copy?type=' + templeType, function (json) {
                checkJSON(json, function () {
                    _this.parents('.box_info').hide().prev().hide();
                    location.href = '#/diytpl?type=' + templeType + '';
                }, function () {
                    _this.parents('.box_info').hide().prev().hide();
                });//checkJSON结束
            });
        });
    };
}
// 图片上传
WarningBox.prototype = {
    _upImage: function (defaults) {
        var defaults = $.extend(true, {
            selector: null,
            aspectRatio: '', // 截图比例
            ajaxurl: '',
            IsMultiple: false, // 是否支持多图
            IsBaseShow: false, // 基础弹框显示，使用进度条上传
            IsOneNatural: false, // 单张普通上传
            oncallback: function () {}
        }, defaults);
        this.context.IsBaseShow = defaults.IsBaseShow;
        this.context.warning_context = '\
			<div>\n\
				<div class="img-container" style="width:' + document.body.clientWidth * 0.4 + 'px;height:' + document.body.clientWidth * 0.2 + 'px">\n\
					<img src="" alt="请点左下角上传要修改的图片！">\n\
				</div>\n\
			</div>\
			<div class="btn-upload">\
				<input type="file" class="sr-only" id="inputImage" name="file" ' + (defaults.IsMultiple ? 'multiple' : '') + '>\
				<div class="up_pic_btn">添加</div>\n\
			</div><label class="cutsize fr w100"></label>';
        this.init();
        if (!defaults.IsBaseShow) {
            this.fileType = '';
            $image = $('.img-container > img');
            var options = {
                aspectRatio: defaults.aspectRatio,
                preview: '.img-preview',
                crop: function (e) {
                    $('.cutsize').text(Math.round(e.width) + ' * ' + Math.round(e.height));
                }
            };
            $image.cropper(options);
            this._UpFunction($image, defaults.ajaxurl, defaults.IsBaseShow, defaults.IsOneNatural, defaults.oncallback);
        } else {
            this._Schedule(defaults.oncallback);    // 带进度条
        }
    },
    _UpFunction: function ($image, ajaxurl, IsBaseShow, IsOneNatural, oncallback) {
        var _this = this,
                $inputImage = $('#inputImage'),
                URL = window.URL || window.webkitURL,
                blobURL;
        if (URL) {
            $inputImage.change(function () {
                var files = this.files;
                var file;
                if (!$image.data('cropper')) {
                    return;
                }
                if (files && files.length == 1 && !IsOneNatural) {
                    file = files[0];
                    _this.fileType = file.type;
                    if (file.size / 1024 > 600) {
                        alert('您这张"' + file.name + '"图片大小过大，应小于600k!');
                        return false;
                    }
                    //===检测空间容量是否充足===
                    //file.size是要目标图片的大小，和裁剪后的保存图片大小不同
                    //TODO
                    if (/^image\/\w+$/.test(file.type) || /^image\/x\-\w+$/.test(file.type)) {
                        blobURL = URL.createObjectURL(file);
                        $image.one('built.cropper', function () {
                            URL.revokeObjectURL(blobURL); // Revoke when load complete
                        }).cropper('reset').cropper('replace', blobURL);
                        $inputImage.val('');
                        _this._save($image, ajaxurl, oncallback);

                    } else {
                        $body.tooltip('请上传图片！', 'warning');
                    }
                } else {
                    if (IsOneNatural) {
                        _this._filter($('#inputImage')[0].files, ajaxurl, oncallback);
                    } else {
                        if (confirm('这是多图上传！请确认好图片已经达到所需要求！')) {
                            _this._filter($('#inputImage')[0].files, ajaxurl, oncallback);
                        } else {
                            return false;
                        }
                    }
                }
            });
        } else {
            $inputImage.parent().remove();
        }
    },
    _filter: function (files, ajaxurl, oncallback) {// 多图上传
        var data = new FormData();
        //为FormData对象添加数据
        $.each(files, function (i, file) {
            if (file.type.indexOf("image") == 0) {
                if (file.size >= 512000) {
                    alert('您这张"' + file.name + '"图片大小过大，应小于500k');
                } else {
                    data.append('upload_file' + i, file);
                }
            } else {
                alert('文件"' + file.name + '"不是图片。');
            }
        });
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            cache: false,
            contentType: false, //告诉jQuery不要去处理发送的数据
            processData: false, //不将传输数据转为对象，告诉jQuery不要去设置Content-Type请求头
            success: function (json) {
                oncallback(json);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(textStatus || errorThrown);
            }
        });
        $('.warning_box').hide().prev().hide();
    },
    _Schedule: function (oncallback) {
        var callbackdata = [];
        var scrollmargin = 0;
        // 改变弹框样式
        $.ajax({
            url: '../classify-list',
            type: 'GET',
            success: function (json) {
                checkJSON(json, function (json) {
                    var d = json.data;
                    var option1 = '', pid, pname = '', id;
                    var PageId = [];
                    var G_c_id = '';
                    if (json.err == 0 || json.err == 1000) {
                        var option1 = '';


                        $.each(d, function (idx, ele) {

                            if (ele.type < 5) {

                                if (G_c_id == ele.id) {
                                    pid = ele.p_id;
                                    pname = ele.name;
                                    id = ele.id;
                                }
                                if (ele.type == '4') {
                                    PageId.push(ele.id);
                                }

                                option1 += '<li><a class="parents' + (ele.childmenu != null ? ' not-allowed' : '') + '" data-id="' + ele.id + '" data-size="' + ele.img_width + ',' + ele.img_height + ',' + ele.img_forcesize + '">' + ele.name + '</a></li>';
                                var NextChild = ele;
                                var num = 2;

                                var LoopChlid = function (NextChild, num) {
                                    if (NextChild.childmenu != null) {
                                        $.each(NextChild.childmenu, function (k, v) {
                                            if (v.type < 5) {
                                                if (G_c_id == v.id) {
                                                    pid = v.p_id;
                                                    pname = v.name;
                                                    id = v.id;
                                                }
                                                if (v.type == '4') {
                                                    PageId.push(v.id);
                                                }
                                                option1 += '<li><a class="LevelChild' + num + (v.childmenu != null ? ' not-allowed' : '') + '" data-pid="' + v.p_id + '" data-id="' + v.id + '" data-size="' + v.img_width + ',' + v.img_height + ',' + v.img_forcesize + '">├' + v.name + '</a></li>';
                                                if (v.childmenu != null) {
                                                    NextChild = v;
                                                    num++;
                                                    LoopChlid(NextChild, num);
                                                    num--;
                                                }
                                            }
                                        });
                                    }
                                }
                                LoopChlid(NextChild, num);
                            }
                        });
                        _op = '<div class="classify">\
								<span class="fl mr5">栏目分类：</span>\
								<div class="dropdown fl">\
								<div class="selectBox" type="text">' + pname + '</div><span class="arrow"></span>\
								<input class="selectBox_val" name="column_name" type="hidden" value="' + id + '"/>\
								<ul>' + option1 + '</ul>\
								</div><span class="fl" style="margin-left: 30px">可见站点：</span>\
						<span style="color:#333; display: inline ">\
							<input type="checkbox" id="1" name="vehicle" value="pc_show" style=" display:none;"><label class="labe2"></label>PC\
						</span>\
						<span style="color:#333; display: inline ">\
							<input type="checkbox" id="2" name="vehicle" value="mobile_show" style=" display:none;"><label class="labe2"></label>手机\
						</span></div>';
                    }
                    $('.warning_box').append(_op);
                    $('.warning_box .classify').css({'position': 'absolute', 'top': '50px', 'left': '50px'});
                    $(".classify").on('click', '.labe2', function () {
                        if (!$(this).hasClass("chirdchecked")) {
                            $(this).addClass("chirdchecked");
                            $(this).siblings("input[type=checkbox]").attr("checked", true);
                        } else {
                            $(this).removeClass("chirdchecked");
                            $(this).siblings("input[type=checkbox]").attr("checked", false);
                        }
                    });
                    // 下拉框模拟事件
                    DropdownEvent(PageId);
                    //                $('.not-allowed').MoveBox({context:'此为含有子级的父级栏目或为单页内容页，不支持选择！'});
                });
                //                            var classifylist;
                //                            function lowestChild(value){
                //                                if(value.childmenu){
                //                                    $.each(value.childmenu,function(key,val){
                //                                        lowestChild(val);
                //                                    });
                //                                }else{
                //                                    if(value.type<4){
                //                                        classifylist +='<option value ="'+value.id+'">'+value.name+'</option>';
                //                                    }
                //                                }
                //                            }
                //                            classifylist='<select name="c_id">';
                //                                $.each(json.data,function(k,v){
                //                                    console.log(v.id);
                //                                    console.log(v.name);
                //                                    if(v.type<4){
                //                                        lowestChild(v);
                //                                    }
                //                                });       
                //                            classifylist +='</select>';
                //                            $('.warning_box .button').append(classifylist);
                ////                            console.log(json);
            }
        });
        $("select[name=c_id]").on("change", function () {
            alert($(this).val);
        })
        $('.warning_box .button').addClass('batchbtn');
        //        $('.batchbtn .save').val('批量编辑').css('cursor','not-allowed');
        $('.batchbtn .save').hide();
        $('.img-container>img').remove();
        $('.btn-upload .up_pic_btn').remove();
        $('.btn-upload').css('position', 'inherit');
        $('.batchbtn .cancel').click(function () {
            $('.warning_box').hide().prev().hide();
        });
        // 添加多图生成文章
        $('#inputImage').uploadify({
            'swf': 'images/uploadify.swf',
            'uploader': '../fileupload?target=articles',
            'removeCompleted': false,
            'buttonText': "添加",
            'onUploadStart': function (file) {
                $('.uploadify-queue .finish').remove();
                $('.batchbtn .save').show();
                $('.batchbtn .save').unbind().val('生成完成').css('cursor', 'not-allowed');
                $('.tpl_mask').unbind();
            },
            'onUploadSuccess': function (file, data, response) {

                var data = eval("(" + data + ")");
                var html;
                data.data[0].filename = file.name;
                $('#' + file.id).attr('data-id', data.data[0].name.split('.')[0]);
                html = '<div  class="article" data-id="' + data.data[0].name.split('.')[0] + '" style="width:100%;">\
											<input type="hidden" name="img' + data.data[0].name + '" class="img" value="' + data.data[0].name + '" />\
											<input type="text" name="title' + data.data[0].filename.substr(0,data.data[0].filename.lastIndexOf('.')) + '" class="title" value="' + data.data[0].filename.substr(0,data.data[0].filename.lastIndexOf('.')) + '" style="width:100%;" />\
										</div>'
                $('#' + file.id).children('.uploadify-progress').append('<div class="batch_title">' + html + '</div>');
                scrollmargin = $(".uploadify-queue").scrollTop() + $('#' + file.id).offset().top - $('.uploadify-queue').offset().top;
                $(".uploadify-queue").animate({scrollTop: scrollmargin}, 1000);
                callbackdata.push(data);
            },
            'onQueueComplete': function (queueData) {
                oncallback(callbackdata);
            }
        });
    },
    _save: function ($image, ajaxurl, oncallback) {
        var _this = this;
        $('.warning_box .save').click(function () {
            var data = $image.cropper('getCroppedCanvas').toDataURL(_this.fileType,0.94);
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {image: data},
                dataType: 'json',
                cache: false,
                success: function (json) {
//                    if (json.err) {//有错误!=0
//                        alert(json.msg);
//                    } else {
//                        get_capacity();
                    oncallback(json);
//                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus || errorThrown);
                }
            });
            $('.warning_box').hide().prev().hide();
        });
        $('.warning_box .cancel').click(function () {
            $('.warning_box').hide().prev().hide();
        });
    }
}
// 模拟下拉框事件
function DropdownEvent(PageId) {
    $(".selectBox").click(function () {
        var _this = $(this), ul = _this.siblings('ul');
        $('.dropdown ul').not(ul).hide();
        if (ul.css("display") == "none") {
            ul.slideDown("fast");
            _this.siblings('.arrow').css({'border-color': 'transparent transparent rgb(180,180,180) transparent', 'top': '2px'});
        } else {
            ul.slideUp("fast");
            _this.siblings('.arrow').css({'border-color': 'rgb(180,180,180) transparent transparent transparent', 'top': '7px'});
        }
    });
    if (PageId) {
        $(".dropdown ul li a").mouseenter(function () {
            var _this = $(this);
            if (PageId.indexOf(_this.data('id')) != -1) {
                _this.addClass('not-allowed');
                $('.not-allowed').MoveBox({context: '此为单页类型或者此级下有子级，不支持选择！'});
            } else {
                if (!_this.hasClass('not-allowed')) {
                    _this.click(function () {
                        var txt = _this.text();
                        $(".selectBox").text(txt);
                        var value = _this.data("id");
                        $(".dropdown ul").slideUp("fast");
                        $(".selectBox_val").val(value);
                        $(".dropdown .arrow").css({'border-color': 'rgb(180,180,180) transparent transparent transparent', 'top': '7px'});
                    });
                }
            }
        });
    } else {
        $(".dropdown ul li a").click(function () {
            var _this = $(this);
            if (_this.hasClass('not-allowed')) {
                return false;
            }
            var ul = _this.closest('ul');
            var value = _this.data("id");
            ul.siblings('.selectBox').attr('data-id', value).text(_this.text());
            ul.slideUp("fast");
            ul.siblings('.selectBox_val').val(value);
            ul.siblings('.arrow').css({'border-color': 'rgb(180,180,180) transparent transparent transparent', 'top': '7px'});
            // 页面展示下拉框
            if (_this.data('size') != undefined) {
                ul.siblings('.selectBox_name').val(_this.data("name"));
                ul.siblings('.selectBox').attr('data-size', _this.data("size"));
            }
        });
    }
}
// 转换栏目类型
function column_type(type) {
    var col_type;
    switch (type) {
        case 0:
            col_type = '不限';
            break;
        case 1:
            col_type = '文字列表';
            break;
        case 2:
            col_type = '图片列表';
            break;
        case 3:
            col_type = '图文列表';
            break;
        case 4:
            col_type = '内容单页';
            break;
        case 5:
            col_type = '功能';
            break;
        case 6:
            col_type = '外链';
            break;
        case 7:
            col_type = '微信功能';
            break;
        case 8:
            col_type = '直达号功能';
            break;
        case 9:
            col_type = '万用表单';
            break;
        case 10:
            col_type = '海报单页';
            break;
    }
    return col_type;
}
// 错误验证
function checkJSON(json, callback, fail_callback) {
    switch (json.err) {
        case 0:
            typeof (callback) === 'function' ? callback(json) : null;
            break;
        case 1000:
            typeof (callback) === 'function' ? callback(json) : null;
            break;
        case 401:
            alert('登入失效！');
            location.hash = 'login';
            break;
        case 303:
            alert('账号密码相同，请先修改密码！');
            location.hash = '#/user';
            break;
        default:
            alert(json.msg);
            typeof (callback) === 'function' ? fail_callback == undefined ? null : fail_callback(json) : null;
            break;

    }
}
//保存提示效果
var Hint_box = function (text) {
    if (text == undefined) {
        text = '保存成功！';
    }
    $('.jumbotron,#diy').append('<div class="hint_box">' + text + '</div>');
    setTimeout(function () {
        $('.hint_box').remove();
    }, 2000);
};
// 全局勾选框
function checkjs(parame) {
    $("." + parame + "-tb").unbind("click").on('click', '.label', function () {
        var num = 0
                , _this = $(this);
        if (_this.hasClass("allcheck"))
        {
            if (!_this.hasClass("nchecked")) {
                $(".label").addClass("nchecked");
                $(".label").siblings("input[type=checkbox]").attr("checked", true);
            } else {
                $(".label").removeClass("nchecked");
                $(".label").siblings("input[type=checkbox]").attr("checked", false);
            }
        } else if (_this.parents(".firstlist").attr("data-aid")) {
            if (!_this.hasClass("nchecked")) {
                _this.addClass("nchecked");
                _this.siblings("input[type=checkbox]").attr("checked", true);
                $("tr[data-parent='" + _this.parents(".firstlist").attr("data-aid") + "']").find(".label").each(function () {
                    _this.addClass("nchecked");
                    _this.siblings("input[type=checkbox]").attr("checked", true);
                });
            } else {
                _this.removeClass("nchecked");
                $(".allcheck").removeClass("nchecked");
                _this.siblings("input[type=checkbox]").attr("checked", false);
                $("tr[data-parent='" + _this.parents(".firstlist").attr("data-aid") + "']").find(".label").each(function () {
                    _this.removeClass("nchecked");
                    _this.siblings("input[type=checkbox]").attr("checked", false);
                });
            }
        } else {
            if (!_this.hasClass("nchecked")) {
                _this.addClass("nchecked");
                _this.siblings("input[type=checkbox]").attr("checked", true);
            } else {
                _this.removeClass("nchecked");
                $(".allcheck").removeClass("nchecked");
                _this.siblings("input[type=checkbox]").attr("checked", false);
            }
            $('.article-check label.label').each(function (i) {
                if (_this.hasClass('nchecked')) {
                    num++;
                }
            });
        }
        //        勾选数目判断批量编辑显隐
        //        num >=2 ? $('.batchedit').fadeIn() : $('.batchedit').fadeOut();
        return false;
    });
    $(".jumbotron").on('click', '.labe2', function () {
        if (!$(this).hasClass("chirdchecked")) {
            $(this).addClass("chirdchecked");
            $(this).siblings("input[type=checkbox]").attr("checked", true);
        } else {
            $(this).removeClass("chirdchecked");
            $(this).siblings("input[type=checkbox]").attr("checked", false);
        }
    });
}
// 图标选择
function icon_choose(limintHeight) {
    this.clicks = function () {
        var _this = this;
        $('.icon_box>i').unbind('click').on('click', function (event) {
            var event_this = $(this);
            if (event_this.offset().top > limintHeight) {
                event_this.siblings('.icon_ousidebox').css({'top': 'auto', 'bottom': '30px'}).end()
                        .siblings('.arrow').css({'border-color': 'rgba(0,0,0,0.7) transparent transparent transparent', 'bottom': '18px', 'top': 'auto'});
            }
            if (event_this.parent().hasClass('in')) {
                event_this.siblings('.icon_ousidebox,.arrow').fadeOut();
                event_this.parent().removeClass('in')
            } else {
                event_this.siblings('.icon_ousidebox,.arrow').fadeIn();
                event_this.parent().addClass('in')
            }
            $.get('../icon-list', function (json) {
                checkJSON(json, function (json) {
                    var _div = '';
                    $.each(json.icons, function (index, el) {
                        _div += '<li name="' + el.replace('&', '&amp;') + '"><i class="iconfonts">' + el + '</i></li>';
                    });
                    event_this.siblings('.icon_ousidebox').find('ul').html(_div);
                    _this._clickhide();
                });
            });
        })
    },
            this._clickhide = function () {
                $('.icon_ousidebox ul li').unbind('click').click(function (event) {
                    var _Pthis = $(this).closest('.icon_box');
                    _Pthis.children('i').before('<i class="iconfonts">' + $(this).attr('name') + '</i></li>').remove();
                    _Pthis.children('.icon_input').val($(this).attr('name'));
                    $('.icon_ousidebox,.icon_box .arrow').fadeOut();
                    _Pthis.removeClass('in')
                    _this.clicks();
                });
            }
}

function insertText(obj, str) {
    if (document.selection) {
        obj.focus();
        var sel = document.selection.createRange();
        sel.text = str;
        var range = obj.createTextRange();
        range.collapse(true);
        range.moveStart('character', 10);
        range.moveEnd('character', 20);
        range.select();
    } else if (typeof obj.selectionStart === 'number' && typeof obj.selectionEnd === 'number') {
        var startPos = obj.selectionStart,
                endPos = obj.selectionEnd,
                cursorPos = startPos,
                tmpStr = obj.value;
        obj.value = tmpStr.substring(0, startPos) + str + tmpStr.substring(endPos, tmpStr.length);
        cursorPos += str.length;
        obj.selectionStart = obj.selectionEnd = cursorPos;
        obj.setSelectionRange(startPos, cursorPos);
        // obj.focus();
    } else {
        obj.value += str;
    }
}

/**
 * ===获取url参数===
 * @returns {string}
 */
function getUrlParam(name) {
    var reg = new RegExp("(^|&|\\?)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.hash.substr(1).match(reg); //匹配目标参数
    if (r != null) {
        return unescape(r[2]);
    } else {
        return null; //返回参数值
    }
}

/**
 * ===初始化容量===
 * @returns {undefined}
 */
function init_capacity() {
    $('#capacity_div').html('已用容量/总容量：<span id="capacity_use">-</span>/<span id="capacity">-</span>');
    get_capacity();
}

/**
 * ===获取容量信息===
 * @returns {undefined}
 */
function get_capacity() {
    $.get('../capacity-info', function (json) {
//        console.log(json);
//        console.log('capacity-info');
        var data = json.data;
        if (json.err == 0) {
            $('#capacity_use').html(data.capacity_use);
            $('#capacity').html(data.capacity);
        } else {
            $('#capacity_use').html(data.capacity_use);
            $('#capacity').html(data.capacity);
            $('#capacity_use').css('color', 'red');
        }
    });
}

/**
 * ===查看是否有双账号用户===
 * @returns {undefined}
 */
function init_bind() {
    $.get('../init-bind', function (json) {
        if (json != 0) {
            var img = '<img src="images/switch_account.png" style="width:16px;" title="双站切换" />';
            $('#switch_bind').html(img);
            $('#switch_bind').attr('data-cusid', json);
        }
    });
}

/**
 * ===获取绑定账号===
 * @returns {undefined}
 */
function get_bind_account() {
    var cusid = $('#switch_bind').attr('data-cusid');
    $.post('../bind-auto-login', {switch_cus_id: cusid}, function (json) {
        if (json.err) {
            alert(json.msg);
        } else {
            location.href = json.msg;
        }
    });
}

/**
 * ===ueditor编辑器，获取文件存储名===
 * @param {type} html
 * @returns {undefined}
 */
function ueditor_regular(html) {
    //
//    var i=1;
//    j\k\l\m\n
//ueditor/php/(\w|/)*.(\w)+
//    $.get('../');
//    var editor = UE.getEditor('container');
//    var _html = editor.getContent()
//    console.log(_html);
//    return html;
}


