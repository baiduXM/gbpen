
function addappController($scope) {
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
	
	$(".app").hover(function(){
		$(this).find("div[data-box='chose']").removeClass("hidden")
		$(this).find(".inside .t2").hide()
	},function(){
		$(this).find("div[data-box='chose']").addClass("hidden")
		$(this).find(".inside .t2").show()
	})
	$(".down").on('click',
		function(){
		$(this).parent().siblings("div[data-box='remov']").attr("data-box","chose").removeClass("hidden")
		$(this).parent().attr("data-box","remov").addClass("hidden")
	})
}