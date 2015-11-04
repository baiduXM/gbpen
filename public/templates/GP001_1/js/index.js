$(document).ready(function() {
	$('#prepics').flexslider({
		prevText: '',
		nextText: '',
		controlNav: true,
		animation: 'fade',
		animationSpeed: 1500,
		animationLoop: true,
		slideshow: true,
		directionNav: false
	});
	$(window).bind('resize.prepics', function() {
		$('#prepics, #prepics img').width($(window).width());
		$('#prepics, #prepics img').height($(window).height());
		$('header').css('margin-top', $(window).height());
	}).trigger('resize');
/*	$(document).bind('scroll.prepics', function() {
		var headerMT = parseInt($('header').css('margin-top'));
		if ($(document).scrollTop() >= headerMT) {
			$('header').css('margin-top', 0);
			$(document).scrollTop($(document).scrollTop() - headerMT);
			$('#prepics').remove();
			$(document).unbind('scroll.prepics');
			$(window).unbind('resize.prepics');
		}
	});*/
	$('#prepics .down-btn, footer .up-btn').click(function() {
		$('body').animate({scrollTop: $('header').offset().top}, 800, 'easeOutCubic', clearPrepics);
	});
});
