$(document).ready(function(){
	$(window).load(function(){
	var img=new Image();
	img.src=$("ul.newslist li img").attr("src");
	$("ul.newslist li .editbox .nr").height($("ul.newslist li img").height()-$("ul.newslist li .editbox .title").height()-10)
	if($(window).width()<400){
		$("ul.newslist li .editbox .nr").css({'line-height':$("ul.newslist li .editbox .nr").height()/2+"px"})
		}else{
			if($(window).width()<540)
			{$("ul.newslist li .editbox .nr").css({'line-height':$("ul.newslist li .editbox .nr").height()/3+"px"})}
			else{$("ul.newslist li .editbox .nr").css({'line-height':$("ul.newslist li .editbox .nr").height()/4+"px"})}
			}
	
	$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
	$(".index-wrap").height($(".wrap").height()-$(".fixed").height()-$(".tell-icon").height())
	
	});
	$(window).resize(function(){
		$("ul.newslist li .editbox .nr").height($("ul.newslist li img").height()-$("ul.newslist li .editbox .title").height()-10)
		if($(window).width()<400){
		$("ul.newslist li .editbox .nr").css({'line-height':$("ul.newslist li .editbox .nr").height()/2+"px"})
		}else{
			if($(window).width()<540)
			{$("ul.newslist li .editbox .nr").css({'line-height':$("ul.newslist li .editbox .nr").height()/3+"px"})}
			else{$("ul.newslist li .editbox .nr").css({'line-height':$("ul.newslist li .editbox .nr").height()/4+"px"})}
			}
	
		$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
		$(".index-wrap").height($(".wrap").height()-$(".fixed").height()-$(".tell-icon").height())
	})
	
	                       

										
								
	function pageSlideOver(){
    $('.page-out').live('transitionend', function(){
      $(this).removeClass('page-out');
    });
    $('.page-in').live('transitionend', function(){
      $(this).removeClass('page-in');
    });
  }
  //隐藏导航跟wrap的切换
	$("#class").click(function(){
		$(".class").removeClass("page-prev").addClass("page-in");
		$(".wrap").removeClass("page-active").addClass("page-next page-in")
		$(".opacity2").show()
		pageSlideOver();
		
	})
	$(".class-close,.opacity2").on('touchstart',function(){
		$(".class").addClass("page-prev page-out")
		$(".wrap").removeClass("page-next").addClass(" page-out")
		$(".opacity2").hide()
		$(".newsclass").removeClass("show")
		pageSlideOver();
		return false;
	})
	$("#classa").click(function(){
		$(".class").removeClass("page-prev").addClass("page-in");
		$(".wrap").removeClass("page-active").addClass("page-next page-in")
		$(".opacity2").show()
		pageSlideOver();
		
	})
	$(".class-close,.opacity2").on('touchstart',function(){
		$(".class").addClass("page-prev page-out")
		$(".wrap").removeClass("page-next").addClass(" page-out")
		$(".opacity2").hide()
		$(".newsclass").removeClass("show")
		pageSlideOver();
		return false;
	})	

	$(".tell-icon .close").click(function(){
		$(".tell-icon").removeClass("display-block")
		$(".index-wrap").height($(window).height()-$(".fixed").height())
		return false
	})
	
	$(".headersearch").on('touchstart',function(){
		$(".searchbox").toggleClass("searchbox-block")
		return false
		})	
  	
	$("#font").click(function(){
		$("#up").hide()
		$("#down").show()
		$(".hide-class").removeClass("hide-class1")
		$(".font").slideToggle()
		$(".hide-class").slideUp()	
	})
	$(".font dl.big").click(function(){
		$(".edite").attr('class',"edite font-big")
		$(".news-ins").attr('class',"news-ins font-big")
		$(".font").hide()
	})
	$(".font dl.normal").click(function(){
		$(".edite").attr('class',"edite font-normal")
		$(".news-ins").attr('class',"news-ins font-normal")
		$(".font").hide()
	})
	$(".font dl.small").click(function(){
		$(".edite").attr('class',"edite font-small")
		$(".news-ins").attr('class',"news-ins font-normal")
		$(".font").hide()

	})	
	$("#first").click(function(){
		$("#up").toggle()
		$("#down").toggle()
		$(".hide-class").slideToggle()
		$(".font").slideUp()	
	})
$(".back-top").click(function(){$(".index-wrap").animate({scrollTop :0}, 800)})
	$("#share_btn").click(function(){
		$(".newsclass").addClass("show")
		$(".opacity2").show()
		return false
	})

	// 无限加载按钮
	var ajaxlist = function(selector) {
		if (!$(this).data('nexturl')) {
            $(selector + ' #more').hide();
			$(selector + ' #less_more').show();
		}
		var _this = this, thisTEXT = $(this).text();
		var _loading = buttonLoading(this, {bgcolor:'transparent'});
		$(this).text('');
		smartyAJAX($(this).data('nexturl'), selector, function(data) {
			var _data = $('<div>' + data);
			$(selector + ' ul').append(_data.find('ul').html());
			$(selector + ' #more').data('nexturl', _data.find(selector + ' #more').data('nexturl'));
			_loading.remove();
			$(_this).html(thisTEXT);
		});
		return false;
	}
	$('.newshow #more').click(function() {
		ajaxlist.call(this, ".newshow");
	});
	$('.prod #more').click(function() {
		ajaxlist.call(this, ".prod");
	});
/*
$(".newshow li,#picture li,ul.picture2 li,.news_list .news_d").hide();	
    size_li = $(".newshow li,ul.picture2 li,.case_list li,.news_list .news_d").size();
    x=4;
    $('.newshow li:lt('+x+'),ul.picture2 li:lt('+x+'),.case_list li:lt('+x+'),.news_list .news_d:lt('+x+')').show();
    $('#more').click(function () {
        x= (x+4 <= size_li) ? x+4 : size_li;
        $('.newshow li:lt('+x+'),ul.picture2 li:lt('+x+'),.case_list li:lt('+x+'),.news_list .news_d:lt('+x+')').fadeIn();
        if(x == size_li){
            $('#more').hide();
			$('#less_more').show();
        }
    });		
	
$(".prod li").hide();	
    size_lia = $(".prod li").size();
    xa=6;
    $('.prod li:lt('+xa+')').show();
    $('#more').click(function () {
        xa= (xa+6 <= size_lia) ? xa+6 : size_lia;
        $('.prod li:lt('+xa+')').fadeIn();
        if(xa == size_lia){
            $('#more').hide();
			$('#less_more').show();
        }
    });		
	*/
	
	$(".share-cance").click(function(){
		$(".newsclass").removeClass("show")
		$(".opacity2").hide()
	})
	
	// 轮滚图
	TouchSlide({ 
		slideCell:"#slideBox",
		titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
		mainCell:".bd ul", 
		effect:"leftLoop", 
		autoPage:true,//自动分页
		autoPlay:true,
		interTime:6000 //自动播放
	});
	
	 function setHeightc(){
		var list = $(".navicon");
list.height(list.width()).css('line-height',list.height() + 'px')				
	 };				 
	 setHeightc();
	 $(window).resize(function(){
		 var list = $(".navicon");
list.height(list.width()).css('line-height',list.height() + 'px')	
		setTimeout(setHeightc);
				//	setHeighta();
				//		setTimeout(setHeighta);					
	 }).resize();
	
  });
  
function smartyAJAX (url, selector, callback) {
	var callback = typeof callback === 'function' ? callback : function(){};
	$.get(url, function(data) {
		var body = /<body[^>]*>([\s\S]*?)<\/body>/gi.exec(data),
			_bodyContent = $('<div>' + body[1]);
		var listContent = _bodyContent.find(selector).html();
		var listData = typeof jSmart === 'undefined' ? listContent : (new jSmart(listContent)).fetch(PREVIEW_CONFIG);
		callback(listData);
	}, 'html');
}