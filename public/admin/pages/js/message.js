function messageController($scope, $http ,$location) {
	$scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];

	$_GET = $location.search();
	// defalut parame
    $scope.num_per_page = 10;
    $scope.MessageInit = function(){
    	this.page = $_GET['p'] == undefined ? 1 : parseInt($_GET['p']);
    	this.cat_id = $_GET['id'] == undefined ? null : parseInt($_GET['id']);
	    this._init();
	};
	$scope.MessageInit.prototype = {
	    _init : function(){
    		this._getInfo();
    	},
    	_getInfo : function(){
    		var _this = this;
    		$http.get('../message-board?page='+this.page+'&per_page='+$scope.num_per_page+'').success(function(json){
    			checkJSON(json,function(json){
    				var data = json.data.data,
    					_div = '';
    				$.each(data,function(idx, ele) {
    					_div += '\
    					<tr class="message-item'+(ele.status == 1 ? '' : ' gray')+'">\
			                <td class="first-item"><span>'+ele.content+'</span></td>\
			                <td>'+ele.name+'</td>\
			                <td>'+ele.telephone+'</td>\
			                <td>'+ele.email+'</td>\
			                <td>'+ele.creat_time+'</td>\
			                <td><span class="message-status">'+(ele.status == 1 ? '通过' : '未通过')+'</span><span class="message-del" data-id="'+ele.id+'">删除</span></td>\
			            </tr>'
    				});
    				$('.message-tb .a-table .sapces').after(_div);
    				_this._detail();
    				_this._pageflip(json);
                    _this._pagestyle();
    			})
    		});
    	},
    	_detail : function(){
    		var IsStatus,id,
    			_this = this;
			// 内容详细显示
    		$('.first-item').hover(function(){
    			$(this).parent().after('<tr class="detail"><td colspan="6"></td></tr>');
    			$('.detail td').text($(this).find('span').text());
    			$('.detail').fadeIn()
    		},function(){
    			$('.detail').remove();
    		});
    		// 审核是否通过
    		$('.message-status').click(function(){
    			$(this).closest('.message-item').hasClass('gray') ? IsStatus = 1 : IsStatus = 0;
                id = $(this).siblings('.message-del').data('id');
    			$http.post('',{id:id,status:IsStatus}).success(function(json){
	    			checkJSON(json,function(json){
	    				IsStatus ? $(this).closest('.message-item').removeClass('gray') : $(this).closest('.message-item').addClass('gray');
	    			})
	    		});
    		});
    		// 删除操作
    		$('.message-del').click(function(){
    			id = $(this).data('id');
    			$http.post('',{id:id}).success(function(json){
	    			checkJSON(json,function(json){})
	    		});
    		});
    	},
    	_pageflip : function(json){
    		var total = json.data.total,
    			page = this.page,
    			page_num;
    		page_num = Math.ceil(total / $scope.num_per_page);
            $("#Pagination").pagination(page_num,{
                current_page: (page-1),
                items_per_page: 1,
                prev_text:"上一页",
                next_text:"下一页",
                num_display_entries: 5,
                num_edge_entries: 3,
                callback: function(page_index){
                    window.location.hash = '#/message?p='+(page_index+1);
                }
            });
    	},
        _pagestyle : function(){
            $('.message-tb').height($('.message-tb .a-table').height()+50);
        }
	};
	var init = new $scope.MessageInit();
}