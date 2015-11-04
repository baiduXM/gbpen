$(function($) {
	init = function() {
		var left_able = false;

		//导航滚动事件控制
	    $(window).scroll( function() { 
	        var left_nav = $(".fixed_nav"),
	        l_h = left_nav.height(),
	        w_h = $(window).height(),
	        lw_h = l_h - w_h;     
	        
	        if (lw_h > 0) {
	            if ($(document).scrollTop() < lw_h) {
	              left_nav.css({
	                  position: 'absolute',
	                  top: 0
	              });
	            } else if ($(document).scrollTop() >= lw_h) {
	              left_nav.css({
	                  position: 'fixed',
	                  top: -lw_h + 'px'
	              });
	            }
	        }

	        $('.container').css('left', -$(document).scrollLeft());	        
	        
	        if($(window).width() <= 1200){
	        	left_nav.css({
	        		left: -$(document).scrollLeft(),
	        		marginLeft: 0
	        	});
	        	left_able = true;
	        }
	        
	    });
		
		$(window).resize(function(event) {
			var left_nav = $(".fixed_nav");
			if(left_able && $(window).width() > 1200 && $(window).width() <1366){
	        	left_nav.css({
	        		left: 0,
	        		marginLeft: 0
	        	});
	        }
	        else if(left_able && $(window).width() > 1366){
	        	left_nav.css({
	        		left: '50%',
	        		marginLeft: '-600px'
	        	});
	        }
		});


	}

	init();
});