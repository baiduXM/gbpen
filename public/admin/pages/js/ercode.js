function ercodeController($scope) {
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
	
	$scope.message = 'Look! I am an about page.';
}