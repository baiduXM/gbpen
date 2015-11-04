	
	window.addEventListener('load', function(){
   setTimeout(function(){ window.scrollTo(0, 1); }, 100);
});//safari浏览器可以通过此代码来隐藏地址栏
$(document).ready(function(){

//设置
myFocus.set({
	id:'myFocus',//ID
	pattern:'mF_YSlider'//风格
});
		jQuery(".picScroll-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",autoPlay:true,vis:5,trigger:"click"});

	TouchSlide({ 
					slideCell:"#slideBox",
					titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
					mainCell:".bd ul", 
					effect:"leftLoop", 
					autoPage:true,//自动分页
					autoPlay:true,
					interTime:6000 //自动播放
				});
	$(window).load(function(){
	var img=new Image();
	img.src=$("ul.stylelist li div img,.bigimg .slideBox .bd li img").attr("src");
	$(".bigimgbox .imgbg").height($(".bigimg").height());
	$(".bigimgbox .imgbg").width($(".bigimg").width());
	$("ul.stylelist li div .imgbg").height($("ul.stylelist li div img").height());
	$("ul.stylelist li div .imgbg").width($("ul.stylelist li div img").width());
	$("ul.stylelist1 li div .imgbg").height($("ul.stylelist1 li div img").height());
	$("ul.stylelist1 li div .imgbg").width($("ul.stylelist1 li div img").width());
	$(".index-wrap").height($(window).height()-$(".fixed").height()-$(".tell-icon").height())
	$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
	var myswiper=$('.indexmenu .scroll-container').swiper({
			mode:'vertical',
			scrollContainer: true,
			mousewheelControl: true,
			freeModeFluid:true,
			onTouchStart : function() {		 
			 }
		})

	});
	$(window).resize(function(){
		$(".index-wrap").height($(window).height()-$(".fixed").height()-$(".tell-icon").height())
		$(".bigimgbox .imgbg").height($(".bigimg").height());
		$(".bigimgbox .imgbg").width($(".bigimg").width());
		$("ul.stylelist1 li div .imgbg").height($("ul.stylelist1 li div img").height());
		$("ul.stylelist1 li div .imgbg").width($("ul.stylelist1 li div img").width());
		$("ul.stylelist li div .imgbg").height($("ul.stylelist li div img").height());
		$("ul.stylelist li div .imgbg").width($("ul.stylelist li div img").width());
		$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
		

	}).resize();
	
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
	})
	
	$(".tell-icon .close").click(function(){
		$(".tell-icon").removeClass("display-block")
		$(".index-wrap").height($(window).height()-$(".fixed").height())
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
	$(".share-cance").click(function(){
		$(".newsclass").removeClass("show")
		$(".opacity2").hide()
	})
  });
