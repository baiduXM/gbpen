$(document).ready(function(){
	$(window).load(function(){
	var img=new Image();
	img.src=$(".indextop img,.prolistb img").attr("src");

	
	$(".indexmenu ul li a").height($(".indexmenu").height()/5-1);
	$(".indexmenu ul li a").css({
		"line-height":$(".indexmenu ul li a").height()+"px"
		})
	
	$(".next2 dl .nextright").width($(".next2 dl").width()-$(".next2 dl .nextleft").width()-2)
	$(".index-wrap").height($(window).height()-$(".fixed").height())
	$(".class-m").height($(".class").outerHeight()-$(".class-top").outerHeight())
	var myswiper=$('.indexmenu .scroll-container').swiper({
			mode:'vertical',
			scrollContainer: true,
			mousewheelControl: true,
			freeModeFluid:true,
			onTouchStart : function() {		 
			 }
		})
	$(".prolistmore").height($(".prolist").height()-2);
	$(".proclass li .prolistmore dl").css({
		"height":$(".proclass li .prolistmore dl").width()+"px","line-height":$(".proclass li .prolistmore dl").width()+"px"
		})

	});
	$(window).resize(function(){
	resize();
	})
	resize();
	
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
	$(".class-close,.opacity2").bind('click',function(){
		$(".class").addClass("page-prev page-out")
		$(".wrap").removeClass("page-next").addClass(" page-out")
		$(".opacity2").hide()
		$(".newsclass").removeClass("show")
		pageSlideOver();
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

window.onload=function(){
	var h = $('.baner').height();
   h=h*0.8;
	$('.indexmenu').height(h) ;
	var hi = $('.topimg img').height();
    hii=hi
	$('.topimg').height(hii*3/4+"px") ;
	
		
	var h6=$(".date").width();
		$(".date").height(h6);
        $(".list").height(h6);
}	

function resize(){
		
		
}