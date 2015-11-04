	
$(document).ready(function(){
	$(".list li a").click(function(){		
		$(this).next(".second").slideToggle(300).siblings(".second").slideUp("slow");
		})	
		
		jQuery(".slideBox").slide({mainCell:".bd ul",autoPlay:true});
	jQuery("#nav").slide({ type:"menu",	titCell:".nLi", targetCell:".sub",	effect:"slideDown", delayTime:300 , triggerTime:0,returnDefault:true });		
	jQuery("#navlist").slide({ 
				type:"menu",// 效果类型，针对菜单/导航而引入的参数（默认slide）
				titCell:".nLi", //鼠标触发对象
				targetCell:".sub", //titCell里面包含的要显示/消失的对象
				effect:"slideDown", //targetCell下拉效果
				delayTime:300 , //效果时间
				triggerTime:0, //鼠标延迟触发时间（默认150）
				returnDefault:true //鼠标移走后返回默认状态，例如默认频道是"预告片"，鼠标移走后会返回"预告片"（默认false）
			});	

	

			
            $('#prizes .photos-content').jCarouselLite({
			      btnPrev: '#prizes a.photos-prev',
			      btnNext: '#prizes a.photos-next',
			      visible: 4,
			      auto: 3000,
			      speed: 1000
			}).css({visibility:"visible"});

            $('#prizesb .photos-content').jCarouselLite({
			      btnPrev: '#prizesb a.photos-prev',
			      btnNext: '#prizesb a.photos-next',
			      visible: 5,
			      auto: 3000,
			      speed: 1000
			}).css({visibility:"visible"});
});
