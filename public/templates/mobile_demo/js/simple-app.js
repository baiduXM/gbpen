$(function(){
	//Init Navigation
	var holdPosition = 0;
	
	var nav = $('.swiper-nav').swiper({
		slidesPerView:4,
		freeMode:true,
		freeModeFluid:true,
		paginationClickable: true,
		onTouchMove:function(){
			slide_state();
		}
	})
	$('.arrow-left').on('click', function(e){
    e.preventDefault()
    nav.swipePrev()
	slide_state();
  })
  $('.arrow-right').on('click', function(e){
    e.preventDefault()
    nav.swipeNext()
	slide_state();
  })
	if($('.menu .swiper-slide').last().hasClass('swiper-slide-visible')){
		$('.arrow-right').addClass("current1");

	}
	function slide_state(){
		if($('.menu .swiper-slide').first().hasClass('swiper-slide-visible')){
			$('.arrow-left').removeClass("current").addClass("current1");	
		}else{
			$('.arrow-left').removeClass("current1").addClass("current");
		}
		if($('.menu .swiper-slide').last().hasClass('swiper-slide-visible')){
			$('.arrow-right').removeClass("current").addClass("current1");
		}else{
			$('.arrow-right').removeClass("current1").addClass("current");
		}
	}
	var menuswiper=$('.class .scroll-container').swiper({
			mode:'vertical',
			scrollContainer: true,
			mousewheelControl: true,
			freeModeFluid:true,
			onTouchStart : function() {		 
			 }

		})
	
	
	
	
	var mySwiper = new Swiper('.baner .swiper-container',{
	pagination: '.pagination',
    paginationClickable: true,
	loop:true,
    mode: 'vertical'
	
  })
	//幻灯片元素与类"menu_body"段与类"menu_head"时点击
	$(".menu_head .icon").click(function(){
		$(this).parent().toggleClass("cur").next(".menu_body").toggleClass("cu").siblings(".menu_body").removeClass("cu");
		$(this).parent().siblings().removeClass("cur");
		$('.class .scroll-container').find('.swiper-wrapper').css({'transform':'translate3d(0px, 0px, 0px)','-webkit-transform':'translate3d(0px, 0px, 0px)','transition': '0.3s'});
		menuswiper.reInit();
	});
	
	
	//Thumbs
	$('.thumbs-cotnainer').each(function(){
		$(this).swiper({
			slidesPerView:'auto',
			offsetPxBefore:25,
			offsetPxAfter:10,
			calculateHeight: true
		})
	})

	
})