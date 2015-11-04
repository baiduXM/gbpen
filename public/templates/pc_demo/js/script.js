$(document).ready(function() {
	$('#category .nexts-btn').click(function() {
		var _this = this, thisTEXT = $(this).text();
		var _loading = buttonLoading(this, {bgcolor:'transparent'});
		$(this).text('');
		smartyAJAX($(this).attr('href'), '#category', function(data) {
			//$('#category ul.list').append(data);
			var _data = $('<div>' + data);
			$('#category ul.list').append(_data.find('ul.list').html());
			$('#category a.nexts-btn').attr('href', _data.find('a.nexts-btn').attr('href'));
			_loading.remove();
			$(_this).html(thisTEXT);
		});
		return false;
	});
});

$(document).bind('mousewheel', function(event, delta) {
	$.scrollTo.window().queue([]).stop();
	if (delta < 0) {
		//if ($(document).scrollTop()+100 > parseInt($('header').css('margin-top')) && $('#prepics').length > 0) return true;
		$('body').stop().scrollTo('+=' + 150, 500, {axis:'y', easing:'easeOutQuart', onAfter:clearPrepics});
	} else {
		$('body').stop().scrollTo('-=' + 150, 500, {axis:'y', easing:'easeOutQuart', onAfter:clearPrepics});
	}
	return false;
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

function clearPrepics () {
	if ( $('#prepics').length > 0) {
		var headerMT = parseInt($('header').css('margin-top'));
		if ($(document).scrollTop() >= headerMT) {
			$('header').css('margin-top', 0);
			$(document).scrollTop($(document).scrollTop() - headerMT);
			$('#prepics').remove();
			$(window).unbind('resize.prepics');
		}
	}
}