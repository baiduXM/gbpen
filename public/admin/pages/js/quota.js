function quotaController($scope) {
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

	$scope.message = '';
	$.getJSON('json/count.json', function(json) {
		_div = '<p><span>栏目总数：'+json.data.colum_count+'</span><span>微信请求数：'+json.data.wx_request+'</span><span>微信请求数：'+json.data.wx_request+'</span></p>';
		_div2 = '<span>空间容量使用情况：</span><span style="display: inline">'+json.data.capacity_use+'M/'+json.data.capacity+'M</span>\n\
		<br /><span style="display: inline">微信流量使用情况：</span><span>'+json.data.flow_use+'G/'+json.data.flow+'G</span>';
		_div3 = '<p><span>文章数量：'+json.data.article_count+'</span><span>微信XXX：230</span><span>微信XXX：110</span></p>';
		$('.columns').append(_div);
		$('.r-info').append(_div2);
		$('.r-info').after(_div3);
	});
}