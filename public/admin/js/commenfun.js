
function heightauto(){
	//自动获取高度
	var col_list=$(".box_info").height()/2;
	$(".box_info").css({
		"marginTop":"-"+col_list+"px"
	})
}
function tanchuang(Save_id){
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
    $('.mask,.box_info .boxs .cancel,.save_column').click(function(){
        $('#bomb-box').removeClass('in').fadeOut(function(){
			$(".box_info").css({
			"marginTop":"-"+2000+"px"
			});
            $('.box-up').text('添加栏目');
            clear_info();
		});
    });
    $('.addcolumn,.addlist,.addauto').click(function(){
        $('#bomb-box').fadeIn(function(){
			heightauto()
		});
        $('#bomb-box').addClass('in');
        clear_info();
        Save_id(1);
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
        $('.col_icon_box,.col_icon .arrow').fadeOut();
        $('.col_icon_box').siblings('i').removeClass('in');
        $('#models .mod_border').each(function(){
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
ercodeDrop._init('.phone-content .notice')



    