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
	
	var h=$('.news_img img').height()
	h=h*0.76;
	$('.news_more').height(h).width(h)
	
	
	  
  window.onload=function(){
	  var h1 = $('.circle').width();
	  $('.circle').height(h1);
	  
	  var h3 = $('.ff').width();
	  $('.ff').height(h3);
	  
	  
	  $('ul.pro_list li').height($('.ff').width());
	 
	  $('ul.pro_list li:even').addClass("equ_d");
	  $('ul.pro_list li:odd').addClass("equ_s");
	  }

	  TouchSlide({ 
					slideCell:"#slideBox",
					titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
					mainCell:".bd ul", 
					effect:"leftLoop", 
					autoPage:true,//自动分页
					autoPlay:1000,
					interTime:6000 //自动播放
				});
  });
