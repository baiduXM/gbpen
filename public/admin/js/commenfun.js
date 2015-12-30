
function heightauto(){
    //自动获取高度
    var col_list=$(".box_info").height()/2;
    $(".box_info").css({
        "marginTop":"-"+col_list+"px"
    })
}
function tanchuang(ColumnInit){
    // 弹窗处理
    $('.close').click(function(){
        $('#bomb-box').removeClass('in').fadeOut(function(){
            $(".box_info").css({
            "marginTop":"-"+2000+"px"
            });
            $('.box-up').text('添加栏目');
            clear_info();
        }); 
    });
    $('.mask,.box_info .boxs .cancel,.save_column,.box_info .boxs .save').click(function(){
        $('#bomb-box').removeClass('in').fadeOut(function(){
            $(".box_info").css({
            "marginTop":"-"+2000+"px"
            });
            $('.box-up').text('添加栏目');
            clear_info();
        });
    });
    $('.addcolumn,.addlist,.addauto').click(function(){
        $(this).hasClass('addcolumn') ? ColumnInit.Column_Upload('') : null;
        $('#bomb-box').fadeIn(function(){
            heightauto()
        });
        $('#bomb-box').addClass('in');
        clear_info();
        ColumnInit._Save_id(1);
    });
    //数据清除
    function clear_info(){
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
        $('.sites input[value=pc]').attr('checked','');
        $('.sites label.lb_pc').removeClass('chirdchecked');
        $('.sites input[value=mobile]').attr('checked','');
        $('.sites label.lb_mobile').removeClass('chirdchecked');
        $('.sites input[value=wechat]').attr('checked','');
        $('.sites label.lb_wechat').removeClass('chirdchecked');
        $('.sites label.lb_wechat').removeClass('chirdchecked');
        $('#lottery1,#lottery2,#lottery_mg').hide();
        $('#out_url').hide();
        $('#page_editor').hide();
        $('#inside_model').addClass('none');
        $('#models').hide();
        $('.icon_ousidebox,.icon_box .arrow').fadeOut();
        $('.icon_ousidebox').siblings('i').removeClass('in');
        $('.box-down .mod_border').each(function(){
            $(this).hasClass('cu')?$(this).removeClass('cu'):'';
        });
    }
}
// 二维码
var ercodeDrop = {
    star_y : 0,
    end_y  : 0,
    selector  : '',
    height : 0,
    _init  : function(selector){
        this.selector = $(selector);
        this.height = this.selector.height();
        this._mouseenter();
        this._click();
    },
    _mouseenter : function(difheight){
        var _this = this;
        this.selector.hover(function(event) {
            $(this).children().not('img').fadeIn();
            $(this).height(40);
            _this._click();
        },function(){
            $(this).children('.arrow').hide();
            $(this).find('.arrow2').attr('class','arrow').hide().end().find('span').removeClass('posb').text('拖动下拉扫描二维码');
            $(this).find('img').hide();
            $(this).height(20);
        });
    },
    _mousedown : function(){
        var _this = this;
        this.selector.mousedown(function(event) {
            this.star_y = event.clientY - $(this).offset().top;
            _this._mousemove();
        });
    },
    _mousemove : function(){
        var _this = this;
        this.selector.mousemove(function(event) {
            var y = event.clientY - $(this).offset().top,
                dif = y - _this.star_y + _this.height;
            _this.selector.height(dif);
        });
        this._mouseup();
    },
    _mouseup : function(){
        this.selector.mouseup(function(event) {
            this.end_y = event.clientY - $(this).offset().top;
        });
    },
    _click : function(){
        var _this = this;
        this.selector.find('.arrow').click(function(event) {
            _this.selector.height(_this.selector.parent().height());
            _this.selector.find('img').fadeIn(800);
            _this.selector.find('.arrow').attr('class','arrow2').end().find('span').addClass('posb').text('点击收起');
        });
    },
    _clickup : function(){
        var _this = this;
        this.selector.find('.posb,.arrow2').unbind('click').click(function(){
            _this.selector.height(_this.height);
            $(this).parent().find('.arrow2').attr('class','arrow').end().find('span').removeClass('posb').text('拖动下拉扫描二维码');
            _this.selector.find('img').hide();
        });
    }
}
ercodeDrop._init('.phone-content .notice');

