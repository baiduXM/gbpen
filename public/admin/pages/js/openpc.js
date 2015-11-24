function openpcController($scope, $http ,$location) {
	$scope.$parent.showbox = "main";
	$scope.$parent.menu = [];

// 模板列表
	$_GET = $location.search();
    var tpltype = $_GET['tpltype'] == undefined ? 1 : parseInt($_GET['tpltype']);
    var page_num = 0; //当前页

	$('.openpc_top .hd li').click(function(event) {
        page_num = 0;
		tpltype = $(this).attr('name');
        show_hide.show();
		window.location.hash = '#/openpc?tpltype='+tpltype+'';
	});//列表结束
	tpltype == 2 ? $('.openpc_top .hd li').eq(1).addClass('on') : '';
	var per_page = 4; //控制显示数目
    var pageselectCallback = function(page_indexs, jq) {
    	// 第一页
    	if(!page_num){
			$http.get('../mytemplate-list?type='+tpltype+'&per_page='+per_page+'').success(function(json){
				checkJSON(json,function(json){
					if(tpltype == 1){
                        $scope.$parent.homepreview = false;
						// PC我的定制
						var _div1 = '';
						var d = json.data.mytemplelist;
						$.each(d, function(i) {
							_div1 += '<li '+(d[i].is_selected==0?'':'data-li="chose"')+' name="'+d[i].id+'">\n\
							          <dl class="title">('+d[i].classify+')'+d[i].name+'</dl>\n\
				                      <div class="showbox '+(d[i].is_selected?'cu':'')+'">\n\
				                        	<dl class="img"><img src="'+d[i].img+'" width="249" height="185" /></dl>\n\
				                            <dl class="zz hidden"></dl>\n\
				                            <dl class="chose_bg '+(d[i].is_selected==0?'hidden':'')+'"><i class="iconfont icon-zhengque "></i></dl>\n\
				                            <dl class="boxbtn hidden">\n\
				                            	<span class="iconbtn true"><i class="iconfont icon-zhengque "></i></span>\n\
				                                <span class="iconbtn remve"><i class="iconfont icon-cuowu "></i></span>\n\
				                            </dl>\n\
				                      </div>\n\
				                      <dl class="number">'+d[i].serial+'</dl>\n\
				                    </li>';
						});
                        _div1 == '' ? $('.System_tpl_top:nth-of-type(1)').hide() : $('.System_tpl_top:nth-of-type(1)').show();
						$('.made_tpl_list').html(_div1);
                        $('#Pagination').remove();
                        $('.pc_tpl_list').after('<div id="Pagination" class="clearfix mt_0"></div>');
	 					PC_temp(json);
					}else{
                        $scope.$parent.homepreview = true;
						// 手机我的定制
						var _div1 = '';
						var d = json.data.mytemplelist;
						_div1 = '';
						$.each(d, function(i) {
							_div1 += '<li '+(d[i].is_selected==0?'':'data-li="chose"')+' name="'+d[i].id+'">\n\
							          <dl class="title">('+d[i].classify+')'+d[i].name+'</dl>\n\
				                      <div class="showbox '+(d[i].is_selected?'cu':'')+'">\n\
				                        	<dl class="img"><img src="'+d[i].img+'" width="194" height="291" /></dl>\n\
				                            <dl class="zz hidden"></dl>\n\
				                            <dl class="chose_bg '+(d[i].is_selected==0?'hidden':'')+'"><i class="iconfont icon-zhengque "></i></dl>\n\
				                            <dl class="boxbtn hidden">\n\
				                            	<span class="iconbtn true"><i class="iconfont icon-zhengque "></i></span>\n\
				                                <span class="iconbtn remve"><i class="iconfont icon-cuowu "></i></span>\n\
				                            </dl>\n\
				                      </div>\n\
				                      <dl class="number">'+d[i].serial+'</dl>\n\
				                    </li>';
						});
                        _div1 == '' ? $('.System_tpl_top:nth-of-type(1)').hide() : $('.System_tpl_top:nth-of-type(1)').show();
						$('ul[data-made=made-phone]').html(_div1);
                        $('#Pagination').remove();
                        $('ul[data-made=System-phone]').after('<div id="Pagination" class="clearfix mt_0"></div>');
						moblic_temp(json);
					}//if结束
				});//checkJSON结束
			});// GET请求结束
		}else{
			// 第二页开始
			$http.get('../mytemplate-list?type='+tpltype+'&per_page='+per_page+'&current_page='+(page_indexs+1)+'').success(function(json){
				checkJSON(json,function(json){
					if(tpltype == 1){
	 					PC_temp(json);
					}else{
						moblic_temp(json);
					}//if结束
				});//checkJSON结束
			});// GET请求结束
		}
	};// pageselectCallback方法结束
	pageselectCallback(0);
	// PC模板管理
	function PC_temp(json){
		var _div2 = '';
		var d_temp = json.data.templelist;
		//模板选择 
		$.each(d_temp.data, function(i) {
			_div2 += '<li '+(d_temp.data[i].is_selected==0?'':'data-li="chose"')+' name="'+d_temp.data[i].id+'">\n\
			            <dl class="title">('+d_temp.data[i].classify+')'+d_temp.data[i].name+'</dl>\n\
	                	<div class="showbox">\n\
	                    	<dl class="img"><img src="'+d_temp.data[i].img+'" width="249" height="185" /></dl>\n\
	                        <dl class="zz hidden"></dl>\n\
	                        <dl class="chose_bg '+(d_temp.data[i].is_selected==0?'hidden':'')+'"><i class="iconfont icon-zhengque "></i></dl>\n\
	                        <dl class="boxbtn hidden">\n\
	                        	<span class="iconbtn true"><i class="iconfont icon-zhengque "></i></span>\n\
	                            <span class="iconbtn"><i class="iconfont icon-sousuo" onClick="window.open(\'../homepage-preview\',\'_blank\')"></i></span>\n\
	                        </dl>\n\
	                        <dl class="colorchoose hidden">';
	                        $.each(d_temp.data[i].colors, function(k,j) {
		                		_div2 += '<a href="javascript:void(0)" data-imgid="'+j.id+'" style="background:'+j.value+'" '+(d_temp.data[i].selected_style==j.description?'data-color="chose"':'')+' >\n\
			                            	<span class="chose_icon hidden"></span>\n\
			                            	<span class="chose_icon1 '+(d_temp.data[i].selected_style==j.description?'':'hidden')+'"><i class="iconfont icon-zhengque "></i></span>\n\
			                        	</a>';
	                			});
                    _div2 += '</dl></div>\n\
	                    <dl class="number">'+d_temp.data[i].serial+'</dl>\n\
	                </li>';
        });
		$('.System_tpl .System_tpl_list').html(_div2); 
		search_temp();
		page_style(d_temp);
	}
	// 手机模板管理
	function moblic_temp(json){
		var _div2 = '';
		var d_temp = json.data.templelist;
		//模板选择 
		$.each(d_temp.data, function(i) {
			_div2 += '<li '+(d_temp.data[i].is_selected==0?'':'data-li="chose"')+' name="'+d_temp.data[i].id+'">\n\
			            <dl class="title">('+d_temp.data[i].classify+')'+d_temp.data[i].name+'</dl>\n\
	                	<div class="showbox">\n\
	                    	<dl class="img"><img src="'+d_temp.data[i].img+'" width="194" height="291" /></dl>\n\
	                        <dl class="zz hidden"></dl>\n\
	                        <dl class="chose_bg '+(d_temp.data[i].is_selected==0?'hidden':'')+'"><i class="iconfont icon-zhengque "></i></dl>\n\
	                        <dl class="boxbtn hidden">\n\
	                        	<span class="iconbtn true"><i class="iconfont icon-zhengque "></i></span>\n\
	                            <span class="iconbtn"><i class="iconfont icon-sousuo" onClick="window.open(\'../mobile/homepage-preview\',\'preview\')"></i></span>\n\
	                        </dl>\n\
	                        <dl class="colorchoose hidden">';
	                        $.each(d_temp.data[i].colors, function(j) {
		                		_div2 += '<a href="javascript:void(0)" data-imgid="'+d_temp.data[i].colors[j].id+'" style="background:'+d_temp.data[i].colors[j].value+'" '+(d_temp.data[i].selected_style==d_temp.data[i].colors[j].description?'data-color="chose"':'')+' >\n\
			                            	<span class="chose_icon hidden"></span>\n\
			                            	<span class="chose_icon1 '+(d_temp.data[i].selected_style==d_temp.data[i].colors[j].description?'':'hidden')+'"><i class="iconfont icon-zhengque "></i></span>\n\
			                        	</a>';
	                			});
                    _div2 += '</dl></div>\n\
	                    <dl class="number">'+d_temp.data[i].serial+'</dl>\n\
	                </li>';
        });
		$('ul[data-made=System-phone]').html(_div2); 
		search_temp();
		page_style(d_temp);
	}
	// 模板搜索功能
	function search_temp(){
		$('.search_box .search_input,.search_btn').on('keydown click',function(event){
			var copy_text=$('');
            var classify = '',color = '';
	  		if(event.which == 13 || $(this).attr('type')=='button'){
	  			if($(this).attr('type')=='button'){
	  				var key = $(".search_input").val();
                    $.trim(key);
	  			}else{
                    key = $(this).val();
                    $.trim(key);
	  			}
	  			var litext=$(".search_input").val();
				copy_text=$('<li>'+ '<dl class="Filter_list">'+litext+'</dl>' +'<dl class="Filter_close"><i class="iconfont icon-cuowu "></i></dl></li>');
				if($(".Filter .Filter_list").text()!=litext){
					$(".Filter ul").html(copy_text);
				}
                $http.get('../template-list?type='+tpltype+'&per_page='+per_page+'&current_page=1&search='+key+'&classify='+classify+'&color='+color+'').success(function(d_temp){
                    if(tpltype == 1){
                        var _serv = '';
                        //PC模板
                        _serv += '<h1 class="System_tpl_top shaixuan_list_top">您输入<span>"'+key+'"</span>搜索的结果</h1>\n\
                                <ul class="System_tpl_list">';
                        $.each(d_temp.data.data, function(i,j) {
                            if(key == j.serial){
                                _serv += '<li '+(j.is_selected==0?'':'data-li="chose"')+' name="'+j.id+'">\n\
                                        <dl class="title">('+j.classify+')'+j.name+'</dl>\n\
                                        <div class="showbox">\n\
                                            <dl class="img"><img src="'+j.img+'" width="249" height="185" /></dl>\n\
                                             <dl class="zz hidden"></dl>\n\
                                             <dl class="chose_bg '+(j.is_selected==0?'hidden':'')+'"><i class="iconfont icon-zhengque "></i></dl>\n\
                                             <dl class="boxbtn hidden">\n\
                                                <span class="iconbtn true"><i class="iconfont icon-zhengque "></i></span>\n\
                                                 <span class="iconbtn"><i class="iconfont icon-sousuo"></i></span>\n\
                                             </dl>\n\
                                             <dl class="colorchoose hidden">';
                                            $.each(j.colors, function(k,v) {
                                                _serv += '<a href="javascript:void(0)" data-imgid="'+v.id+'" style="background:'+v.value+'" '+(j.selected_style==v.description?'data-color="chose"':'')+' >\n\
                                                            <span class="chose_icon hidden"></span>\n\
                                                            <span class="chose_icon1 '+(j.selected_style==v.description?'':'hidden')+'"><i class="iconfont icon-zhengque "></i></span>\n\
                                                        </a>';
                                                });
                                    _serv += '</dl></div>\n\
                                        <dl class="number">'+j.serial+'</dl>\n\
                                    </li>';
                            }
                        });
                        _serv += '</ul>';
                        show_hide.hide(_serv);
                    }else if(tpltype == 2){
                        var _serv = '';
                        //手机模板
                        _serv += '<h1 class="System_tpl_top shaixuan_list_top">您输入<span>'+key+'</span>搜索的结果</h1>\n\
                                <ul class="System_tpl_list phone_tpl_list">';
                        $.each(d_temp.data.data, function(i,j) {
                            if(key == j.serial){
                                _serv += '<li '+(j.is_selected==0?'':'data-li="chose"')+' name="'+j.id+'">\n\
                                        <dl class="title">('+j.classify+')'+j.name+'</dl>\n\
                                        <div class="showbox">\n\
                                            <dl class="img"><img src="'+j.img+'" width="194" height="291" /></dl>\n\
                                             <dl class="zz hidden"></dl>\n\
                                             <dl class="chose_bg '+(j.is_selected==0?'hidden':'')+'"><i class="iconfont icon-zhengque "></i></dl>\n\
                                             <dl class="boxbtn hidden">\n\
                                                <span class="iconbtn true"><i class="iconfont icon-zhengque "></i></span>\n\
                                                 <span class="iconbtn"><i class="iconfont icon-sousuo"></i></span>\n\
                                             </dl>\n\
                                             <dl class="colorchoose hidden">';
                                            $.each(j.colors, function(k,v) {
                                                _serv += '<a href="javascript:void(0)" data-imgid="'+v.id+'" style="background:'+v.value+'" '+(j.selected_style==v.description?'data-color="chose"':'')+' >\n\
                                                            <span class="chose_icon hidden"></span>\n\
                                                            <span class="chose_icon1 '+(j.selected_style==v.description?'':'hidden')+'"><i class="iconfont icon-zhengque "></i></span>\n\
                                                        </a>';
                                                });
                                    _serv += '</dl></div>\n\
                                        <dl class="number">'+j.serial+'</dl>\n\
                                    </li>';
                            }
                        });
                        _serv += '</ul>';
                        show_hide.hide(_serv);
                    }
                });
  			}// if结束
		}).focus(function(){
			$(this).val('');
		});// 搜索结束
	}// 方法结束
    // 分页样式显示
    function page_style(json){
    	if (!page_num) {
	        page_num = Math.ceil(json.total / per_page);
	        $("#Pagination").pagination(page_num,{
	            items_per_page: 1,
	            prev_text:"上一页",
	            next_text:"下一页",
	            num_display_entries: 5,
	            num_edge_entries: 3,
	            link_to: "javascript:void(0)",
	            callback: pageselectCallback
	        });
	    }
    }
	// 切换
	jQuery(".slideTxtBox").slide({
		trigger:"click"
	});
    // 手机预览
    // 选择模板
    $('ul[data-made=System-phone]').on('click','.iconfont icon-zhengque ',function(){
        if (!$('#phone').data('phoneClosed')) {
            $('#phone').data('phoneClosed_index', false);
            $('body').addClass('closephone').removeClass('closemenu');
            setTimeout(function() {
                $('#phone').data('phoneClosed', true);
            }, $.support.leadingWhitespace?1200:1);
        }
        $(window).resize();
    });
    // 预览模板
    $('ul[data-made=System-phone]').on('click','.icon-sousuo',function(){
        if ($('#phone').data('phoneClosed')) {
            $('#phone').data('phoneClosed_index', true);
            $('#phone').removeClass('phone-hover').data('phoneClosed', false);
            $('body').removeClass('closephone').addClass('closemenu');
            $('.previews').removeClass('phone-hover');
            if ($(this).attr('id') == 'preview-wechat') {
                $('#phone #weixin_preview').show();
                $('#phone #phone_preview').hide();
            }else{
                $('#phone #phone_preview').show();
                $('#phone #weixin_preview').hide();
            }
        }
        $(window).resize();
    });
	//模板
	$(".System_tpl").on('mouseenter','.showbox',function(){
        if($(this).find(".colorchoose").children().length > 1){
            $(this).find(".colorchoose").removeClass("hidden")
        }else{
            $(this).find(".zz").removeClass("hidden")
            if($(this).find(".chose_bg").hasClass("hidden")){
                $(this).find(".boxbtn").removeClass("hidden").find(".iconbtn").removeClass("hidden")
            }else{
                $(this).find(".boxbtn").removeClass("hidden").find(".iconbtn").removeClass("hidden")
                $(this).find(".boxbtn .true").addClass("hidden")
            }
        }
        $(this).siblings('.title').css('visibility','visible');
	}).on('mouseleave','.showbox',function(){
		$(this).find(".zz").addClass("hidden")
		$(this).find(".colorchoose").addClass("hidden")
		$(this).find(".boxbtn").addClass("hidden").find(".iconbtn").addClass("hidden")
        $(this).siblings('.title').css('visibility','hidden');
        //色块取消选择
        $(this).find(".chose_icon").addClass("hidden")
        $(this).attr("data-color","chose")
        $(this).find(".colorchoose a").removeAttr("data-color")
    }).on('click','.colorchoose a',function(){
        $(this).parent().siblings('.zz').removeClass("hidden")
        if($(this).parent().siblings('.chose_bg').hasClass("hidden")){
            $(this).parent().siblings('.boxbtn').removeClass("hidden").find(".iconbtn").removeClass("hidden")
        }else{
            $(this).parent().siblings('.boxbtn').removeClass("hidden").find(".iconbtn").removeClass("hidden").find(".true").removeClass("hidden")
        }
        //色块选择
		if($(this).parent().siblings(".chose_bg").hasClass("hidden")){// 未选模板
			$(this).siblings().find(".chose_icon").addClass("hidden")
			$(this).find(".chose_icon").removeClass("hidden")	
			$(this).attr("data-color","chose")
			$(this).siblings().removeAttr("data-color","chose")	
		}else{// 已选模板
			$(this).siblings().find(".chose_icon1").addClass("hidden")	
			$(this).find(".chose_icon1").removeClass("hidden")
			$(this).attr("data-color","chose")
			$(this).siblings().removeAttr("data-color","chose")
	}
	}).on('click','ul.System_tpl_list li .true',function(){	//选择模板
		var y = 0;
		$(this).addClass("hidden")
		_this = $(this);
		$(this).parent().siblings('.colorchoose').children().each(function(){
			if($(this).attr('data-color') == 'chose'){
				y = 1;
			}
		});
        if($(this).parent().siblings(".colorchoose").children().length == 0 || $(this).parent().siblings(".colorchoose").children().length == 1){
            y = 1;
        }
		if(y == 1){
			//选择模板
			var color = $(this).parent().siblings('.colorchoose').find("a[data-color='chose']").data('imgid');
			var id = $(this).parent().parent().parent().attr('name');
			$http.post('../template-operate',{id:id,type:tpltype,color:color}).success(function(json){
				checkJSON(json,function(json){
                    $('#phone_preview').attr('src','../mobile/homepage-preview');
					// 挑选模板显隐控制
					_this.parent().siblings(".chose_bg").removeClass("hidden")
					_this.parent().siblings('.colorchoose').find("a[data-color='chose'] .chose_icon").addClass("hidden")
					_this.parent().siblings('.colorchoose').find("a[data-color='chose'] .chose_icon1").removeClass("hidden")
					_this.parents("ul.System_tpl_list li").attr("data-li","chose")
					_this.parents("ul.System_tpl_list li").siblings().removeAttr("data-li","chose")
					_this.parents("ul.System_tpl_list li").siblings().find(".chose_bg").addClass("hidden")
					_this.parents("ul.System_tpl_list li").siblings().find("a[data-color='chose'] .chose_icon1").addClass("hidden")
					_this.parents("ul.System_tpl_list li").siblings().find("a[data-color='chose'] .chose_icon").removeClass("hidden")
					// 我的定制显隐控制
					_this.parents("ul").siblings('.made_tpl_list').find(".chose_bg").addClass("hidden");
					_this.parents("ul").siblings('.made_tpl_list').find(".showbox").removeClass("cu")
					_this.parents("ul").siblings('.made_tpl_list').find(".true").removeClass("hidden")
					$(".tpl_mask").show()
					$(".text_tishi").show()
					setTimeout(function(){
						$(".tpl_mask").hide()
						$(".text_tishi").hide()
						var hint_box = new Hint_box();
	                    hint_box;
					},2500);
				});
			},function(){alert(json.msg)});
		}else{alert('请选择模板颜色');}
	}).on('click','ul.made_tpl_list li .true',function(){//我的定制
		_this = $(this);
		var color = $(this).parent().siblings('.colorchoose').find("a[data-color='chose']").data('imgid');
		var id = $(this).parent().parent().parent().attr('name');
		$http.post('../template-operate',{id:id,type:tpltype,color:color}).success(function(json){
			checkJSON(json,function(json){
				// 显隐控制
				_this.parents(".showbox").addClass("cu")
				_this.parent().siblings(".chose_bg").removeClass("hidden")
				_this.parents("ul.made_tpl_list li").siblings().find(".showbox").removeClass("cu")	
				_this.parents("ul.made_tpl_list li").attr("data-made","chose").siblings().removeAttr("data-made","chose")
				_this.parents("ul.made_tpl_list li").siblings().find(".chose_bg").addClass("hidden")
				_this.parent().addClass("hidden")
				_this.parent().siblings(".zz").addClass("hidden")
				// 挑选模板显隐控制
				_this.parents("ul").siblings('.System_tpl_list').find('.chose_bg').addClass("hidden");
				_this.parents("ul").siblings('.System_tpl_list').find("a[data-color='chose'] .chose_icon1").addClass("hidden");
				_this.parents("ul").siblings('.System_tpl_list').find('.boxbtn .true').removeClass("hidden");
				_this.parents("ul").siblings('.System_tpl_list').find('.colorchoose').children().removeAttr("data-color","chose");
				$(".tpl_mask").show()
				$(".text_tishi").show()
				setTimeout(function(){
					$(".tpl_mask").hide()
					$(".text_tishi").hide()
					var hint_box = new Hint_box();
                    hint_box;
				},2500);
			});
		},function(){alert(json.msg)});
	}).on('click','ul.made_tpl_list li .remve',function(){
		$(this).parents("ul.made_tpl_list li").remove();
		//删除我的定制
		var id = $(this).parent().parent().parent().attr('name');
		$.post('../template-delete',{id:id,type:tpltype}).success(function(json){
			checkJSON(json,function(json){
				var hint_box = new Hint_box();
                hint_box;
			});
		});
	});
    // 搜索显隐控制
    var show_hide = {
        show : function(_this,d_temp){
            $('.made_tpl_list').show();
            $('.System_tpl_top').show();
            _this == undefined ? '' : _this.parent().remove();
            $('#Pagination').show();
            if(tpltype == 1){
                $('.search_input').val('');
                $('.pc_tpl_list').show();
                $('.pc-list').html('');
            }else{
                $('ul[data-made=made-phone]').children().length == 0 ? $('.phone_tpl .System_tpl_top:first').hide() : $('.phone_tpl .System_tpl_top:first').show();
                $('.phone_tpl_list').show();
                $('.phone-list').html('');
            }
        },
        hide : function(_serv){
            $('.made_tpl_list').hide();
            $('.System_tpl_top').hide();
            $('#Pagination').hide();
            if(tpltype == 1){
                $('.pc_tpl_list').hide();
                $('.pc-list').html(_serv);
            }else{
                $('.phone_tpl_list').hide();
                $('.phone-list').html(_serv);
            }
        }
    }
	//关闭搜索记录
	$(".Filter").on('click','.Filter_close',function(){
        show_hide.show($(this));
	})
}
			
			