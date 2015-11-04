/**
 *
 * Mask
 * 
 * @name Mask
 * @description box mask and button mask.
 * @author Esone (http://it.ee01.me)
 * @version 2.1 (2013.6.27 updated)
 * @copyright (c) 2013 Sina
 *
 */


 /**
 * Mask
 *
 * @constructor Mask
 *
 * @extends jquery, jquery.floatDiv
 *
 * @example
	var mask = new Mask();
	
	var _loading = buttonLoading(this);
	var _loading = buttonLoading(this, {zIndex:300});	// for dialog
 *
 **/

// Box Mask
var Mask = function(options) {
	var _this = this;
	_this.obj = null;
	options = $.extend({
		opacity: 0.5,
		zIndex: 120,
		backgroundColor: '#000'
	}, options);
	
	$(window).bind({
		'resize.mask' : function(){
			if(_this.obj != null)
			{
				_this.obj.css({
					'width':$(window).width(),
					'height':$(window).height()
				});
			}
		}
	});
	_this.show = function(){
		if(_this.obj != null)
		{
			_this.hide();
		}
		_this.obj = $('<div/>');
		_this.obj.floatdiv({'left':0, 'top':0, 'zIndex':options.zIndex});
		_this.obj.css({
			'width':$(window).width(),
			'height':$(window).height(),
			'opacity':options.opacity,
			'background-color':options.backgroundColor
		});
		_this.obj.appendTo('body');
	};
	_this.hide = function(){
		if(_this.obj != null)
		{
			_this.obj.remove();
			_this.obj = null;
		}
	};
};

// Button Loading Mask
var buttonLoading = function (button, options) {
	button = button || "body";
	options = options || {};
	var backgroundColor = (/.*\.swf$/i).test(options.backgroundImage) ? 'transparent' : '#000';
	var opacity = (/.*\.swf$/i).test(options.backgroundImage) ? 1 : 0.5;
	switch (options.backgroundImage) {
		case 'gif':
			options.backgroundImage = 'http://minnan.sinaimg.cn/utils/images/loading_button.gif';
			break;
		case 'swf':
			options.backgroundImage = 'http://minnan.sinaimg.cn/utils/images/star.swf';
			break;
	}
	options = $.extend({
		timeout: 10000,
		timeoutMsg: '你的网速太卡了点..过会儿重新提交试试~',
		opacity: opacity,
		zIndex: 0,
		backgroundColor: backgroundColor,
		backgroundImage: 'http://minnan.sinaimg.cn/utils/images/loading_button.gif'
	}, options);
	var _loading = $('<div class="loading">');
	
	$("body").append(_loading);
	if (options.zIndex) {
		_loading.css({
			"position": "relative",
			"z-index": options.zIndex
		});
	}
	_loading.css({
		width: $(button).outerWidth(),
		height: $(button).outerHeight(),
		background: "url(" + options.backgroundImage + ") no-repeat center center " + options.backgroundColor,
		opacity: options.opacity
	}).offset({
		top: $(button).offset().top,
		left: $(button).offset().left
	});
	if ((/.*\.swf$/i).test(options.backgroundImage)) {
		var _loading_swf = $('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="680" height="150">\n\
								<param name="movie" value="' + options.backgroundImage + '">\n\
								<param name="menu" value="false">\n\
								<param name="quality" value="high">\n\
								<param name="wmode" value="transparent">\n\
								<embed src="' + options.backgroundImage + '" width="100%" height="100%" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>\n\
							</object>');
		_loading_swf.css({
			width: "100%",
			height: "100%"
		}).appendTo(_loading);
		_loading.css("background-image", "none");
	}
	
	setTimeout(function() {
		if (_loading.is(":visible")) {
			_loading.remove();
			alert(options.timeoutMsg);
		}
	}, options.timeout);
	
	return _loading;
};
