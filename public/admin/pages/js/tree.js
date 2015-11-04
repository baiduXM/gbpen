
(function($){
	$.fn.tree = function(options){
		var defaults = {
			bgcolor: null,
			bgcolorHover: '#acd3fe',
			trees: [],
			nestable: {}
		},_this = this;
		var options = $.extend(true,defaults,options);
		var _divb = '<div id="nestable2" class="dd">\n\
    					<ol class="dd-list">\n\ ';
		var _div='';
			var d = options.trees
    		var list = Array();
		for(var i = 0;i < d.length;i++){
			if(d[i].pid == 0){
		 _div += '<li data-id='+d[i].id+' class="dd-item">\n\
				    <div class="dd-handle">'+d[i].title+'\n\
				    </div>\n\
				    <div class="icon clearfix drag">\n\
				        <a href="javascript:;" class="drag" style="top:0;left:200px;"><img src="images/icon_09.jpg" width="20px" ></a>\n\
				        <a href="javascript:;" style="top:0;left:225px;"><img src="images/icon_07.jpg" width="20px" ></a>\n\
				        <a href="javascript:;" style="top:0;left:250px;"><img src="images/icon_03.jpg" width="20px" ></a>\n\
                	</div>\n\
				    <ol class="dd-list">';
					    for(var j = 0;j < d.length;j++){
					    	if(d[j].pid == d[i].id){
				_div +=	'<li class="dd-item" data-id='+d[j].id+' style="position:relative">\n\
		                    <div class="dd-handle">'+d[j].title+'\n\
						    </div>\n\
						    <div class="icon clearfix drag">\n\
						        <a href="javascript:;" class="drag" style="top:0;left:200px;"><img src="images/icon_09.jpg" width="20px" ></a>\n\
						        <a href="javascript:;" style="top:0;left:225px;"><img src="images/icon_07.jpg" width="20px" ></a>\n\
						        <a href="javascript:;" style="top:0;left:250px;"><img src="images/icon_03.jpg" width="20px" ></a>\n\
		                	</div>\n\
		                </li>';
			        }}
			_div += '</ol></li>';
			}
		}
		var _dive = '</ol></div>';
		$(_this).append(_divb+_div+_dive);
//__________________效果begin_____________________________
		k = 1;
		n = 0;
		y = 8;
		e = 1;
		$('.dd-item').mouseenter(function(){
			$(this).children('.icon').show();
			return false;
    	});
    	$('.dd-item').mouseleave(function(){
	        $(this).children('.icon').hide();
			return false;
	    });
	    $('#nestable2').nestable(options.nestable);
	    var $expand = false;
	    $('#nestable-menu').on('click', function(e)
	    {
	        if ($expand) {
	            $expand = false;
	            $('.dd').nestable('expandAll');
	        }else {
	            $expand = true;
	            $('.dd').nestable('collapseAll');
	        }
		   });

	    $('.dd').on('change', function() {
	    	$value = $('.dd').nestable('serialize');
	    	$.post("sortcategories", {jsonstr:$value},function(data){
					console.log(data);
	        	} );
	    });
	    $('.dd-handle').mousedown(function(){
	    	// if ((k == (4*n+1))||(k == (4*n+2))) {
	    	// 	$('.dd-right').css({'display':'block','box-shadow':'0 0 5px #888888','margin-top':'1px',}).animate({left:20},400);
	    	// 	$(this).find('.dd-handle').css({'background-color':'red','transition':'.5s'});
	    	// 	e++;k++;
	    	// 	if(e%2)n++;
	     //    }else
	     //    {
	     //        $('.dd-right').animate({top:-600},200).fadeOut().animate({top:0},2).animate({left:0},2);
	     //        k++;
	     //    };
	    		$('.dd-right').css({'display':'block','box-shadow':'0 0 5px #888888','margin-top':'1px',}).animate({left:20},400);
	    		$(this).css({'background-color':'red','transition':'.5s'});
	    });
	    $('.dd-handle').mouseleave(function(){
	    	
	    });
	    $('.updata').mousedown(function(){
	    	
	        return false;
	    });
	    $('.delete').mousedown(function(){
	    	$(this).parents('.dd-item').remove();
	    	options.trees.id
	        return false;
	    });
	   $('.insert').mousedown(function(){
	   //  	var _div2='';
			 // _div2 += '<li data-id='+y+' class="dd-item">\n\
				// 		    <div class="dd-handle">newdata</div>\n\
				// 		    <div style="display:none">\n\
				// 		        <img class="updata" src="images/icon_09.jpg" width="20px" height="20px" >\n\
				// 		        <img class="insert" src="images/icon_07.jpg" width="20px" height="20px" >\n\
				// 		        <img class="delete" src="images/icon_03.jpg" width="20px" height="20px" >\n\
				// 		    </div>\n\
				// 	    </li>';
		  //   $(this).parents('.dd-item').after(_div2);
		  //   y++;
	        return false;
	    });
//__________________效果end_____________________________
		_this.methods = {
			bgcolor: function(parameter){
				_this.find('.dd-item div').css('background-color',parameter);
			}
		}
		if(options.bgcolor) _this.methods;
		return _this.methods;
	};
})(jQuery);

