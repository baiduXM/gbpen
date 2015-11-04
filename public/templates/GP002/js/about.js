$(document).ready(function() {
	var page_name;
	$('.containters .menu .nav li').each(function(){
		if($(this).hasClass('selected')){
			page_name = $(this).find('a').text();
		}
	});
	$('.page_name').text(page_name);
});