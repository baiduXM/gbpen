function visitController($scope) {
    $scope.$parent.page = true;
    $scope.$parent.diy = true;
    $scope.$parent.homepage = true;
    $scope.$parent.homepreview = true;
    $scope.$parent.fixedwidth = 500;
	$scope.$parent.menu = [{
		title: '访问统计',
		url: '#/visit'
	}, {
		title: '额度概况',
		url: '#/quota'
	}, {
		title: '二维码统计',
		url: '#/ercode'
	}, {
		title: '微信统计',
		url: '#/contact'
	}];
	
	$scope.message = '';
	$.getJSON('json/count.json', function(json) {
		_div = '<p><span>总访问次数：'+json.data.num+'</span><span>总访客数量：'+json.data.users+'</span></p>';
		$('.divs').append(_div);
	});
}