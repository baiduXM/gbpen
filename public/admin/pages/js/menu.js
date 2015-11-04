function menuController($scope, $http) { 
	$scope.$parent.page = true;
	$scope.$parent.homepage = true;
	$scope.$parent.homepreview = true;
	$scope.$parent.menu = [{
		title: '微菜单',
		url: '#/menu'
	}, {
		title: '自动应答',
		url: '#/auto'
	}, {
		title: '素材编辑',
		url: '#/material'
	}, {
		title: '添加应用',
		url: '#/addapp'
	}];
	
	// ---------全局加载tree列表------------
x = 1;
$http.get("json/test.json").success(function(data) {
    var d = data;
    aa = $('.wrap').tree({
        trees: d.data,
        bgcolor: '',
        nestable : {
            group: 1,
            dragFixX: -380,
            dragFixY: -10,
            handleClass: 'drag'
        }
    });
});
}