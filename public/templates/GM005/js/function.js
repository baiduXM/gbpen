$(document).ready(function(){
	$(".index-wrap").height($(window).height()-$(".fixed").height())
	$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
	$(window).resize(function(){
		$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
		$(".index-wrap").height($(window).height()-$(".fixed").height())
	})
	
	$("#share_btn").click(function(){
	 $(".share-con").slideToggle(100)
	});
	//隐藏导航跟wrap的切换
	$("#class").click(function(){
		$(".class").addClass("hover")
		$(".wrap").addClass("hover1")
		$(".opacity2").show()
		$(".index-wrap").off('touchmove', disableScroll);
	})
	$(".class-close,.opacity2").click(function(){
		$(".class").removeClass("hover")
		$(".wrap").removeClass("hover1")
		$(".opacity2").hide()
		$("body,html").removeClass("overflow")
	})
  	$("#first").click(function(){
		$("#up,#down").toggle()
		$(".hide-class").slideToggle()
		$(".font").slideUp()
	})
	$("#font").click(function(){
		$(".font").slideToggle()
		$(".hide-class").slideUp()
	})
	
  //幻灯片元素与类"menu_body"段与类"menu_head"时点击
	$(".menu_head .icon").click(function(){
		$(this).parent().toggleClass("cur").next(".menu_body").slideToggle(300).siblings(".menu_body").slideUp("slow");
	$(this).parent().siblings().removeClass("cur");
	});
	//返回定都
	$('.back-top').click(function(){
		$(".index-wrap").animate({scrollTop :0}, 800)

	})
	
	TouchSlide({ 
					slideCell:"#slideBox",
					titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
					mainCell:".bd ul", 
					effect:"leftLoop", 
					autoPage:true,//自动分页
					autoPlay:true,
					interTime:6000 //自动播放
				});
  });	
window.onload=function(){
    var t_h = $('.picture').height();
	$(".box-id .triangle").css({"border-width":t_h+"px"});
	
	$('.more-y').height($('.more-y').width());
	$('.more-y1').height($('.more-y').width());
	$('.circle').height($('.circle').width());
	$('.circle1').height($('.circle1').width());
	$('.circle3').height($('.circle3').width());
	$('.circle span').height($('.circle span').width());
	$('.circle1 span').height($('.circle1 span').width());
	$('.circle3 span').height($('.circle3 span').width());

	$('.about_box').height($('.about_box').width());
		$('.about_box span').height($('.about_box').width());

	}	
	
