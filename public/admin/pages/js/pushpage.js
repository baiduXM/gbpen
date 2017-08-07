function pushpageController($scope, $http, $location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = false;
    $scope.$parent.menu = [];

    var msg = getUrlParam('msg')?getUrlParam('msg'):'';
    var img = getUrlParam('img')?getUrlParam('img'):'img.zip';
    if(msg==1001){
    	$('.refresh-name').html('A服务器图片推送失败');
    	$('.push_refresh').html('A服务器图片重推');
    }else if(msg==1002){
    	$('.refresh-name').html('B服务器图片推送失败');
    	$('.push_refresh').html('B服务器图片重推');
    }else if(msg==1003){
        $('.refresh-name').html('服务器图片推送失败');
        $('.push_refresh').html('服务器图片重推');
    }

    $('.push_refresh').click(function(){
        $('.push_refresh').css('display','none');
        $('.push_refresh_ing').css('display', 'block');
    	if(msg){
    		$http.get('../push-again?msg='+msg+'&img='+img).success(function(json){
    			$('.push_refresh_ing').css('display', 'none');
    			var _div = '<h1 style="text-align:center;font-size:30px;">'+json.msg+'</h1><br/>';
    			$('.grad_refresh').before(_div);	    		
	    	});
    	}else{
    		$http.get('../push-images').success(function(json){
    			$('.push_refresh_ing').css('display', 'none');
    			var _div = '<h1 style="text-align:center;font-size:30px;">'+json.msg+'</h1><br/>';
    			$('.grad_refresh').before(_div);	    		
	    	});
    	}    	
    });
}