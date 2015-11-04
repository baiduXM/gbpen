$(function(){
	//Init Navigation

	var holdPosition = 0;
	
	var nav = $('.swiper-nav').swiper({
		slidesPerView: '4',
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
	$('.class .scroll-container').each(function(){
		$(this).swiper({
			mode:'vertical',
			scrollContainer: true,
			mousewheelControl: true,
		})
	})
	var myswiper=$('.index-wrap .scroll-container').swiper({
			mode:'vertical',
			scrollContainer: true,
			mousewheelControl: true,
		})
	
	$('.thumbs-cotnainer').each(function(){
		$(this).swiper({
			slidesPerView:'auto',
			offsetPxBefore:25,
			offsetPxAfter:10,
			calculateHeight: true,
			mode:'horizontal',
		})
		
	})
	$(".font dl.big").click(function(){
		$(".edite").attr('class',"edite font-big")
		$(".news-ins").attr('class',"news-ins font-big")
		myswiper.reInit();
	})
	$(".font dl.normal").click(function(){
		$(".edite").attr('class',"edite font-normal")
		$(".news-ins").attr('class',"news-ins font-normal")
		myswiper.reInit();
	})
	$(".font dl.small").click(function(){
		$(".edite").attr('class',"edite font-small")
		$(".news-ins").attr('class',"news-ins font-normal")
		myswiper.reInit();
	})	
	
})