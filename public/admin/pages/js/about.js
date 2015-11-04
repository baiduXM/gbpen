function aboutController($scope, $http) {
	$scope.$parent.page = false;
	$scope.$parent.diy = true;
	$scope.$parent.homepage = false;
	$scope.$parent.homepreview = false;
	$scope.$parent.menu = [];

	//钢笔动画
		$(".cxb_gangbi").addClass("cxb_gangbi_an")
		//弹窗处理
		tanchuang();
		//添加快捷入口
		$("ul.cxb_quick_box li").hover(function(){
		$(this).find("div[data-box='chose']").removeClass("hidden")
		$(this).find(".insidebox .inside_title").hide()
	},function(){
		$(this).find("div[data-box='chose']").addClass("hidden")
		$(this).find(".insidebox .inside_title").show()
	})
	$(".ousidebox .title").on('click',
		function(){
		$(this).parent().siblings("div[data-box='remov']").attr("data-box","chose").removeClass("hidden")
		$(this).parent().attr("data-box","remov").addClass("hidden")
		$(this).parent().siblings(".insidebox").find(".inside_icon").toggleClass("inside_icon_cu")
	})
	$('.main-content').css({'background':'transparent'});
	// $('#main').css({'background':'transparent','box-shadow':'4px 4px 0px transparent, 8px 8px 0px transparent'});
	// $('#main').addClass('main_bf');
}