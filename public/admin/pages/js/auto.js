function autoController($scope) {
	checkjs();
    $scope.$parent.fixedwidth = 0;
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
	
	$('#lottery').change(function(){
        r = $(this).val();
        switch (r){
            case '抽奖':{
                $('#lottery1,#lottery2,#lottery_mg').show();
                $('#models').hide();
                $('#out_url').hide();
                break;
            }
            case '列表':{
                $('#models').show();
                $('#lottery1,#lottery2,#lottery_mg').hide();
                $('#out_url').hide();
                break;
            }
            case '外链':{
                $('#out_url').show();
                $('#models').hide();
                $('#lottery1,#lottery2,#lottery_mg').hide();
            }
        }
    });

tanchuang();
}
$(window).resize();

	
