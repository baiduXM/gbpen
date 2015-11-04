function aboutController($scope) {
	$scope.$parent.page = true;
	$scope.$parent.diy = true;
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
	
	$scope.message = '';
}