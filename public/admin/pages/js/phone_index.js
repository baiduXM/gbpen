function phone_indexController($scope,$http ,$location) {
    $scope.$parent.showbox = "main";
    $scope.$parent.homepreview = true;
    $scope.$parent.menu = [];
    $_GET = $location.search();

    $scope.phoneIndexInit = function(){
    	this.templePage = 'index';
    	// this.maininfourl = '../mhomepage-data';
    	this.maininfourl = 'json/phone_index.json';
    	this.init();
    }
    $scope.phoneIndexInit.prototype = {
    	init : function(){
    		this.imageType = [];
    		this.quickbarType = [];
    		this.otherType = [];
    		this._loading();
    		this._getInfo();
    	},
    	_loading : function(){
    		$('#phone').data('phoneClosed_index', true);
		    $('body').removeClass('closephone');
		    setTimeout(function(){ width_resize(); },4);
    	},
    	_getInfo : function(){
		    var Area_name = $_GET['Area'] || 'index',_this = this;
		    if(Area_name == 'quickbar'){
				$('.phone_index_btn .delcolumn,.phone_index-banner .navs').remove();
				$('.phone_index_btn .save').show();
				$('#phone_index .phone_index_btn').width(122)
			}
    		// 获取手机切换标签
		    $http.get(this.maininfourl).success(function(json){
		    	checkJSON(json,function(json){ 
		    		var data = json.data.pagelist,mpagetype,type;
		    		// 标签切换修改保存按钮类名
				    $('.phone_index-banner .navs li a').on('click',function(){
						var page = $(this).data('sub').split("-");
						window.location.hash = '#/phone_index?Area=' + page[1];
				    });
					if(Area_name != _this.templePage){
						$.each(json.data.templatedata,function(idx, ele) {
				    		switch(ele.type){
				    			case "image":
					    		case "images":
					    			_this.imageType.push(ele)
					    			break;
				    			case "quickbar":
				    			case "navs":
					    			_this.quickbarType.push(ele)
					    			break;
				    			case "text":
				    			case "textarea":
				    			case "navs":
					    			_this.otherType.push(ele)
					    			type = ele.type;
					    			break;
					    	}
					    });
					    if(Area_name == 'images'){
					    	mpagetype = 'images';
					    	$.each(_this.imageType,function(i, j) {
					    		var SlidepicsInit = new $scope.phoneIndexSlidepics(j);
					    	});
					    }
				    	if(Area_name == 'quickbar'){
				    		mpagetype = 'quickbar';
				    		$.each(_this.quickbarType,function(i, j) {
					    		var QuickbarInit = new $scope.phoneIndexQuickbar(j);
					    	});
				    	}
					    if(Area_name == 'other'){
					    	mpagetype = 'other';
					    	$.each(_this.otherType,function(i, j) {
					    		var OtherInit = new $scope.phoneIndexOther(j);
					    	});
					    }
					}else{
					    // 栏目排序
		    			var init = new $scope.phoneIndexPageList(json.data.m_catlist);
						mpagetype = 'index';
					}
					// 标签切换
					_this.change_sub(Area_name,mpagetype);
		    	});
		    });
    	},
    	Save_hint : function(){
			// 保存延时效果
    		var Countdown = 2;
			function inside_hint(){
		    	$(".tpl_mask").show();
				$(".text_tishi").show();
				if(Countdown > 0){
					$('#phone_index .text_tishi').text('努力保存中...('+Countdown+')')
					Countdown--;
					setTimeout(inside_hint,1000);
				}else{
					$('#phone_index .text_tishi').text('保存成功！')
					setTimeout(function(){
						$(".tpl_mask").hide();
						$(".text_tishi").hide();
					},1000);
	                $('#phone_preview').attr('src','../mobile/homepage-preview');
				}
		    }
		    inside_hint();
    	},
    	change_sub : function(phonename,mpagetype){
    		// 标签切换
    		// 标签选中状态
	    	$('.phone_index-banner .page_navs li a').each(function(){
				var page = $(this).data('sub').split("-");
				if(phonename == page[1]){
					$(this).addClass('border_red').siblings().removeClass('border_red');
	    		}
	    	});
	    	// 内容页显隐
	    	$('.phone_index-content>div').each(function(){
				var page = $(this).attr('id').split("-");
				if(mpagetype == page[1]){
					$(this).show();
					$(this).siblings().hide();
	    		}
	    	}); 
    	}
    };
    var phoneindexinit = new $scope.phoneIndexInit();
    // 手机栏目排序
    $scope.phoneIndexPageList = function(ele){
    	this.jsonData = ele;
    	this.init();
    };
    $scope.phoneIndexPageList.prototype = {
    	init : function(){
    		this.pagelist_info();
    	},
    	pagelist_info : function(){
    		var _divT = '',_divF = '',
    			data = this.jsonData,
    			_this = this;
			$.each(data,function(k,v){
				if(v.showtypetotal != 0){// 可选
	                _this.column_icon(v.type);
					_divT += '<tr class="firsttab" data-aid="'+v.id+'">\n\
		            	<td>\n\
	            			<i class="fa iconfont icon-phonehome btn '+(v.index_show == 1?'blue':'grey')+'"></i>\n\
	            			<div class="style_choose"><select>\n\
								<option '+(v.showtype == 1?'selected' : '')+' '+(v.showtypetotal >= 1?'' : 'class="none"')+' value="1">'+(v.showtypetotal >= 1?'样式一' : '')+'</option>\n\
								<option '+(v.showtype == 2?'selected' : '')+' '+(v.showtypetotal >= 2?'' : 'class="none"')+' value="2">'+(v.showtypetotal >= 2?'样式二' : '')+'</option>\n\
								<option '+(v.showtype == 3?'selected' : '')+' '+(v.showtypetotal >= 3?'' : 'class="none"')+' value="3">'+(v.showtypetotal >= 3?'样式三' : '')+'</option>\n\
								<option '+(v.showtype == 4?'selected' : '')+' '+(v.showtypetotal >= 4?'' : 'class="none"')+' value="4">'+(v.showtypetotal >= 4?'样式四' : '')+'</option>\n\
	    					</select></div>\n\
		            	</td>\n\
		            	<td><div class="tit_info">'+v.name+'</div>'+layout+'</td>\n\
		            	<td><i herf="javascript:void(0);" class="fa iconfont icon-xingxing star'+(v.star_only == 1 ? ' checked' : '')+'"></i>限<input type="text" value="'+v.show_num+'" class="show_num" />条</td>\n\
		            	<td><i class="fa iconfont icon-yidong"></i></td>\n\
		            </tr>';
	            }else{// 不可选
	                _this.column_icon(v.type);
	                _divF += '<tr class="firsttab" data-aid="'+v.id+'">\n\
		            	<td>\n\
	            			<i class="fa iconfont icon-phonehome '+(v.index_show == 1?'blue':'grey')+' not-allowed"></i>\n\
	            			<div class="style_choose"><select disabled class="not-allowed">\n\
								<option >无样式</option>\n\
	    					</select></div>\n\
		            	</td>\n\
		            	<td><div class="tit_info">'+v.name+'</div>'+layout+'</td>\n\
		            	<td></td>\n\
		            	<td></td>\n\
		            </tr>';
	            }
			});
			$('#phone_index-index .phone_table .sapces').after(_divT);
	        $('#phone_index-index .a-table .disArea').after(_divF);
		    this.IsShow();
		    this.IsStar();
		    this.ShowNum();
		    this.ChangeStyle();
    	},
    	IsShow : function(){
			// 是否展示
    		$('#phone_index-index .icon-phonehome').click(function(){
		    	var btn = $(this);
		    	var vid = $(this).parents('tr').attr('data-aid');
		    	var isshow = ($(this).hasClass('blue') ? 0 : 1);
		    	if(!btn.hasClass('not-allowed')){
		    		$http.post('../mhomepage-batchmodify',{id:vid,show:isshow}).success(function(json){
		                checkJSON(json,function(json){
		                    if(btn.hasClass('blue')){
		                        btn.removeClass('blue').addClass('grey');
		                    }else{
		                        btn.removeClass('grey').addClass('blue');
		                    }
		                    phoneindexinit.Save_hint();
		                });
		            });
		    	}
		    });
    	},
    	IsStar : function(){
    		// 星标文章
	        $('#phone_index-index .firsttab .star').click(function(){
	            var id = $(this).parents('.firsttab').data('aid');
	            if(!$(this).hasClass("checked")){
	                $(this).addClass("checked");
	                $http.post('../mhomepage-batchmodify',{id:id,star_only:1}).success(function(json){
	                    checkJSON(json,function(json){
	                        var hint_box = new Hint_box();
	                        hint_box;
	                    });
	                });
	            }else{
	                $(this).removeClass("checked");
	                $http.post('../mhomepage-batchmodify',{id:id,star_only:0}).success(function(json){
	                    checkJSON(json,function(json){
	                        var hint_box = new Hint_box();
	                        hint_box;
	                    });
	                });
	            }
	        });
    	},
    	ShowNum : function(){
    		// 显示数量
		    $('#phone_index-index tr td .show_num').change(function(){
		    	var total = $(this).val();
		    	var id = $(this).parent().parent().data('aid');
		   		$http.post('../mhomepage-batchmodify',{id:id,total:total}).success(function(json){
		   			checkJSON(json,function(json){phoneindexinit.Save_hint();});
		   		});
		    });
    	},
    	ChangeStyle : function(){
    		// 更改样式
		    $('.firsttab .style_choose select').change(function(){
		    	var style = $(this).val();
		    	var id = $(this).parents('tr').data('aid');
		   		$http.post('../mhomepage-batchmodify',{id:id,showtype:style}).success(function(json){
		   			checkJSON(json,function(json){phoneindexinit.Save_hint();});
		   		});
		    });
	       	$('#phone_index-index tr .icon-yidong').TreeList({
	       		rootNode    : 'firsttab',
	       		parentNode  : 'phone_index-index',
	       		'oncallback':function(indexlist){
	       			$http.post('../mhomepage-sortmodify', {indexlist:indexlist}).success(function(json) {
		                checkJSON(json, function(json){
		                    phoneindexinit.Save_hint();;
		                });
		            });
	       		}
	       	});
    	},
    	column_icon : function(type){
    		// 获取对应栏目图标
    		switch(parseInt(type)){
	            case 1: layout = '<i class="fa iconfont icon-liebiao"></i>';break;
	            case 3: layout = '<i class="fa iconfont icon-list"></i>';break;
	            case 2: layout = '<i class="fa iconfont icon-graph"></i>';break;
	            case 4: layout = '<i class="fa iconfont icon-wenjian"></i>';break;
	            case 6: layout = '<i class="fa iconfont icon-lianjie"></i>';break;
	            case 7: layout = '<i class="fa iconfont icon-weixin"></i>';break;
	            case 8: layout = '<i class="fa iconfont icon-dingwei"></i>';break;
	        }
    	}
    };
    // 幻灯片
    $scope.phoneIndexSlidepics = function(ele){
    	this.jsonData = ele;
    	this.init();
    };
    $scope.phoneIndexSlidepics.prototype = {
    	init : function(){
    		this.ModelSlidepicsInfo = function(parameter){
    			var _div = '<'+(parameter.Tag || 'li')+' class="phone_index-field">\n\
						<div class="materlist-first">\n\
			            	<dt class="title">'+(parameter.title || '')+'</dt>\n\
			                <dd class="msgimg">\n\
			                <img src="'+parameter.image+'" alt="" width="100%">\n\
			                </dd>\n\
			                <div class="concrol">\n\
			                	<dl><dd class="zz"></dd><dd class="concrol-edite"><i class="iconfont icon-bianji"></i></dd></dl>\n\
			                	<dl class="i1"><dd class="zz"></dd><dd class="concrol-del" name="'+(parameter.id || '')+'"><i class="iconfont icon-delete"></i></dd></dl> \n\
			                </div>\n\
			            </div>\n\
			            <div class="del-box">\n\
		                	<span class="del-sanjiao"></span>\n\
		                    <dd class="del-btn">删除</dd>\n\
			            </div>\n\
			            <div class="materlist-secondbox">\n\
			            <span class="sanjiao"></span>\n\
			            <div class="materlist-second">\n\
			            	<dt class="title">编辑名称</dt>\n\
			                <dd class="input"><input name="slidepics'+(parameter.num == undefined ? '' : '['+parameter.num+']')+'[PC_name]" type="text" value="'+(parameter.title || '')+'" /></dd>\n\
			                <dt class="title">编辑链接</dt>\n\
			                <dd class="input"><input name="slidepics'+(parameter.num == undefined ? '' : '['+parameter.num+']')+'[PC_link]" type="text" value="'+(parameter.link || '')+'" /></dd>\n\
			                <dl class="btnbox">\n\
			                	<dd class="surebtn">确定</dd>\n\
			                    <dd class="cancebtn">取消</dd>\n\
			                </dl>\n\
			            </div>\n\
			            </div>\n\
			            <div class="detailbox">\n\
			            	<span class="sanjiao"></span>\n\
			            	<div class="detailbox-main">\n\
			                	<dt class="title">确定删除此素材？</dt>\n\
			                    <dl class="btnbox">\n\
				                	<dd class="surebtn">确定</dd>\n\
				                    <dd class="cancebtn">取消</dd>\n\
			                	</dl>\n\
			                </div>\n\
			            </div><input type="hidden" name="slidepics'+(parameter.num == undefined ? '' : '['+parameter.num+']')+'[phone_info_pic]" value="'+(parameter.subimage || '')+'" />\n\
					</'+(parameter.Tag || 'li')+'>';
				return _div;
    		};
    		this.slidepics_info();
    		this.EditBtn();
    	},
    	slidepics_info : function(){
    		// 幻灯片列表
    		var htmlColumn = '',
    			_this = this;
			if(this.jsonData.type == 'images'){
				var data = this.jsonData.value,
					oddColumnWidth = 315,									// 单列宽
	    			mainWidth = $('#phone_index').width(),					// 区域总宽
	    			ColumnNum = Math.floor(mainWidth/oddColumnWidth),		// 列数
	    			lastColumnNum = (data.length%ColumnNum);				// 记录最终添加完的是第几列
				// 添加瀑布流列
				$('#phone_index_images').append('<div class="pictitle">多图文</div>');
				for(var i = 1;i < ColumnNum;i++){
					$('#phone_index_images').append('<ul id="phone_index_col'+i+'" class="phone_index_col" style="width:'+oddColumnWidth+'px"></ul>');
				}
				$.each(data,function(k,v){
					// 除余取列
					var C_num = (k+1)%ColumnNum == 0 ? (k+1) : (k+1)%ColumnNum;
					count = k;
					var _div = _this.ModelSlidepicsInfo({
						title	: v.title,
						image	: v.image,
						subimage: v.image,
						id		: v.id,
						link	: v.link,
						num		: k
					});
					$('#phone_index_col'+C_num+'').append(_div);
				});
				// 添加按钮
				var addButton = '<div class="phone_index-add">\
									<div class="up_pic up_phone"></div>\
								</div>';
				$('#phone_index_col'+(lastColumnNum+1)+'').append(addButton);
			}else{
				var data = this.jsonData.value;
				var _div = _this.ModelSlidepicsInfo({
						title	: data.title,
						image	: data.image,
						subimage: data.image,
						id		: data.id,
						link	: data.link,
						Tag 	: 'div'
					});
				var addButton = '<div class="phone_index-add">\
									<div class="up_pic up_phone"></div>\
								</div>';
				$('#phone_index_image').append('<div class="pictitle">单图</div>');
				$('#phone_index_image').append(_div+addButton);
			}
			this.IsDelete();
			this.slidepics_upload(lastColumnNum,ColumnNum);
    	},
    	layoutChange : function(){
    		// 改变列表布局
    		// 创建数组记录总个数
			var arrTemp = [],arrHtml = [],maxLength = 0,changeNum = 0,newColumnNum;
			// 新栏个数		
			newColumnNum = Math.floor($('#phone_index').width() / 315);
			for (var start = 1; start <= newColumnNum; start++) {
				var arrColumn = $("#phone_index_col"+start).html().match(/<li(?:.|\n|\r|\s)*?li>/gi);
				if (arrColumn) {
					maxLength = Math.max(maxLength, arrColumn.length);
					arrTemp.push(arrColumn);
					changeNum++;
				}
			};
			// 重新排序
			var rowStart, colStart;
			for (rowStart = 0; rowStart<maxLength; rowStart++) {
				for (colStart = 0; colStart<changeNum; colStart++) {
					if (arrTemp[colStart][rowStart]) {
						arrHtml.push(arrTemp[colStart][rowStart]);	
					}
				}	
			}
			var lastColumnNum = arrHtml.length%newColumnNum;
			// 添加按钮
			arrHtml.push('<div class="phone_index-add"><div class="up_pic up_phone"></div></div>');
			if(arrHtml.length !== 0){
				// 计算每列的行数
				var line = Math.ceil(arrHtml.length / newColumnNum);
				// 重组HTML
				var newStart = 0, htmlColumn = '';
				for (newStart; newStart < newColumnNum; newStart++) {
					htmlColumn = htmlColumn + '<ul id="phone_index_col'+ (newStart+1) +'" class="phone_index_col" style="width:315px">'+ 
						function() {
							var html = '';
							for (var i=0; i<line; i++) {
								html += arrHtml[newStart + newColumnNum * i] || '';
							}
							return html;	
						}() + '</ul> ';	
				}
				$('#phone_index_images').html(htmlColumn);
				this.slidepics_upload(lastColumnNum,newColumnNum);
			}
    	},
    	slidepics_upload : function(lastColumnNum,ColumnNum){
    		var _this = this;
			// 添加图片弹框
    		$('.up_pic').on('click',function(event) {
		        var warningbox = new WarningBox();
		        warningbox._upImage({
		            ajaxurl    : '../file-upload?target=page_index',
		            oncallback : function(json){
		            	count++;
		                $('.column_pic .template-download').remove();
		                _newpic = _this.ModelSlidepicsInfo({
							image	: json.data.url,
							subimage: json.data.name,
							num		: count
						});
						var addBtn = (lastColumnNum+1)%ColumnNum,
							addpic = (lastColumnNum+2)%ColumnNum;
		                $('#phone_index_col'+(addBtn == 0 ? (lastColumnNum+1) : addBtn)+'').append(_newpic);
		                $('#phone_index_col'+(addpic == 0 ? (lastColumnNum+2) : addpic)+'').append($('.phone_index-add'));
		                lastColumnNum++;
		            }
		        });
		    });
		},
		IsDelete : function(ColumnNum){
			var _this = this;
    		// 确定、取消按钮效果
			$(".concrol-del").hover(function(){
				$(this).parents(".phone_index-field").find(".del-box").slideDown();
			},function(){
				$(this).parents(".phone_index-field").find(".del-box").slideUp();
			}).click(function(){
				$(this).parents(".phone_index-field").find(".detailbox").slideDown();
				$(this).parents(".concrol").find(".zz").show();
			})
			$('.concrol-edite').click(function(){
				$(this).parents(".phone_index-field").find(".materlist-secondbox").slideDown();
				$(this).parents(".concrol").find(".zz").show();
			})
			$('.cancebtn').click(function(){
				$(this).parents(".phone_index-field").find(".materlist-secondbox,.detailbox").slideUp();
				$(this).parents(".phone_index-field").find(".zz").hide();
			})
			$('.detailbox .surebtn').click(function(){
				$(this).parents('.phone_index-field').fadeOut('400', function() { 
					$(this).remove();
					_this.layoutChange();
					//幻灯片删除
					if(!$(this).hasClass('new')){
						var data = $("#phone_index_images").serializeJson();
						var data1 = ($("#phone_index_images").serializeArray().length > 0?data:{slidepics : ""});
						$http.post('../mhomepage-modify',data1).success(function(){
							phoneindexinit.Save_hint();;
						});
					}
				});
			})
			$('.materlist-secondbox .surebtn').click(function(){
				var _this = $(this).closest('.phone_index-field');
				$(this).parents(".phone_index-field").find(".materlist-secondbox,.detailbox").slideUp();
				$(this).parents(".phone_index-field").find(".zz").hide();
				var PC_name = $(this).parents('.materlist-secondbox').find('input[name=PC_name]').val();
				var PC_link = $(this).parents('.materlist-secondbox').find('input[name=PC_link]').val();
				var phone_info_pic = $(this).parents('.materlist-secondbox').siblings('input[name=phone_info_pic]').val();
				var id = $(this).parents('.materlist-secondbox').siblings('.materlist-first').children('.concrol').find('.concrol-del').attr('id');
				if($(this).parents('div').hasClass('materlist-secondbox')){
					//幻灯片保存
					var data = $("#phone_index_images").serializeJson();
					$http.post('../mhomepage-modify',data).success(function(){
						phoneindexinit.Save_hint();;
						_this.removeClass('new');
					});
				}
			});
		},
		EditBtn : function(){
			// 编辑功能
			$('#phone_index_images').on('keyup','.materlist-second .input:nth-of-type(1) input',function(event) {
				$(this).parents('.materlist-secondbox').siblings('.materlist-first').children('.title').text($(this).val());
			});
		}
    };
    // Other数据
    $scope.phoneIndexOther = function(ele){
    	this.jsonData = ele;
    	this.init();
    };
    $scope.phoneIndexOther.prototype = {
    	init : function(){
    		this.GetOtherData();
    	},
    	GetOtherData : function(){
    		var _div = '';
    		$.each(this.jsonData,function(k, v) {
    			switch(v.type){
	    			case 'text':
	    				_div += '<li>\
									<dl class="leftblock">'+v.description+'</dl>\
									<dl class="rightblock">\
										<input type="text" name="" value="'+v.value+'"></input>\
									</dl>\
								</li>';
						$('#phone_index_text ul').append(_div);
	    				break;
					case 'textarea':
	    				_div += '<li>\
									<dl class="leftblock">'+v.description+'</dl>\
									<dl class="rightblock">\
										<textarea name cols="52" rows="4">'+v.value+'</textarea>\
									</dl>\
								</li>';
						$('#phone_index_textarea ul').append(_div);
	    				break;
					case 'navs':
	    				_div += '<li>\
									<dl class="leftblock">'+v.description+'</dl>\
									<dl class="rightblock">\
										<input type="text" name="" value="123"></input>\
									</dl>\
								</li>';
						$('#phone_index_text ul').append(_div);
	    				break;
	    		}
    		});
    	},
    }
    // 底部导航
    $scope.phoneIndexQuickbar = function(ele){
    	this.jsonData = ele;
    	this.init();
    };
    $scope.phoneIndexQuickbar.prototype = {
    	init : function(){
    		this.bottomnavs_info();
    	},
    	bottomnavs_info : function(){
    		var data = this.jsonData.value,
				_div1 = '',num,info;
			$.each(data,function(k,v){
				info = (v.type == 'share' ? '<span class="shareicon ml5">\
						<i class="iconfont icon-tengxunweibo '+($.inArray('txweibo', v.data) == -1 ? 'grey' : 'blue')+'" data-name="'+($.inArray('txweibo', v.data) == -1 ? 'txweibo' : '')+'"></i>\
						<i class="iconfont icon-baidu '+($.inArray('baidu', v.data) == -1 ? 'grey' : 'blue')+'"  data-name="'+($.inArray('baidu', v.data) == -1 ? 'baidu' : '')+'"></i>\
						<i class="iconfont icon-qqkongjian '+($.inArray('qqzone', v.data) == -1 ? 'grey' : 'blue')+'"  data-name="'+($.inArray('qqzone', v.data) == -1 ? 'qqzone' : '')+'"></i>\
						<i class="iconfont icon-2 '+($.inArray('weibo', v.data) == -1 ? 'grey' : 'blue')+'"  data-name="'+($.inArray('weibo', v.data) == -1 ? 'weibo' : '')+'"></i></span>' : '<input type="text" value="'+v.data+'" class="message-num" />');
				_div1 += '<li class="move_feild">\n\
							<i class="fa iconfont icon-yidong"></i>\n\
							<span><i class="fa icon-pc iconfont btn btn-show btn-desktop '+(v.pc_show?'blue':'grey')+'"></i><i class="fa iconfont icon-snimicshouji btn btn-show btn-mobile '+(v.mobile_show?'blue':'grey')+'"></i></span>\n\
							<label class="message-name">'+v.name+'</label>'+info+'\n\
						</li>';
			});
			$('.phone_service .phone_func').append(_div1);
			this.InputStyle();
			this.ShowPos();
			this.DragBlock();
			this.SaveData();
    	},
    	InputStyle : function(){
    		$('input').focus(function(){
				$(this).addClass('input_border');
				$(this).css('border','solid 1px #639cfb');
			}).blur(function(){
				$(this).removeClass('input_border')
				$(this).css('border','solid 1px #999');
			});
    	},
    	ShowPos : function(){
    		$('.phone_func span i').click(function(){
				$(this).hasClass('blue') ? $(this).removeClass('blue').addClass('grey') : $(this).removeClass('grey').addClass('blue');
			});
    	},
    	DragBlock : function(){
    		$('#phone_index-bottomnavs li .icon-yidong').TreeList({
				parentNode  : 'phone_func',
				rootNode 	: 'move_feild',
				oncallback 	: function(indexlist){}
	       	});
    	},
    	SaveData : function(){
    		$('.phone_index_btn .bottomnavs_save').click(function(){
	    		var navsArray = new Array(),
	    			show = [],data = [];
		    	$('#phone_index-bottomnavs .phone_service .phone_func .move_feild').each(function() {
		    		$(this).find('span:eq(0) i').eq(0).hasClass('blue') ? show.push('pc_show') : '';
		    		$(this).find('span:eq(0) i').eq(1).hasClass('blue') ? show.push('mobile_show') : '';
		    		if($(this).find('span:eq(1) i').length != 0){
		    			$.each($(this).find('span:eq(1) i'),function(index, ele) {
		    				$(ele).hasClass('blue') ? data.push($(ele).data('name')) : '';
		    			});
		    		}
	    			navsArray.push({
		    			name  : $(this).find('.message-name').text(),
						data  : data,
						isshow: show,
						type  : $(this).find('.btn_type').val()
		    		});				
				});
		    	$http.post('../mhomepage-modify',{bottomnavs: navsArray}).success(function(){
		    		checkJSON(json,function(json){
		    			phoneindexinit.Save_hint();;
		    		});
		    	});
		    });
    	}
    };
}