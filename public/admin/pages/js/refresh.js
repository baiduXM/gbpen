 function refreshController($scope, $http) { 
	$scope.$parent.showbox = "main";
    $scope.$parent.homepreview = false;
	$scope.$parent.menu = [];
        var grad_push=0;
        var grad_key=-1;
        var c_ids;
        var count=0;
        var remember_token="";
        var name="";
        
	// 推送
	window.refresh = function(parameter){
            if(grad_push==1){
                var per=0;
                per=Math.floor((100*grad_key+parameter)/(count+1));
                if(parameter == 100){
                    grad_key++;
                    if(grad_key<count){
                        $('#refresh_iframe').attr('src','http://182.61.23.43/pushlogin?name='+name+"&remember_token="+remember_token+'&end=0&push_c_id='+c_ids[grad_key]);
                    }else if(grad_key==count){
                        $('#refresh_iframe').attr('src','http://182.61.23.43/pushlogin?name='+name+"&remember_token="+remember_token+'&end=1&push_c_id=0');
                    }
                    $('.progress_bar .expand').stop(true).animate({width: 450*(per/100)},{duration:1000,easing: 'easeOutQuad'});
                    per == 100? $('.progress_title').text('已完成！') : $('.progress_title').text('完成：'+per+'%');
                    if($('.progress_title').text() == '已完成！'){
			$('.refresh-content .btn-top').append('<a class=" refresh_a" target="_blank" href="http://'+$scope.$parent.domain_pc+'">查看网站首页</a>');
			// 刷新按钮
			$('.refresh-content .ing').hide();
			$('.refresh-content .ing').find('img').removeClass('rotate');
			$('.refresh-content .ed').css({'display':'block'}).stop();
			$('.feild-content .feild-item .status').hide();
			$('.feild-content .feild-item img').show();
                    }
                }else{
                    $('.progress_bar .expand').stop(true).animate({width: 450*(per/100)},{duration:1000,easing: 'easeOutQuad'});
                    $('.progress_title').text('完成：'+per+'%');
                }
            }else{
		$('.progress_bar .expand').stop(true).animate({width: 450*(parameter/100)},{duration:1000,easing: 'easeOutQuad'});
		parameter == 100? $('.progress_title').text('已完成！') : $('.progress_title').text('完成：'+parameter+'%');
		if($('.progress_title').text() == '已完成！'){
			$('.refresh-content .btn-top').append('<a class=" refresh_a" target="_blank" href="http://'+$scope.$parent.domain_pc+'">查看网站首页</a>');
			// 刷新按钮
			$('.refresh-content .ing').hide();
			$('.refresh-content .ing').find('img').removeClass('rotate');
			$('.refresh-content .ed').css({'display':'block'}).stop();
			$('.feild-content .feild-item .status').hide();
			$('.feild-content .feild-item img').show();
		}
            }
	};

	$http.get('../isneedpush').success(function(json){
		checkJSON(json,function(json){
			if(json.data.cache_num == 0){
				$('.progress_bar .expand').css('width','450px');
				$('.progress_title').text('已完成！')
				$('.refresh-content .ed').css({'display':'block'})
				$('.refresh-content .refresh:not(.ed)').hide();
			}
		});
	});
        // 更新按钮效果
	$('.refresh-content .grad_refresh').click(function(){
            var _this=$(this);
            grad_push=1;
            $http.get('../customer-info').success(function (json) {
                var  currenttime=(new Date().getTime())/1000;
                var lasttime= json.data.lastpushtime;
                if(1||(currenttime-lasttime)>3600||lasttime==false){
                    _this.hide();
                    $('.progress_bar span').addClass('expand');
                    $('.refresh-content .ing').css('display','block');
                    $('.refresh-content .ing').find('img').addClass('rotate');
                    $http.get('../getremeber_token').success(function (json) {
                        name=json.name;
                        remember_token=json.remember_token;
                        $('#refresh_iframe').attr('src','http://182.61.23.43/pushlogin?pushgrad=1&name='+json.name+"&remember_token="+json.remember_token);
//                        $http.get('../classify-ids').success(function (json) {
//                            c_ids=json;
//                            count=json.length;
//                            refresh(100);
//                        });
                    });
                }else{
                    alert('你推送过于频繁，一小时只允许推送一次');
                    return false;
                }
            });
	});
        // 更新按钮效果
	$('.refresh-content .push_refresh').click(function(){
            var _this=$(this);
            grad_push=0;
            $http.get('../customer-info').success(function (json) {
                var  currenttime=(new Date().getTime())/1000;
                var lasttime= json.data.lastpushtime;
                if(1||(currenttime-lasttime)>3600||lasttime==false){
                    _this.hide();
                    $('.progress_bar span').addClass('expand');
                    $('.refresh-content .ing').css('display','block');
                    $('.refresh-content .ing').find('img').addClass('rotate');
                    $http.get('../getremeber_token').success(function (json) {
                        name=json.name;
                        remember_token=json.remember_token;
                        $('#refresh_iframe').attr('src','http://182.61.23.43/pushlogin?name='+json.name+"&remember_token="+json.remember_token);
                    });
                }else{
                    alert('你推送过于频繁，一小时只允许推送一次');
                    return false;
                }
            });
	});
	// 更新按钮效果
	$('.refresh-content .refresh:not(.ed)').click(function(){
            var _this=$(this);
            grad_push=0;
            $http.get('../customer-info').success(function (json) {
                var  currenttime=(new Date().getTime())/1000;
                var lasttime= json.data.lastpushtime;
                if(1||(currenttime-lasttime)>3600||lasttime==false){
                    _this.hide();
                    $('.progress_bar span').addClass('expand');
                    $('.refresh-content .ing').css('display','block');
                    $('.refresh-content .ing').find('img').addClass('rotate');
                    // $http.get('json/refresh.json').success(function(json){
                    // 	checkJSON(json,function(json){
                    // 		if(json.data.state != -1){
                    // 			setTimeout(function(){
                    // 				refresh(json.data.state);
                    // 			},1000);
                    // 		}
                    // 	});
                    // });
                    $('#refresh_iframe').attr('src','../push');
                }else{
                    alert('你推送过于频繁，一小时只允许推送一次');
                    return false;
                }
            });
	});
	var loadPageSize = function(){
		var mainMarginLeft = ($(window).width() - 840)/2;
        $('#main').css({'width' : 840,'marginLeft' : mainMarginLeft});
	}();
 }