// 弹框警告
var WarningBox = function(del,warning_context){
    this.context = $.extend(true,{
        warning_context : '∑(っ°Д ° )っ你确定删除吗？',
        IsBaseShow : false
    },warning_context);
    this.init = function(){
        this.div = '<div class="tpl_mask" style="display: block;"></div>\n\
        <div class="warning_box box_info pos">\n\
        <div class="boxs" style="overflow-y: hidden">\n\
            <div class="box-up tc">'+this.context.warning_context+'</div>\n\
            <div class="button pr">\n\
                <input type="button" class="cancel" value="取消" />\n\
                <input type="button" class="save save_column" value="确定" />\n\
            </div>\n\
        </div>\n\
        </div>';

        $('.tpl_mask,.warning_box').remove();
        $('body').append(this.div);
        $('.tpl_mask').click(function(){
            $(this).hide();
            $(this).next().hide();
        });
        $('.warning_box .cancel').click(function(){
            $('.warning_box ').hide().prev().hide();
        });
    };
    this.ng_fuc =  function(){
        this.init();
        $('.warning_box .cancel').click(function(){
            this.is = 0;
            typeof del === 'function' ? del(this.is) : null;
            $(this).parents('.box_info').hide().prev().hide();
        });
        $('.warning_box .save').click(function(){
            this.is = 1;
            typeof del === 'function' ? del(this.is) : null;
            $(this).parents('.box_info').hide().prev().hide();
        });
    };
    this.idx_fuc =  function(templeType){
        this.init();
        $('.warning_box .cancel').click(function(){
            $(this).parents('.box_info').hide().prev().hide();
        });
        $('.warning_box .save').click(function(){
            $.get('../template-copy',function(json){});
            $(this).parents('.box_info').hide().prev().hide();
            location.href = '#/diytpl?type='+templeType+'';
        });
    };
}
// 图片上传
WarningBox.prototype = {
    _upImage : function(defaults){
        var defaults = $.extend(true, {
            selector    : null,
            aspectRatio : '',
            ajaxurl     : '',
            IsMultiple  : false,
            IsBaseShow  : false,
            oncallback  : function(){}
        }, defaults);
        this.context.IsBaseShow = defaults.IsBaseShow;
        this.context.warning_context = '\
        <div>\n\
            <div class="img-container" style="width:'+document.body.clientWidth*0.4+'px;height:'+document.body.clientWidth*0.2+'px">\n\
                <img src="" alt="请点左下角上传要修改的图片！">\n\
            </div>\n\
        </div>\
        <div class="btn-upload">\
            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*" '+(defaults.IsMultiple ? 'multiple' : '')+'>\
            <div class="up_pic_btn">添加</div>\n\
        </div><label class="cutsize fr w100"></label>';
        this.init();
        if(!defaults.IsBaseShow){
            this.fileType = '';
            $image = $('.img-container > img');
            var options = {
                aspectRatio: defaults.aspectRatio,
                preview: '.img-preview',
                crop: function (e) {
                    $('.cutsize').text(Math.round(e.width)+' * '+Math.round(e.height));
                }
            };
            $image.cropper(options);
            this._UpFunction($image,defaults.ajaxurl,defaults.IsBaseShow,defaults.oncallback);
        }else{
            this._Schedule(defaults.oncallback);    // 带进度条
        }
    },
    _UpFunction : function($image,ajaxurl,IsBaseShow,oncallback){
        var _this = this,
            $inputImage = $('#inputImage'),
            URL = window.URL || window.webkitURL,
            blobURL;
        if (URL) {
            $inputImage.change(function() {
                var files = this.files;
                var file;
                if (!$image.data('cropper')) {
                    return;
                }
                if (files && files.length == 1) {
                    file = files[0];
                    _this.fileType = file.type;
                    if(file.size/1024 > 600){
                        alert('您这张"'+ file.name +'"图片大小过大，应小于600k!');   
                        return false;
                    }
                    if (/^image\/\w+$/.test(file.type)) {
                        blobURL = URL.createObjectURL(file);
                        $image.one('built.cropper', function() {
                            URL.revokeObjectURL(blobURL); // Revoke when load complete
                        }).cropper('reset').cropper('replace', blobURL);
                        $inputImage.val('');
                        _this._save($image,ajaxurl,oncallback);
                    } else {
                        $body.tooltip('请上传图片！', 'warning');
                    }
                }else{
                    if(confirm('这是多图上传！请确认好图片已经达到所需要求！')){
                        _this._filter($('#inputImage')[0].files,ajaxurl,oncallback);
                    }else{
                        return false;
                    }
                }
            });
        } else {
            $inputImage.parent().remove();
        }
    },
    _filter: function(files,ajaxurl,oncallback) {// 多图上传
        var data = new FormData();
        //为FormData对象添加数据
        $.each(files, function(i, file) {
            if (file.type.indexOf("image") == 0) {
                if (file.size >= 512000) {
                    alert('您这张"'+ file.name +'"图片大小过大，应小于500k');    
                } else {
                    data.append('upload_file' + i, file);
                }           
            } else {
                alert('文件"' + file.name + '"不是图片。');    
            }
        });
        $.ajax({
            url : ajaxurl, 
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
    },
    _Schedule : function(oncallback){
        var callbackdata = [];
        // 改变弹框样式
        $('.warning_box .button').addClass('batchbtn');
        $('.batchbtn .save').val('批量编辑').css('cursor','not-allowed');
        $('.img-container>img').remove();
        $('.btn-upload .up_pic_btn').remove();
        $('.btn-upload').css('position','inherit');
        $('.batchbtn .cancel').click(function(){
            $('.warning_box ').hide().prev().hide();
        })
        // 添加多图生成文章
        $('#inputImage').uploadify({
            'swf'      : 'images/uploadify.swf',
            'uploader' : '../file-upload?target=articles',
            'removeCompleted' : false,
            'buttonText' : "添加",
            'onUploadStart' : function(file) {
                $('.uploadify-queue .finish').remove();
                $('.batchbtn .cancel').unbind().val('生成完成').css('cursor','not-allowed');
                $('.tpl_mask').unbind();
            },
            'onUploadSuccess' : function(file, data, response){
                var data = eval("("+data+")");
                data.data[0].filename = file.name
                $('.uploadify-queue-item').attr('data-id',data.data[0].name);
                callbackdata.push(data);
            },
            'onQueueComplete' : function(queueData) {
                oncallback(callbackdata);
            }
        });
    },
    _save : function($image,ajaxurl,oncallback){
        var _this = this;
        $('.warning_box .save').click(function(){
            var data = $image.cropper('getCroppedCanvas').toDataURL(_this.fileType);
            $.ajax({
                url : ajaxurl, 
                type: 'POST',
                data: {image:data},
                dataType: 'json',
                cache: false,
                success: function (json) {
                    oncallback(json);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(textStatus || errorThrown);
                }
            });
            $('.warning_box ').hide().prev().hide();
        });
        $('.warning_box .cancel').click(function(){
            $('.warning_box ').hide().prev().hide();
        });
    }
}
// 模拟下拉框事件
function DropdownEvent(PageId){
    $(".selectBox").click(function(){
        var ul = $(this).siblings('ul'); 
        $('.dropdown ul').not(ul).hide();
        if(ul.css("display")=="none"){ 
            ul.slideDown("fast"); 
            $(this).siblings('.arrow').css({'border-color':'transparent transparent rgb(180,180,180) transparent','top':'2px'});
        }else{ 
            ul.slideUp("fast"); 
            $(this).siblings('.arrow').css({'border-color':'rgb(180,180,180) transparent transparent transparent','top':'7px'});
        } 
    }); 
    if(PageId){
        $(".dropdown ul li a").mouseenter(function(){
            var _this = $(this);
            if(PageId.indexOf($(this).data('id')) != -1){
                $(this).addClass('not-allowed');
                $('.not-allowed').MoveBox({context:'此为单页类型或者此级下有子级，不支持选择！'});
            }else{
                if(!$(this).hasClass('not-allowed')){
                    $(this).click(function(){
                        var txt = $(this).text(); 
                        $(".selectBox").text(txt); 
                        var value = $(this).data("id"); 
                        $(".dropdown ul").slideUp("fast"); 
                        $(".selectBox_val").val(value); 
                        $(".dropdown .arrow").css({'border-color':'rgb(180,180,180) transparent transparent transparent','top':'7px'});
                    });
                }
            }
        });
    }else{
        $(".dropdown ul li a").click(function(){
            if($(this).hasClass('not-allowed')){
                return false;
            }
            var ul = $(this).closest('ul');
            var value = $(this).data("id"); 
            ul.siblings('.selectBox').attr('data-id',value).text($(this).text()); 
            ul.slideUp("fast"); 
            ul.siblings('.selectBox_val').val(value); 
            ul.siblings('.arrow').css({'border-color':'rgb(180,180,180) transparent transparent transparent','top':'7px'});
            // 页面展示下拉框
            if($(this).data('size') != undefined){
                ul.siblings('.selectBox_name').val($(this).data("name"));
                ul.siblings('.selectBox').attr('data-size',$(this).data("size"));
            }
        });
    }
}
// 转换栏目类型
function column_type(type){
    var col_type;
    switch(type){
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
        }
    return col_type;
}
// 错误验证
function checkJSON(json, callback, fail_callback) {
    if (json.err == 0) {
        typeof callback ==='function' ? callback(json) : null;
    }else if(json.err == 401){
        alert('登入失效！');
        location.hash = 'login';
    }else{
        alert(json.msg);
        typeof callback ==='function' ? fail_callback == undefined ? null : fail_callback(json) : null;
    }
}
//保存提示效果
var Hint_box = function(){
    $('.jumbotron,#diy').append('<div class="hint_box">保存成功！</div>');
    setTimeout(function(){
        $('.hint_box').remove();
    },2000);
};
// 全局勾选框
function checkjs(parame){
    $("."+parame+"-tb").unbind( "click" ).on('click','.label',function() {
        var num = 0;
        if($(this).hasClass("allcheck"))
        {
            if(!$(this).hasClass("nchecked")){
                $(".label").addClass("nchecked");
                $(".label").siblings("input[type=checkbox]").attr("checked", true);
            }else{
                $(".label").removeClass("nchecked");
                $(".label").siblings("input[type=checkbox]").attr("checked", false);
            }
        }else if($(this).parents(".firstlist").attr("data-aid")){
            if(!$(this).hasClass("nchecked")){
                $(this).addClass("nchecked");
                $(this).siblings("input[type=checkbox]").attr("checked", true);
                $("tr[data-parent='"+$(this).parents(".firstlist").attr("data-aid")+"']").find(".label").each(function(){
                  $(this).addClass("nchecked");
                  $(this).siblings("input[type=checkbox]").attr("checked", true);
                });
            }else{
                $(this).removeClass("nchecked");
                $(".allcheck").removeClass("nchecked");
                $(this).siblings("input[type=checkbox]").attr("checked", false);
                $("tr[data-parent='"+$(this).parents(".firstlist").attr("data-aid")+"']").find(".label").each(function(){
                  $(this).removeClass("nchecked");
                  $(this).siblings("input[type=checkbox]").attr("checked", false);
                });
            }
        }else{
            if (!$(this).hasClass("nchecked")) {
                $(this).addClass("nchecked");
                $(this).siblings("input[type=checkbox]").attr("checked", true);
            }else {
                $(this).removeClass("nchecked");
                $(".allcheck").removeClass("nchecked");
                $(this).siblings("input[type=checkbox]").attr("checked", false);
            }
            $('.article-check label.label').each(function(i){
                if($(this).hasClass('nchecked')){
                    num++;
                }
            });
        }
        num >=2 ? $('.batchedit').fadeIn() : $('.batchedit').fadeOut();
        return false;
    });
    $(".jumbotron").on('click','.labe2',function () {
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
function icon_choose(limintHeight){
    this.clicks = function(){
        var _this = this;
        $('.icon_box>i').unbind('click').on('click',function(event) {
            if($(this).offset().top > limintHeight){
                $(this).siblings('.icon_ousidebox').css({'top':'auto','bottom':'30px'}).end()
                .siblings('.arrow').css({'border-color':'rgba(0,0,0,0.7) transparent transparent transparent','bottom':'18px','top':'auto'});
            }
            var event_this = $(this);
            if($(this).parent().hasClass('in')){
                $(this).siblings('.icon_ousidebox,.arrow').fadeOut();
                $(this).parent().removeClass('in')
            }else{
                $(this).siblings('.icon_ousidebox,.arrow').fadeIn();
                $(this).parent().addClass('in')
            }
            $.get('../icon-list',function(json){
                checkJSON(json, function(json){
                    var _div = '';
                    $.each(json.icons,function(index, el) {
                        _div += '<li name="'+el.replace('&', '&amp;')+'"><i class="iconfonts">'+el+'</i></li>';
                    });
                    event_this.siblings('.icon_ousidebox').find('ul').html(_div);
                    _this._clickhide();
                });
            });
        })  
    },
    this._clickhide = function(){
        var _this = this;
        $('.icon_ousidebox ul li').unbind('click').click(function(event) {
            var _Pthis = $(this).closest('.icon_box');
            _Pthis.children('i').before('<i class="iconfonts">'+$(this).attr('name')+'</i></li>').remove();
            _Pthis.children('.icon_input').val($(this).attr('name'));
            $('.icon_ousidebox,.icon_box .arrow').fadeOut();
            _Pthis.removeClass('in')
            _this.clicks();
        });
    }
}
