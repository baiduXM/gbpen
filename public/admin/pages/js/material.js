function materialController($scope) {
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

	liuxiaofan();

	
}



