// 单页面应用 ----------
// create the module and name it mainApp
	// also include ngRoute for all our routing needs
var mainApp = angular.module('mainApp', ['ngRoute']);

// configure our routes
mainApp.config(function($routeProvider, $httpProvider) {
	var pageLoading, timerPageLoading = 0, initRouter = location.hash;
	// route for the home page
	$routeProvider.when('/login', {
			template   : 'login...',
			controller : function() {
				location.href = '/';
			}
		}).when('/', { 
			template   : 'empty...',
			controller : "indexController"
		}).when('/loading', {
			template   : 'loading...',
			controller : function() {
				if (location.hash.indexOf('?') !== -1) {
					var loadingRoute = decodeURIComponent( location.hash.substring(location.hash.indexOf('?')+1) );
					location.hash = loadingRoute;
				} else if (!pageLoading) {
					pageLoading = function() {
						location.hash = initRouter;
					};
					clearTimeout(timerPageLoading);
					timerPageLoading = setTimeout(pageLoading, 5000);
				}
			}
		}).otherwise({ redirectTo: 'loading' });

	$.get('json/config.json', function(data) {
		var currentPage = false;
		angular.forEach(data, function(page) {
			$routeProvider.when(page.router, {
				templateUrl : page.templateUrl + ( page.templateUrl.indexOf('?') === -1 ? ('?t='+Math.random()) : ('&t='+Math.random()) ),
				controller  : function($scope, $rootScope, $injector) {
					$(window).resize();
					// Load Controller JS
					if (page.scriptUrl) {
						var elem = document.createElement('script');
						elem.setAttribute('type', 'text/javascript');
						elem.setAttribute('src', page.scriptUrl);
						elem.onload = elem.onreadystatechange = function() {
							if (! elem.readyState || /loaded|complete/.test(elem.readyState)) {
								elem.onload = elem.onreadystatechange = null;
								if (eval('typeof '+page.controller+' == "function"')) {
									//var prevMenuShow = $scope.$parent.menu.length > 0;
									var pageController = eval(page.controller);
									// Read pageController Params
									var pageCtrlParamsNameArr = pageController.toString().match(/function.*\(([^(]*)\)/)[1].split(',');
									var pageCtrlParamsArr = [], pageCtrlParamsArrNameArr = [];
									for (var k in pageCtrlParamsNameArr) {
										pageCtrlParamsNameArr[k] = pageCtrlParamsNameArr[k].trim();
										if ($injector.has(pageCtrlParamsNameArr[k])) {
											pageCtrlParamsArr[k] = $injector.get(pageCtrlParamsNameArr[k]);
											pageCtrlParamsArrNameArr[k] = 'pageCtrlParamsArr['+k+']';
										}else{
											pageCtrlParamsArrNameArr[k] = eval("typeof "+pageCtrlParamsNameArr[k]+"!=='undefined'") ? pageCtrlParamsNameArr[k] : 'null';
										}
									}
									eval("pageController(" + pageCtrlParamsArrNameArr.join(',') + ");")
									$scope.$apply();
									//var nowMenuShow = $scope.$parent.menu.length > 0;
									//if (nowMenuShow !== prevMenuShow) $(window).resize();
								}
							}
						};
						(document.body || document.getElementsByTagName('body')[0] || document.head || document.getElementsByTagName('head')[0] || document.documentElement).appendChild(elem);
					}
				}
			});
			
			//if (location.hash.replace('#','') == page.router) currentPage = page;
		});
		// route for the otherwise page
		$routeProvider.otherwise({ redirectTo: '/login' });
		
		// Refresh page
		clearTimeout(timerPageLoading);
		if (location.hash == '#/loading') {
			typeof pageLoading === 'function' ? setTimeout(pageLoading, 100) : null;
		}
		// light recording
		setTimeout(function(){
			var locathash; 
			$('nav .nav a').each(function(){
				var locat = $(this).attr('href').substr(2);
				var loca_than = location.hash.substr(2);
				var pattern = new RegExp(locat+"+$","g"); 
				if(pattern.test(loca_than)){
					locathash = $(this).attr('href');
					$(this).parent().addClass('selected').siblings().removeClass('selected');
				}
			});
			locathash ? $('#blob').animate({left:$('nav .nav a[href="'+locathash+'"]').parent().position().left},1000,'easeOutExpo') : '';
		}, 200);
		/*
		// reload page
		if (currentPage && location.hash != '#/loading') {
			var nowHash = location.hash;
			location.hash = '#/loading?' + encodeURIComponent(currentPage.router);
			setTimeout(function() {
				if (location.hash != nowHash) location.hash = nowHash;
			}, 100);
		}*/
	});

	// change request payload method to form data
	$httpProvider.defaults.headers.post = {
		'Content-Type': 'application/x-www-form-urlencoded'
	};
	$httpProvider.defaults.transformRequest = function(obj) { 
		var inside_obj = '';
		for(var p in obj){
			for(var n in obj[p]){
				if(typeof obj[p][n] == "object")
				inside_obj = 1;
			}
		}
		if(obj != undefined){
			if(obj instanceof Array || inside_obj == ''){
				var str = [];
				for(var p in obj) str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				return str.join("&");
			}else if(inside_obj == 1){
				return $.param(obj);
			}
		}
	};
	
}).factory('pageLoadingStart', function() {
	return function() {
		$('.page-loading').fadeIn('slow');
		$('div[ng-view]').css('opacity', 0.5);
	};
}).factory('pageLoadingSuccess', function() {
	return function() {
		$('.page-loading').stop().fadeOut('fast');
		$('div[ng-view]').css('opacity', 0.5);
	};
}).run(function($rootScope, $http, pageLoadingStart, pageLoadingSuccess) {
	$http.get('json/config.json').success(function(response) {
		$rootScope.pages = response;
	});
	
	var locationChangeStartOff = $rootScope.$on('$locationChangeStart', function(event, next, current) {
		if (next.substring(next.indexOf('#')) == '#') {
			event.preventDefault();
		}
    //    if (/^#article/g.test(next.substring(next.indexOf('#')))) {
    //        event.preventDefault();
    //    }
	});
	var routeChangeStartOff = $rootScope.$on('$routeChangeStart', function(event, next, current) {
        pageLoadingStart();
	});
	var routeChangeSuccessOff = $rootScope.$on('$routeChangeSuccess', function(event, next, current) {
		pageLoadingSuccess();
	});
});
 
// create the controller and inject Angular's $scope
// 主控制器\控制模块显隐
mainApp.controller('mainController', function($scope) {
	$scope.showbox = "";
	$scope.menu = [];
	$scope.domain_pc = '';
});
// 登陆信息
mainApp.controller('memberController', function($scope,$http) {
	$http.get('../customer-info').success(function(json){
		checkJSON(json,function(json){
			$scope.companyname = json.data.company_name;
			$scope.$parent.domain_pc = json.data.domain_pc;
		});
	});
});
// 引导页
mainApp.controller('indexController', function($scope) {
	$scope.$parent.showbox = "home";
	$scope.$parent.menu = [];
});
// serializeArray()转化
$.fn.serializeJson=function(){
	var serializeObj=[];
	$(this.serializeArray()).each(function(){
		serializeObj[this.name]=this.value;
	});
	return serializeObj;
};

$(document).ready(function() {
	// 初始化 ----------
	var bgImg = new Image();
	bgImg.src = 'images/bg_top.jpg';
	bgImg.onload = function() {
		$('.background .bg_thumb').fadeOut('slow', function() {
			$(this).remove();
		});
	};
	setTimeout(function() {
		$('#phone').data('phoneClosed', true);
	}, $.support.leadingWhitespace?1200:1);
	
	// 导航栏 ----------
	$('header nav ul').spasticNav({
		overlap: 0,
		reset : 1000,
		color: 'transparent',
		backToSelected_callback: function() {
			$('header nav ul li.selected').find('em').fadeIn();
			$('#blob').addClass('rotation');
		}
	}).find('li:not(#blob)').mouseenter(function() {
		if (!$(this).hasClass('selected')) {
			$('header nav ul li.selected').find('em').hide();
			$('#blob').removeClass('rotation');
		} else if (!$('#blob').hasClass('rotation')) {
			$('#blob').addClass('rotation');
			var _this = $(this);
			setTimeout(function() {
				_this.find('em').fadeIn('fast');
			}, 200);
		}
	}).mouseleave(function(){
		if ($(this).hasClass('selected')) return false;
		$(this).find('em').hide();
	}).click(function(){
		if ($(this).hasClass('selected')) return false;
		$(this).find('em').fadeIn();
		$(this).siblings().find('em').hide();
		$('#blob').addClass('rotation');
		$(this).addClass('selected').siblings().removeClass('selected');
	});
	$('.member_buttons a:nth-of-type(2)').click(function(){
		$('header nav ul li').removeClass('selected').find('em').hide();
		$('#blob').removeClass('rotation').animate({left:0},1000,'easeOutExpo');
	});
	
	// 侧边菜单 ----------
	$('#menu-close').click(function() {
		$('body').toggleClass('closemenu');
		$(window).resize();
	});

	// 手机拉出收起事件 ----------
	$('#preview-phone').mouseenter(function() {
        if($('#phone').data('phoneClosed_index')){
            return false;
        }else{
            if (!$('#phone').data('phoneClosed')) return false;
            $('.previews').addClass('phone-hover');
            $('#phone').addClass('phone-hover').removeClass('phone-hoveroff');
        }
	});
	$('#phone').mouseleave(function() {
        if($('#phone').data('phoneClosed_index')){
            return false;
        }else{
            if (!$('#phone').data('phoneClosed')) return false;
            $(this).addClass('phone-hoveroff').removeClass('phone-hover');
            $('.previews').removeClass('phone-hover');
        }
	});
	$('#phone-home, #preview-phone, #preview-wechat').click(function() {
        $('#phone').data('phoneClosed_index') == false ? $('#phone').data('phoneClosed',true) : $('#phone').data('phoneClosed_index') == undefined ? $('#phone').data('phoneClosed',true) : $('#phone').data('phoneClosed',false);
		if ($('#phone').data('phoneClosed')) {
            $('#phone').data('phoneClosed_index', true);
			$('#phone').removeClass('phone-hover').data('phoneClosed', false);
			$('body').removeClass('closephone').addClass('closemenu');
			$('.previews').removeClass('phone-hover');
			if ($(this).attr('id') == 'preview-wechat') {
				$('#phone #weixin_preview').show();
				$('#phone #phone_preview').hide();
			}else{
				$('#phone #phone_preview').show();
				$('#phone #weixin_preview').hide();
			}
		}else{
            $('#phone').data('phoneClosed_index', false);
			$('body').addClass('closephone').removeClass('closemenu');
			setTimeout(function() {
				$('#phone').data('phoneClosed', true);
			}, $.support.leadingWhitespace?1200:1);
		}
		$(window).resize();
	});
	// 手机预览区域显隐控制
	var ClosephoneThis;
    $('.closephone header nav li').click(function(){
    	if(ClosephoneThis != $(this).index()){
    		$('#phone').removeData('phoneClosed_index');
			$('body').addClass('closephone').removeClass('closemenu');
    	}
    	ClosephoneThis = $(this).index();
    });
});
 
//上传图片效果
$('.home-edite').on('mouseenter', '.preview', function(event) {
	$(this).children('.preview-edit').css('visibility','visible');
	$(this).children('.preview-mask').css('visibility','visible');
}).on('mouseleave', '.preview', function(event) {
	$(this).children('.preview-edit').css('visibility','hidden');
	$(this).children('.preview-mask').css('visibility','hidden');
}).on('click', '.homeed-right .preview-close', function(event) {
	$(this).parent().parent().remove();
	return false;
});

var mainMarginLeft = parseInt($('#main').css('margin-left'));
var previewsWidth = $('.previews').outerWidth(true);
// 计算高宽值
function width_resize(data){
	// #main
	var mainWidth = 840;
    var socrllWidth = parseInt(window.innerWidth) - parseInt(document.body.clientWidth);
	$('#menu').data('restwidth', $('#menu').data('restwidth') || 0)
	var menuWidth = $('body').hasClass('showmenu') ? ( !$('body').hasClass('closemenu') ? $('#menu').outerWidth()+parseInt($('#menu').css('margin-right')) : parseInt($('#menu').data('restwidth')) ) : 0;
	var phoneWidth = !$('body').hasClass('closephone') ? $('#phone').outerWidth(true) : 0;
	var mainExtraWidth = mainMarginLeft + parseInt($('#main').css('padding-left'));
	mainWidth = $(window).width() - menuWidth - phoneWidth - previewsWidth - mainExtraWidth - 37;
	// console.log('menuWidth:'+menuWidth,'phoneWidth:'+phoneWidth,'previewsWidth:'+previewsWidth,'mainExtraWidth:'+mainExtraWidth);
	location.hash.match(/[a-z]+?$/) == 'setting' ? '' : $('#main').css({'width' : mainWidth,'marginLeft' : mainMarginLeft});
	// .home-box
	var homeboxWidth = $(window).width() - parseInt($('#home-edit').css('margin-left')) - parseInt($('#home-edit').width()) - parseInt($('#home-edit').css('padding-left')) - parseInt($('.home-box').css('margin-right')) - 40;
	$('#diy .home-page-preview').css('width', homeboxWidth);
	$('.home-box .home-page-preview .iframs').css('width', homeboxWidth);
}
//窗口更改时计算高宽值
$(window).resize(function(){width_resize()});
// 推送静态文件
var cache_num;
setInterval(function(){
	$.get('../isneedpush',function(json){
		checkJSON(json,function(json){
			cache_num = json.data.cache_num;
			if(json.data.cache_num > 0){
				$('#preview-refresh').show();
				cache_num >99 ? $('.red_dot').text('99+') : $('.red_dot').text(json.data.cache_num);
			}else{
				$('.previews').find('#preview-refresh').remove();
			}
		});
	});
}, 10000);
window.onbeforeunload=function(event){
	if(cache_num){
		return "你更改的文件尚未推送！";
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
// 全屏按钮
$('.home-box .iframs .full_screen').hover(function(event) {
	$(this).css('opacity','1').animate({'height':'70px','width':'48px'},200);
	$('.home-box .iframs .full_screen .pos').animate({'margin-top':'8px'},200);
},function(){
	$(this).css('opacity','0.6').animate({'height':'60px','width':'38px'},200);
	$('.home-box .iframs .full_screen .pos').animate({'margin-top':''},200);
}).click(function(){
	$('.home-box .full_screen_box').fadeIn(500,function() {
		heightauto()
	});
});
$('.home-page-preview .mask').click(function(){
	$('.home-box .full_screen_box').fadeOut(500);
});
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
    };
    this.ng_fuc =  function(){
		this.init();
        $('.warning_box .cancel').click(function(){
            this.is = 0;
            del(this.is);
            $(this).parents('.box_info').hide().prev().hide();
        });
        $('.warning_box .save').click(function(){
            this.is = 1;
            del(this.is);
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
			selector 	: null,
			aspectRatio : '',
			ajaxurl		: '',
			IsMultiple	: false,
			IsBaseShow	: false,
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
	    	this._Schedule(defaults.oncallback);	// 带进度条
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
